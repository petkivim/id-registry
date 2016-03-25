<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Identifier Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelIdentifier extends JModelAdmin {

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $type    The table name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  JTable  A JTable object
     *
     * @since   1.6
     */
    public function getTable($type = 'Identifier', $prefix = 'IsbnregistryTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed    A JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm(
                'com_isbnregistry.identifier', 'identifier', array(
            'control' => 'jform', 'load_data' => $loadData
                )
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since   1.6
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState(
                'com_isbnregistry.edit.identifier.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Adds new identifiers to the database.
     * @param array $identifiers array that contains the identifiers to be added
     * @param int $identifierBatchId batch id of the identifiers
     * @return bool true on success; otherwise false
     */
    public function addNew($identifiers, $identifierBatchId) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->addNew($identifiers, $identifierBatchId);
    }

    /**
     * Returns identifiers with the given batch id.
     * @param int $identifierBatchId
     * @param boolean $orderByIdentifier when true the results are sorted by
     * identfier in desceinding order, by default the value is false which
     * means that the results are sorted by id in ascending order
     * @return list of identifier objects
     */
    public function getIdentifiers($identifierBatchId, $orderByIdentifier = false) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->getIdentifiers($identifierBatchId, $orderByIdentifier);
    }

    /**
     * Returns identifiers with the given batch id.
     * @param int $identifierBatchId
     * @return array array identifier strings
     */
    public function getIdentifiersArray($identifierBatchId) {
        // Get result
        $list = $this->getIdentifiers($identifierBatchId);
        // Results array
        $results = array();
        // Loop through the objects list
        foreach ($list as $item) {
            array_push($results, $item->identifier);
        }
        // Return array
        return $results;
    }

    /**
     * Delete all identifiers related to the batch identified by
     * the given batch id.
     * @param int $batchId batch id
     * @return int number of deleted rows
     */
    public function deleteByBatchId($batchId) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->deleteByBatchId($batchId);
    }

    /**
     * Cancels the given identifier and removes it from the current publication.
     * The identifier can be reused later.
     * @param string $identifier identifier to be canceled 
     * @return boolean true on success, false on failure
     */
    public function cancelIdentifier($identifier) {
        // Get db access
        $table = $this->getTable();
        // Start transaction
        $table->transactionStart();

        // Load identifier object
        $identifierObj = $table->getIdentifier($identifier);
        // Check that we have a result
        if (!$identifierObj) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_DELETE_IDENTIFIER_IDENTIFIER_NOT_FOUND'));
            $table->transactionRollback();
            return false;
        }

        // Load publication model
        $publicationModel = JModelLegacy::getInstance('publication', 'IsbnregistryModel');
        // Get an instance of identifier batch model
        $identifierBatchModel = $this->getInstance('Identifierbatch', 'IsbnregistryModel');
        // Check how many identifiers were created on the same bacth
        if ($identifierObj->identifier_count + $identifierObj->identifier_canceled_used_count == 1 || $identifierObj->identifier_canceled_count == (($identifierObj->identifier_canceled_used_count + $identifierObj->identifier_count) - 1)) {
            // If this is the only identifier OR there's only one identifier left,
            //  the batch object can be deleted
            if (!$identifierBatchModel->delete($identifierObj->identifier_batch_id)) {
                $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_DELETE_IDENTIFIER_DELETE_BATCH_FAILED'));
                $table->transactionRollback();
                return false;
            }
            // Delete messages related to the batch
            $messageModel = JModelLegacy::getInstance('message', 'IsbnregistryModel');
            $messageModel->deleteByBatchId($identifierObj->identifier_batch_id);
            // Try to remove publication's identifiers
            if ($identifierObj->publication_id != 0 && !$publicationModel->removeIdentifiers($identifierObj->publication_id)) {
                $table->transactionRollback();
                return false;
            }
        } else {
            // Update canceled count
            if (!$identifierBatchModel->increaseCanceledCount($identifierObj->identifier_batch_id, $identifierObj->identifier_canceled_count)) {
                $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_DELETE_IDENTIFIER_DELETE_BATCH_FAILED'));
                $table->transactionRollback();
                return false;
            }
            // Update publication
            if ($identifierObj->publication_id != 0 && !$publicationModel->removeIdentifier($identifierObj->publication_id, $identifier)) {
                $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_DELETE_IDENTIFIER_UPDATE_PUBLICATION_FAILED'));
                $table->transactionRollback();
                return false;
            }
        }
        // Try to delete identifier from db
        if (!$this->delete($identifierObj->id)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_DELETE_IDENTIFIER_FAILED'));
            $table->transactionRollback();
            return false;
        }

        // Load publication identifier range model
        $publisherIdentifierRangeModel = JModelLegacy::getInstance('publisher' . strtolower($identifierObj->identifier_type) . 'range', 'IsbnregistryModel');
        // Get publisher identifier range
        $publisherIdentifierRange = $publisherIdentifierRangeModel->getItem($identifierObj->publisher_identifier_range_id);

        // Create new identifier canceled object
        $identifierCanceled = array(
            'id' => 0,
            'identifier' => $identifier,
            'identifier_type' => $identifierObj->identifier_type,
            'category' => $publisherIdentifierRange->category,
            'publisher_id' => $identifierObj->publisher_id,
            'publisher_identifier_range_id' => $identifierObj->publisher_identifier_range_id
        );

        // Increase publisher identifier range canceled counter
        $publisherIdentifierRange->canceled += 1;
        // Check if closed
        if ($publisherIdentifierRange->is_closed) {
            // Update is_closed and is_active
            $publisherIdentifierRange->is_closed = false;
        }
        // Update to database
        if (!$publisherIdentifierRangeModel->increaseCanceled($publisherIdentifierRange, 1)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_DELETE_IDENTIFIER_UPDATE_IDENTIFIER_RANGE_FAILED'));
            $table->transactionRollback();
            return false;
        }

        // Get an instance of identifier canceled model
        $identifierCanceledModel = $this->getInstance('Identifiercanceled', 'IsbnregistryModel');
        // Save new identifier canceled object
        if (!$identifierCanceledModel->save($identifierCanceled)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_DELETE_IDENTIFIER_SAVE_IDENTIFIER_CANCELED_FAILED'));
            $table->transactionRollback();
            return false;
        }

        // Commit transaction
        $table->transactionCommit();

        // Return true
        return true;
    }

}
