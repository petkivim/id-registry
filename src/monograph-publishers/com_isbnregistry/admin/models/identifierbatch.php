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
     * Deletes the batch identified by the given id. Range model's counters 
     * are not updated.
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
     * identifier range. Range model's counters are updated.
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

        // Check that no single identifiers have been canceled
        if ($identifierBatch->identifier_canceled_count != 0) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_IDENTIFIER_BATCH_DELETE_FAILED_CANCELED_COUNT_NOT_ZERO'));
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
        if ($identifierBatch->publisher_identifier_range_id != 0) {
            if (!$rangeModel->decreaseByCount($identifierBatch->publisher_identifier_range_id, $identifierBatch->identifier_count)) {
                $table->transactionRollback();
                return false;
            }
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
        // Get identifier objects
        $identifiers = $identifierModel->getIdentifiers($identifierBatch->id, true);
        // Check that the numbers match
        if (sizeof($identifiers) != $identifierBatch->identifier_count + $identifierBatch->identifier_canceled_used_count) {
            $table->transactionRollback();
            return false;
        }

        // Go through identifiers that should be new and not canceled before.
        // These identifiers must have the same publisher identifier range id
        // with the batch object. If the id is not the same there are probably
        // canceled identifiers that are from a later range. In this case delete
        // process should be different and that's not supported.
        for ($i = 0; $i < $identifierBatch->identifier_count; $i++) {
            if ($identifiers[$i]->publisher_identifier_range_id != $identifierBatch->publisher_identifier_range_id) {
                $table->transactionRollback();
                return false;
            }
        }
        // Get an instance of identifier canceled model
        $identifierCanceledModel = $this->getInstance('Identifiercanceled', 'IsbnregistryModel');
        // Get publisher identifier range object - we need to know the category
        $publisherIdentifierRange = $rangeModel->getItem($identifiers[0]->publisher_identifier_range_id);

        // Cache for publisher ranges
        $rangeCache = array();
        // Add publisher range to cache
        $rangeCache[$publisherIdentifierRange->id] = $publisherIdentifierRange;

        // Reused identifiers must be moved back to cancelled identifiers
        for ($i = $identifierBatch->identifier_count; $i < $identifierBatch->identifier_count + $identifierBatch->identifier_canceled_used_count; $i++) {
            // Create new identifier canceled object
            $identifierCanceled = array(
                'id' => 0,
                'identifier' => $identifiers[$i]->identifier,
                'identifier_type' => $identifierBatch->identifier_type,
                'category' => $publisherIdentifierRange->category,
                'publisher_id' => $identifierBatch->publisher_id,
                'publisher_identifier_range_id' => $identifiers[$i]->publisher_identifier_range_id
            );

            // Save new identifier canceled object
            if (!$identifierCanceledModel->save($identifierCanceled)) {
                $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_DELETE_IDENTIFIER_SAVE_IDENTIFIER_CANCELED_FAILED'));
                $table->transactionRollback();
                return false;
            }

            // Check if publisher range is in cache
            if (!array_key_exists($identifiers[$i]->publisher_identifier_range_id, $rangeCache)) {
                $rangeCache[$identifiers[$i]->publisher_identifier_range_id] = $rangeModel->getItem($identifiers[$i]->publisher_identifier_range_id);
            }
            // Increase publisher identifier range canceled counter
            $rangeCache[$identifiers[$i]->publisher_identifier_range_id]->canceled += 1;
            // Update to database
            if (!$rangeModel->increaseCanceled($rangeCache[$identifiers[$i]->publisher_identifier_range_id], 1)) {
                $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_DELETE_IDENTIFIER_UPDATE_IDENTIFIER_RANGE_FAILED'));
                $table->transactionRollback();
                return false;
            }
        }
        // Put identifier ids into array that we can delete them easily
        $identifiersDelete = array();
        for ($i = 0; $i < sizeof($identifiers); $i++) {
            array_push($identifiersDelete, $identifiers[$i]->id);
        }
        // Delete identifiers
        if (!$identifierModel->delete($identifiersDelete)) {
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

    /**
     * Increase identifier batch canceled count by one.
     * @param int $identifierBatchId id of the identifier batch
     * @param int $count current count
     * @return boolean true on success, false on failure
     */
    public function increaseCanceledCount($identifierBatchId, $count) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->increaseCanceledCount($identifierBatchId, $count);
    }

}
