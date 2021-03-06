<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivim�ki
 * @copyright	Copyright (C) 2015 Petteri Kivim�ki. All rights reserved.
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

        // Check that no single identifiers have been canceled or deleted
        if ($identifierBatch->identifier_canceled_count != 0 || $identifierBatch->identifier_deleted_count != 0) {
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

        // Check if canceled identifiers have been used and handle them if needed
        if ($identifierBatch->identifier_canceled_used_count > 0) {
            // Get an instance of identifier canceled model
            $identifierCanceledModel = $this->getInstance('Identifiercanceled', 'IsbnregistryModel');

            // Array for ids of new identifiers that can be canceled by updating
            // range counters
            $newIdentifierIds = array();

            // Counter for new identifiers that have been added to the table
            $j = 0;
            // Go through all the identifiers and add their ids to new identifiers
            // table. There might be new and canceled identifiers from the
            // same range and we must be able to separate them
            foreach ($identifiers as $identifier) {
                // Identifier batch's identifier count tells the number of new
                // identifiers. The identifiers are sorted by identfier in 
                // descending order which means that new identifiers come
                // first. As we know the number of new identifiers so we
                // can collect their ids.
                if ($identifier->publisher_identifier_range_id == $identifierBatch->publisher_identifier_range_id && $j < $identifierBatch->identifier_count) {
                    // Add identifier to new identifiers array
                    array_push($newIdentifierIds, $identifier->id);
                    // Update counter
                    $j++;
                }
            }

            // Get publisher identifier range object - we need to know the category
            $publisherIdentifierRange = $rangeModel->getItem($identifiers[0]->publisher_identifier_range_id);
            // Cache for publisher ranges
            $rangeCache = array();
            // Add publisher range to cache
            $rangeCache[$publisherIdentifierRange->id] = $publisherIdentifierRange;

            // Go through all the identifiers and move reused identifiers back to
            // canceled identifiers. Canceled identifiers can be recognized from
            // publisher identifier range id which may be different from identifier
            // batch object's publisher identifier range id. For new identifiers that
            // are from the range defined by identifier batch, it's enough to update
            // the range's counters and pointers, and delete the identifiers.
            // The range's counters were already updated earlier and all the used
            // identifiers will be deleted later. However, some canceled identifiers
            // might be from the same range with new identifiers which is why
            // we use the new identifiers array as help.
            for ($i = 0; $i < $identifierBatch->identifier_count + $identifierBatch->identifier_canceled_used_count; $i++) {
                // If publisher identifier range ids do not match, this identifier 
                // must be added to canceled identifiers. The identifier must
                // be added to canceled identifiers also in case that range ids
                // match, but identifier's id is not found from new identifiers
                // array.
                if ($identifiers[$i]->publisher_identifier_range_id != $identifierBatch->publisher_identifier_range_id || !in_array($identifiers[$i]->id, $newIdentifierIds)) {
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
                    // Check if closed
                    if ($rangeCache[$identifiers[$i]->publisher_identifier_range_id]->is_closed) {
                        // Update is_closed
                        $rangeCache[$identifiers[$i]->publisher_identifier_range_id]->is_closed = false;
                    }
                    // Update to database
                    if (!$rangeModel->increaseCanceled($rangeCache[$identifiers[$i]->publisher_identifier_range_id], 1)) {
                        $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_DELETE_IDENTIFIER_UPDATE_IDENTIFIER_RANGE_FAILED'));
                        $table->transactionRollback();
                        return false;
                    }
                }
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

    /**
     * Increase identifier batch deleted count by one.
     * @param int $identifierBatchId id of the identifier batch
     * @param int $count current count
     * @return boolean true on success, false on failure
     */
    public function increaseDeletedCount($identifierBatchId, $count) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->increaseDeletedCount($identifierBatchId, $count);
    }

}
