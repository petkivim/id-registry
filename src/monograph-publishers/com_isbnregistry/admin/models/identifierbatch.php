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
 * Identifier Batch Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelIdentifierbatch extends JModelAdmin {

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
    public function getTable($type = 'Identifierbatch', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.identifierbatch', 'identifierbatch', array(
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
                'com_isbnregistry.edit.identifierbatch.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Adds new identifier batch to the database.
     * @param array $params array that contains the parameters
     * @return int id of the new identifier batch entry
     */
    public function addNew($params) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->addNew($params);
    }

    /**
     * Updates the publication id of the batch identifier identified by
     * the given id.
     * @param int $identifierBatchId batch identifier id
     * @param int $publicationId publication id
     * @return boolean true on success; otherwise false
     */
    public function updatePublicationId($identifierBatchId, $publicationId) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->updatePublicationId($identifierBatchId, $publicationId);
    }

    /**
     * Deletes the batch identified by the given id.
     * @param int $id batch id
     * @return boolean true on success; otherwise false
     */
    public function delete($id) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->delete($id);
    }

    /**
     * Deletes all the batches related to the publisher identified by the
     * given publisher id. Also all the identifiers related to the batches
     * are deleted.
     * @param int $publisherId publisher id
     * @return boolean true on success; otherwise false
     */
    public function deleteByPublisherId($publisherId) {
        // Get db access
        $table = $this->getTable();
        // Get batch ids related to the publisher
        $batchIds = $table->getBatchIdsByPublisher($publisherId);
        // If batches are deleted, also identifiers must be deleted
        if ($table->deleteByPublisherId($publisherId) > 0) {
            // Get an instance of identifier model
            $identifierModel = $this->getInstance('Identifier', 'IsbnregistryModel');
            // Delete identifiers
            foreach ($batchIds as $batchId) {
                $identifierModel->deleteByBatchId($batchId);
            }
            return true;
        }
        return false;
    }

    /**
     * Get the identfier type of the batch identified by the given
     * identifier batch id.
     * @param int $identifierBatchId identifier batch id
     * @return string identifier type: ISBN or ISMN
     */
    public function getIdentifierType($identifierBatchId) {
        // Get db access
        $table = $this->getTable();
        // Return identifier type
        return $table->getIdentifierType($identifierBatchId);
    }

    /**
     * Deletes the batch identified by the given id. The batch is deleted
     * if and only if it's the last batch generated from the same publisher
     * identifier range.
     * @param int $identifierBatchId batch id
     * @return boolean true on success; otherwise false
     */
    public function safeDelete($identifierBatchId) {
        // Get db access
        $table = $this->getTable();
        // Load batch
        $identifierBatch = $table->getIdentifierBatch($identifierBatchId);
        if (!$identifierBatch) {
            return false;
        }
        // Get the id of last batch from the same publisher identifier range
        $lastId = $table->getLast($identifierBatch->publisher_identifier_range_id);
        // Check that the last id and the id to be deleted match
        if ($identifierBatch->id != $lastId) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_IDENTIFIER_BATCH_DELETE_FAILED_NOT_LATEST'));
            return false;
        }
        // Load message model
        $messageModel = JModelLegacy::getInstance('message', 'IsbnregistryModel');
        // Check that we have a model
        if (!$messageModel) {
            return false;
        }
        // Check that there's no messages related to this batch
        if ($messageModel->getMessageCountByBatchId($identifierBatch->id) > 0) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_IDENTIFIER_BATCH_DELETE_FAILED_MESSAGES_EXIST'));
            return false;
        }
        // Load publisher identifier range model
        $rangeModel = JModelLegacy::getInstance('publisher' . strtolower($identifierBatch->identifier_type) . 'range', 'IsbnregistryModel');
        // Check that we have a model
        if (!$rangeModel) {
            return false;
        }
        // Start transaction
        $table->transactionStart();
        // Decrease range models' counters
        if (!$rangeModel->decreaseByCount($identifierBatch->publisher_identifier_range_id, $identifierBatch->identifier_count)) {
            $table->transactionRollback();
            return false;
        }
        // Check if there's a publication related to the batch
        if ($identifierBatch->publication_id > 0) {
            // Load publication model
            $publicationModel = JModelLegacy::getInstance('publication', 'IsbnregistryModel');
            // Check that we have a model
            if (!$publicationModel) {
                return false;
            }
            // Try to update publication's identifiers
            if (!$publicationModel->removeIdentifiers($identifierBatch->publication_id)) {
                $table->transactionRollback();
                return false;
            }
        }
        // Get an instance of identifier model
        $identifierModel = $this->getInstance('Identifier', 'IsbnregistryModel');
        // Delete identifiers
        if (!$identifierModel->deleteByBatchId($identifierBatch->id)) {
            $table->transactionRollback();
            return false;
        }
        // Delete batch
        if (!$table->deleteBatch($identifierBatch->id)) {
            $table->transactionRollback();
            return false;
        }
        // Commit
        $table->transactionCommit();
        return true;
    }

}
