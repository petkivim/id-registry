<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author              Petteri Kivim�ki
 * @copyright   Copyright (C) 2015 Petteri Kivim�ki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

abstract class IsbnregistryModelAbstractIdentifierRange extends JModelAdmin {

    abstract public function formatPublisherIdentifier($identifierrange, $publisherIdentifier);

    abstract public function getPublisherRangeModelName();

    abstract public function updateActiveIdentifier($publisherId, $identifier);

    /**
     * Generates a new publisher identifier from the given identifier range and
     * assigns it to the given publisher.
     * @param int $rangeId identifier range id that used for generating the identifier
     * @param int $publisherId id of the publisher to which the identifier is assigned
     * @return mixed returns 0 if the operation fails; on success the generated
     * publisher identifier string is returned
     */
    public function getPublisherIdentifier($rangeId, $publisherId) {
        // Get DAO for db access
        $table = $this->getTable();
        // Start transaction
        $table->transactionStart();
        // Get ISBN range object
        $range = $table->getRange($rangeId, true);

        // Check that we have a result
        if ($range == null) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_IDENTIFIER_RANGE_NOT_FOUND'));
            $table->transactionRollback();
            return 0;
        }

        // Get an instance of identifier canceled range model
        $publisherRangeCanceledModel = $this->getInstance($this->getPublisherRangeModelName() . 'canceled', 'IsbnregistryModel');
        // Get canceled identifier
        $canceledIdentifier = $publisherRangeCanceledModel->getIdentifier($range->category, $range->id);
        // Check if a canceled identifier was found
        if ($canceledIdentifier != null) {
            // Try to delete the canceled identifier from the db
            if (!$publisherRangeCanceledModel->deleteIdentifier($canceledIdentifier->range_id, $canceledIdentifier->identifier)) {
                $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_CANCELED_IDENTIFIER_DELETE_FAILED'));
                $table->transactionRollback();
                return 0;
            }
            // Update range object if necessary
            if ($canceledIdentifier->range_id != $rangeId) {
                $range = $table->getRange($canceledIdentifier->range_id, false);
            }
            // Decreased canceled counter
            $range->canceled -= 1;
            // Are there any more identifiers left in this range?
            if ($range->free == 0 && $range->canceled == 0) {
                // This is the last value -> range becames inactive
                $range->is_active = false;
                // Range becomes closed
                $range->is_closed = true;
            }
            // Update range object's canceled counter to db
            if (!$table->decreaseCanceled($range, 1)) {
                $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_UPDATE_IDENTIFIER_CANCELED_COUNTER_DAILED'));
                $table->transactionRollback();
                return 0;
            }
            // Set result
            $result = $canceledIdentifier->identifier;
        } else {
            // Get the next available number
            $publisherIdentifier = $range->next;
            // Is this the last value of the range
            if ($range->next == $range->range_end && $range->canceled == 0) {
                // This is the last value -> range becames inactive
                $range->is_active = false;
                // Range becomes closed
                $range->is_closed = true;
            }
            // Increase next pointer
            $range->next = $range->next + 1;
            // Next pointer is a string, add left padding
            $range->next = str_pad($range->next, $range->category, "0", STR_PAD_LEFT);
            // Decrease free numbers pointer 
            $range->free -= 1;
            // Increase used numbers pointer
            $range->taken += 1;

            // Update new values to database
            if (!$table->updateIncrease($range)) {
                $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_UPDATE_IDENTIFIER_RANGE_FAILED'));
                $table->transactionRollback();
                return 0;
            }
            // Format publisher identifier
            $result = $this->formatPublisherIdentifier($range, $publisherIdentifier);
        }
        // Get an instance of a ISBN range model
        $publisherRangeModel = $this->getInstance($this->getPublisherRangeModelName(), 'IsbnregistryModel');
        // Insert data into publisher isbn range table
        if (!$publisherRangeModel->saveToDb($range, $publisherId, $result)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_UPDATE_PUBLISHER_IDENTIFIER_RANGE_FAILED'));
            $table->transactionRollback();
            return 0;
        }

        // Update publisher's active identifier range info
        if (!$this->updateActiveIdentifier($publisherId, $result)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLISHER_ACTIVE_IDENTIFIER_RANGE_UPDATE_FAILED'));
            $table->transactionRollback();
            return 0;
        }
        // Commit transaction
        $table->transactionCommit();

        return $result;
    }

    /**
     * Checks if the given identifier can be deleted. The identifier can be
     * deleted if and only if it is the last identifier that was generated
     * from its range. 
     * @param integer $rangeId id of the range in which the identifier belongs
     * @param string $identifier identifier to be deleted
     * @return boolean true if and only if the identifier can be deleted; 
     * otherwise false
     */
    public function canDeleteIdentifier($rangeId, $identifier) {
        // Get DAO for db access
        $table = $this->getTable();
        // Get ISBN range object
        $range = $table->getRange($rangeId, false);

        // Check that we have a result
        if ($range != null) {
            // Get the next available number
            $nextPointer = $range->next;
            // Decrease next pointer
            $nextPointer = $nextPointer - 1;
            // Next pointer is a string, add left padding
            $nextPointer = str_pad($nextPointer, $range->category, "0", STR_PAD_LEFT);
            // Format publisher identifier
            $result = $this->formatPublisherIdentifier($range, $nextPointer);
            // Compare result to the given identifier
            if (strcmp($result, $identifier) == 0) {
                // If they match, we can delete the given identifier and decrease next pointer by one
                return true;
            }
        }
        return false;
    }

    /**
     * Updates the range matching the given identifier id and decreases its
     * next pointer by one. Also free and taken properties are updated 
     * accordingly.
     * @param integer $rangeId id of the range to be updated
     * @return boolean true if and only if the operation succeeds; otherwise
     * false
     */
    public function decreaseByOne($rangeId) {
        // Get DAO for db access
        $table = $this->getTable();
        // Get ISBN range object
        $range = $table->getRange($rangeId, false);

        // Check that we have a result
        if ($range != null) {
            // Decrease next pointer
            $range->next = $range->next - 1;
            // Next pointer is a string, add left padding
            $range->next = str_pad($range->next, $range->category, "0", STR_PAD_LEFT);
            // Update free
            $range->free += 1;
            // Update taken
            $range->taken -= 1;
            // Check if closed
            if ($range->is_closed) {
                // Update is_closed and is_active
                $range->is_closed = false;
                $range->is_active = true;
            }
            // Update to db
            if ($table->updateDecrease($range)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Updates the canceled value of the given identifier range to 
     * the database. This  method must be used when the number of canceled 
     * identifiers is being increased.
     * @param Object $range object to be updated
     * @param int $count how much value is increased
     * @return boolean true on success
     */
    public function increaseCanceled($range, $count) {
        // Get db access
        $table = $this->getTable();
        // Get results 
        return $table->increaseCanceled($range, $count);
    }

    /**
     * Returns the number of created identifiers between the given timeframe.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @return ObjectList number of created identifiers grouped by year and
     * month
     */
    public function getCreatedIdentifierCountByDates($begin, $end) {
        // Get db access
        $table = $this->getTable();
        // Get results 
        return $table->getCreatedIdentifierCountByDates($begin, $end);
    }

    /**
     * Return a list of all the ISMN ranges in the database.
     * @return ObjectList list of all the ISMN ranges in the database
     */
    public function getRanges() {
        // Get db access
        $table = $this->getTable();
        // Get results 
        return $table->getRanges();
    }

}

?>
