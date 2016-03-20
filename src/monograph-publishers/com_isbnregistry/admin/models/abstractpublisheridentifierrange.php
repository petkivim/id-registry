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
 * Abstract Publisher Identifier Range Model
 *
 * @since  1.0.0
 */
abstract class IsbnregistryModelAbstractPublisherIdentifierRange extends JModelAdmin {

    abstract public function getRangeModelName();

    abstract public function getRangeId($publisherRange);

    abstract public function getCheckDigit($identifier);

    abstract public function updateActiveIdentifier($publisherId, $identifier);

    abstract public function getIdentifierVarTotalLength();

    /**
     * Method to store a new publisher identifier range into database. 
     * All the other ranges of the same type are disactivated and the new 
     * range is set active.
     * 
     * @param Object $range range object which subset the publisher 
     * identifier range is
     * @param int $publisherId id of the publisher that owns the isbn range
     * @param int $publisherIdentifier publisher identifier of the publisher 
     * that owns the range to be created
     * @return boolean returns true if and only if the object was successfully 
     * saved to the database; otherwise false
     */
    public function saveToDb($range, $publisherId, $publisherIdentifier) {
        // Get db access
        $table = $this->getTable();
        // Disactivate all the other publisher isbn ranges
        $table->disactivateAll($publisherId);
        // Store to db and return true/false
        return $table->saveToDb($range, $publisherId, $publisherIdentifier);
    }

    /**
     * Activates the given publisher identifier range that belong to the given
     * publisher.
     * @param integer $publisherId id of the publisher
     * @param integer $publisherRangeId id of the range
     * @return boolean true on success
     */
    public function activateRange($publisherId, $publisherRangeId) {
        // Get db access
        $table = $this->getTable();
        // Start transaction
        $table->transactionStart();
        // Disactivate all the other publisher isbn ranges
        $table->disactivateAll($publisherId);
        // Activate given range
        if (!$table->activateRange($publisherId, $publisherRangeId)) {
            $table->transactionRollback();
            return false;
        }
        // Get object 
        $publisherRange = $table->getPublisherRange($publisherRangeId, true);
        // Update publisher's active identifier range info
        if (!$this->updateActiveIdentifier($publisherRange->publisher_id, $publisherRange->publisher_identifier)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLISHER_ACTIVE_IDENTIFIER_RANGE_UPDATE_FAILED'));
            $table->transactionRollback();
            return false;
        }

        // Commit transaction
        $table->transactionCommit();

        return true;
    }

    /**
     * Deletes the given publisher range.
     * @param integer $publisherRangeId id of the range to be deleted
     * @return boolean true on success
     */
    public function deleteRange($publisherRangeId) {
        // Check if the given publisher isbn range can be deleted
        $publisherRange = $this->canBeDeleted($publisherRangeId);
        if ($publisherRange == null) {
            return false;
        }

        // Get db access
        $table = $this->getTable();
        // Start transaction
        $table->transactionStart();

        // Get an instance of a identifier range model
        $rangeModel = $this->getInstance($this->getRangeModelName(), 'IsbnregistryModel');
        // Check if other identifiers have been given from the same range 
        // since this one. If not, identifier range must be updated
        if ($rangeModel->canDeleteIdentifier($this->getRangeId($publisherRange), $publisherRange->publisher_identifier)) {
            // Update the identifier range accordingly
            if (!$rangeModel->decreaseByOne($this->getRangeId($publisherRange))) {
                $table->transactionRollback();
                return false;
            }
        } else {
            // Add removed identifier to canceled identifiers list that it
            // can be reused
            // Get an instance of canceled model
            $canceledModel = $this->getInstance('publisher' . $this->getRangeModelName() . 'canceled', 'IsbnregistryModel');
            // Create canceled object
            $publisherRangeCanceled = array();
            $publisherRangeCanceled['identifier'] = $publisherRange->publisher_identifier;
            $publisherRangeCanceled['category'] = ($this->getIdentifierVarTotalLength() - $publisherRange->category);
            $publisherRangeCanceled['range_id'] = $this->getRangeId($publisherRange);
            // Add new canceled entry
            if ($canceledModel->save($publisherRangeCanceled) == 0) {
                $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_IDENTIFIER_CANCELED_ENTRY_CREATION_FAILED'));
                $table->transactionRollback();
                return false;
            }
        }

        // Return false if deleting the object failed
        if (!$table->deleteRange($publisherRangeId)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLISHER_IDENTIFIER_RANGE_DELETE_FROM_DB_FAILED'));
            $table->transactionRollback();
            return false;
        }
        // Update publisher's active identifier range info
        if (!$this->updateActiveIdentifier($publisherRange->publisher_id, '')) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLISHER_ACTIVE_IDENTIFIER_RANGE_UPDATE_FAILED'));
            $table->transactionRollback();
            return false;
        }

        // Commit transaction
        $table->transactionCommit();

        // Return true on success
        return true;
    }

    /**
     * Checks if the range identified by the given id can be deleted.
     * @param integer $publisherRangeId publisher range id to be deleted
     * @return mixed object to be deleted on success; otherwise null
     */
    private function canBeDeleted($publisherRangeId) {
        // Get db access
        $table = $this->getTable();
        // Get object 
        $result = $table->getPublisherRange($publisherRangeId, true);

        // Check for null
        if ($result == null) {
            return null;
        }
        // If no identifiers have been given yet, the item can be deleted
        if (strcmp($result->range_begin, $result->next) == 0) {
            return $result;
        }
        $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLISHER_IDENTIFIER_RANGE_POINTER_NOT_ZERO'));
        // Otherwise the item can't be deleted
        return null;
    }

    /**
     * Returns an array of identifiers that are generated from the active
     * range of the given publisher. The number of identifiers that are generated
     * is defined by the count parameter.
     * @param int $publisherId id of the publisher to whom the identifiers
     * are generated
     * @param type $count number of identifiers to be generated
     * @param int $publicationId id of the publication if the identifiers
     * to be generated are for a particular publication. 0 by default.
     * @return array array of identifiers on success, empty array on failure
     */
    public function generateIdentifiers($publisherId, $count, $publicationId = 0) {
        // Get db access
        $table = $this->getTable();
        // Start transaction
        $table->transactionStart();

        // Array for results
        $resultsArray = array();
        $resultsArray['identifier_batch_id'] = 0;
        $resultsArray['identifiers'] = array();

        // Load publisher model
        $publisherModel = JModelLegacy::getInstance('publisher', 'IsbnregistryModel');
        // Get has quitted value
        $hasQuitted = $publisherModel->hasQuitted($publisherId);
        // Check the result - if publisher has quitted identifiers can not
        // be generated
        if ($hasQuitted) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLISHER_HAS_QUITTED'));
            $table->transactionRollback();
            return $resultsArray;
        }

        // Get object 
        $publisherRange = $table->getPublisherRangeByPublisherId($publisherId);

        // Check that we have a result
        if (!$publisherRange) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLISHER_IDENTIFIER_RANGE_PUBLISHER_HAS_NO_RANGE'));
            $table->transactionRollback();
            return $resultsArray;
        }

        // Set publisher range id
        $publisherRangeId = $publisherRange->id;
        // Get identifier type
        $identifierType = strtoupper(substr($this->getRangeModelName(), 0, 4));
        // Init publication model variable
        $publicationModel = null;
        // Init publication format variable
        $publicationFormat = '';
        // Init publication types array
        $publicationTypes = array();

        // Check if publication id is defined
        if ($publicationId != 0) {
            // Load publication model
            $publicationModel = JModelLegacy::getInstance('publication', 'IsbnregistryModel');
            // Get publication format
            $publication = $publicationModel->getItem($publicationId);
            // Check that the format has been set
            if (!$publication) {
                $this->setError(JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . $identifierType . '_NUMBER_FAILED_NO_PUBLICATION_FOUND'));
                $table->transactionRollback();
                return $resultsArray;
            } else if (empty($publication->publication_format)) {
                $this->setError(JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . $identifierType . '_NUMBER_FAILED_NO_FORMAT'));
                $table->transactionRollback();
                return $resultsArray;
            }
            // Check that "no_identifier_granted" is false
            if ($publication->no_identifier_granted) {
                $this->setError(JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . $identifierType . '_NUMBER_FAILED_NO_IDENTIFIER_GRANTED'));
                $table->transactionRollback();
                return $resultsArray;
            }
            // Set publication format
            $publicationFormat = $publication->publication_format;
            // Get print types
            $printTypes = $this->fromStrToArray($publication->type);
            // Get electronical types
            $electronicalTypes = $this->fromStrToArray($publication->fileformat);
            // Check publication format and type
            if (strcmp($publicationFormat, 'PRINT') == 0) {
                if (sizeof($printTypes) == 0 || sizeof($electronicalTypes) > 0) {
                    $this->setError(JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . $identifierType . '_NUMBER_INVALID_TYPE_FORMAT'));
                    $table->transactionRollback();
                    return $resultsArray;
                }
            } else if (strcmp($publicationFormat, 'ELECTRONICAL') == 0) {
                if (sizeof($printTypes) > 0 || sizeof($electronicalTypes) == 0) {
                    $this->setError(JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . $identifierType . '_NUMBER_INVALID_TYPE_FORMAT'));
                    $table->transactionRollback();
                    return $resultsArray;
                }
            } else if (strcmp($publicationFormat, 'PRINT_ELECTRONICAL') == 0) {
                if (sizeof($printTypes) == 0 || sizeof($electronicalTypes) == 0) {
                    $this->setError(JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . $identifierType . '_NUMBER_INVALID_TYPE_FORMAT'));
                    $table->transactionRollback();
                    return $resultsArray;
                }
            }
            // Merge publication type and fileformat arrays
            $publicationTypes = array_merge($printTypes, $electronicalTypes);
            // Calculate identifier count
            $count = sizeof($publicationTypes);
            // Check that count is not zero
            if ($count == 0) {
                $this->setError(JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . $identifierType . '_NUMBER_FAILED_NO_TYPE'));
                $table->transactionRollback();
                return $resultsArray;
            }
        }

        // Load identifier canceled model
        $identifierCanceledModel = JModelLegacy::getInstance('identifiercanceled', 'IsbnregistryModel');
        // Get canceled identifiers
        $canceledIdentifiers = $identifierCanceledModel->getIdentifiers($publisherRange->category, $publisherId, $identifierType, $count, $publisherRange->id);
        // Get canceled identifiers count
        $canceledIdentifiersCount = sizeof($canceledIdentifiers);
        // Update count
        $count -= $canceledIdentifiersCount;
        // Identifier objects array
        $identifierObjects = array();

        // Check if more identifiers are needed
        if ($count > 0) {

            // Check there are enough free numbers
            if ($publisherRange->free < $count) {
                $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLISHER_IDENTIFIER_RANGE_NOT_ENOUGH_FREE_IDENTIFIERS'));
                $table->transactionRollback();
                // If not enough free numbers, return an empty array
                return $resultsArray;
            }

            // Get the next available number
            $nextPointer = (int) $publisherRange->next;
            // Init counter
            $i = 0;
            // Generate identifiers
            for ($x = $nextPointer; $x < $nextPointer + $count; $x++) {
                // Add padding to the publication code
                $temp = str_pad($x, $publisherRange->category, "0", STR_PAD_LEFT);
                // Remove dashes
                $identifier = str_replace('-', '', $publisherRange->publisher_identifier . $temp);
                // Calculate check digit
                $checkDigit = $this->getCheckDigit($identifier);
                // Format full identifier
                $identifier = $publisherRange->publisher_identifier . '-' . $temp . '-' . $checkDigit;
                // Publication type
                $publicationType = (!empty($publicationTypes) ? $publicationTypes[$i] : '');
                // Add identifier to results array
                $resultsArray['identifiers'][$identifier] = $publicationType;
                // Identifier object
                $identifierObject = array(
                    'identifier' => $identifier,
                    'publisher_identifier_range_id' => $publisherRangeId,
                    'publication_type' => $publicationType
                );
                // Add to objects array
                array_push($identifierObjects, $identifierObject);
                // Increase counter
                $i++;
            }
            // Increase the pointer
            $publisherRange->next += $count;
            // Increase taken
            $publisherRange->taken += $count;
            // Decreseace free
            $publisherRange->free -= $count;
            // Next pointer is a string, add left padding
            $publisherRange->next = str_pad($publisherRange->next, $publisherRange->category, "0", STR_PAD_LEFT);

            // Are there any free numbers left?
            if ($publisherRange->free == 0) {
                // If all the numbers are used, closed and disactivate
                $publisherRange->is_active = false;
                $publisherRange->is_closed = true;
            }

            // Update changed publisher isbn range to the database
            if (!$table->updateIncrease($publisherRange, $count)) {
                $table->transactionRollback();
                // If update failed, return an empty array
                return array();
            }
        } else {
            // Set publisher range id to zero, because it wasn't used
            $publisherRangeId = 0;
        }

        // Cache for publisher ranges
        $rangeCache = array();
        // Add publisher range to cache
        $rangeCache[$publisherRange->id] = $publisherRange;

        // Add canceled identifiers to results
        for ($i = $count, $j = 0; $i < $count + $canceledIdentifiersCount; $i++, $j++) {
            // Publication type
            $publicationType = (!empty($publicationTypes) ? $publicationTypes[$i] : '');
            // Add identifier to results array
            $resultsArray['identifiers'][$canceledIdentifiers[$j]->identifier] = $publicationType;
            // Identifier object
            $identifierObject = array(
                'identifier' => $canceledIdentifiers[$j]->identifier,
                'publisher_identifier_range_id' => $canceledIdentifiers[$j]->publisher_identifier_range_id,
                'publication_type' => $publicationType
            );
            // Add to objects array
            array_push($identifierObjects, $identifierObject);

            // Check if publisher range is in cache
            if (!array_key_exists($canceledIdentifiers[$j]->publisher_identifier_range_id, $rangeCache)) {
                $rangeCache[$canceledIdentifiers[$j]->publisher_identifier_range_id] = $this->getItem($canceledIdentifiers[$j]->publisher_identifier_range_id);
            }
            // Increase publisher identifier range canceled counter
            $rangeCache[$canceledIdentifiers[$j]->publisher_identifier_range_id]->canceled -= 1;
            // Update to database
            if (!$table->decreaseCanceled($rangeCache[$canceledIdentifiers[$j]->publisher_identifier_range_id], 1)) {
                $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_DELETE_IDENTIFIER_UPDATE_IDENTIFIER_RANGE_FAILED'));
                $table->transactionRollback();
                return array();
            }
        }

        // Parameters array
        $params = array(
            'identifier_type' => $identifierType,
            'identifier_count' => $count,
            'publisher_id' => $publisherId,
            'publication_id' => $publicationId,
            'identifier_canceled_used_count' => $canceledIdentifiersCount,
            'publisher_identifier_range_id' => $publisherRangeId
        );
        // Get an instance of identifier batch model
        $identifierBatchModel = $this->getInstance('Identifierbatch', 'IsbnregistryModel');
        // Add identifier batch
        $batchId = $identifierBatchModel->addNew($params);
        // Check that creating new batch succeeded
        if ($batchId == 0) {
            $table->transactionRollback();
            return array();
        }
        // Add batch id to results array
        $resultsArray['identifier_batch_id'] = $batchId;
        // Get an instance of identifier model
        $identifierModel = $this->getInstance('Identifier', 'IsbnregistryModel');
        // Add new identifiers to db
        if (!$identifierModel->addNew($identifierObjects, $batchId)) {
            // If adding new identifiers to DB failed, rollback
            $table->transactionRollback();
            // Return an empty array
            return array();
        }
        // Delete canceled identifiers, if they exist
        if ($canceledIdentifiersCount > 0) {
            $ids = array();
            // Put canceled identifier ids into an array
            foreach ($canceledIdentifiers as $canceledIdentifier) {
                array_push($ids, $canceledIdentifier->id);
            }
            // Delete all the canceled identifiers
            if (!$identifierCanceledModel->delete($ids)) {
                $this->setError(JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . $identifierType . '_NUMBER_FAILED_CANCELED_IDENTIFIERS'));
                $table->transactionRollback();
                return array();
            }
        }
        // If publication id has been defined, update the publication
        if ($publicationId != 0) {
            // Update publication record
            if (!$publicationModel->updateIdentifiers($publicationId, $publisherId, $resultsArray['identifiers'], $identifierType, $publicationFormat)) {
                if ($publicationModel->getError()) {
                    $this->setError($publicationModel->getError());
                }
                $table->transactionRollback();
                return array();
            }
        }

        // Add translations for types
        foreach ($resultsArray['identifiers'] as $identifier => $type) {
            $resultsArray['identifiers'][$identifier] = (!empty($type) ? JText::_('COM_ISBNREGISTRY_PUBLICATION_JSON_TYPE_' . $type) : '');
        }

        // Commit transaction
        $table->transactionCommit();

        // If update was succesfull, return the generated ISBN numbers
        return $resultsArray;
    }

    /**
     * Returns a list of identifier ranges belonging to the publisher
     * identified by the given id.
     * @param int $publisherId id of the publisher who owns the identifiers
     * @return array list of identifiers
     */
    public function getPublisherIdentifiers($publisherId) {
        // Get db access
        $table = $this->getTable();
        // Get results 
        return $table->getPublisherIdentifiers($publisherId);
    }

    /**
     * Returns active identifier range belonging to the publisher
     * identified by the given id. 
     * @param int $publisherId id of the publisher who owns the range
     * @return object active identifier ranage
     */
    public function getActivePublisherIdentifierRange($publisherId) {
        // Get db access
        $table = $this->getTable();
        // Get results 
        return $table->getPublisherRangeByPublisherId($publisherId);
    }

    /**
     * Updates the range matching the given identifier id and decreases its
     * next pointer by the given number. Also free and taken properties are updated 
     * accordingly.
     * @param integer $publisherRangeId id of the range to be updated
     * @param integer $count how must counters are increased/decreased
     * @return boolean true if and only if the operation succeeds; otherwise
     * false
     */
    public function decreaseByCount($publisherRangeId, $count) {
        // Get db access
        $table = $this->getTable();
        // Get publisher range object
        $publisherRange = $table->getPublisherRange($publisherRangeId, false);

        // Check that we have a result
        if ($publisherRange != null) {
            // Decrease next pointer
            $publisherRange->next = $publisherRange->next - $count;
            // Next pointer is a string, add left padding
            $publisherRange->next = str_pad($publisherRange->next, $publisherRange->category, "0", STR_PAD_LEFT);
            // Update free
            $publisherRange->free += $count;
            // Update taken
            $publisherRange->taken -= $count;
            // Check if closed
            if ($publisherRange->is_closed) {
                // Update is_closed and is_active
                $publisherRange->is_closed = false;
                $publisherRange->is_active = true;
            }
            // Update to db
            if ($table->updateDecrease($publisherRange, $count)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Delete all publisher identifier ranges related to the publisher 
     * identified by the given publisher id.
     * @param int $publisherId publisher id
     * @return int number of deleted rows
     */
    public function deleteByPublisherId($publisherId) {
        // Get db access
        $table = $this->getTable();
        // Get results 
        return $table->deleteByPublisherId($publisherId);
    }

    /**
     * Returns the publisher range identified by the given id.
     * @param id $rangeId id of the publisher range
     * @return Object publisher range
     */
    public function getRange($rangeId) {
        // Get db access
        $table = $this->getTable();
        // Get results 
        return $table->getPublisherRange($rangeId, false);
    }

    /**
     * Updates the canceled value of the given publisher identifier range to 
     * the database. This  method must be used when the number of canceled 
     * identifiers is being increased.
     * @param Object $publisherRange object to be updated
     * @param int $count how much value is increased
     * @return boolean true on success
     */
    public function increaseCanceled($publisherRange, $count) {
        // Get db access
        $table = $this->getTable();
        // Get results 
        return $table->increaseCanceled($publisherRange, $count);
    }

    private function fromStrToArray($source) {
        $result = array();
        if (empty($source)) {
            return $result;
        }
        if ($source && !is_array($source)) {
            $result = explode(',', $source);
        }
        return $result;
    }

}
