<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author              Petteri Kivimäki
 * @copyright   Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

abstract class AbstractIdentifierRange extends JModelAdmin {

    abstract public function formatPublisherIdentifier($identifierrange, $publisherIdentifier);
    
    abstract public function getPublisherRangeModelName();

    /**
     * Generates a new publisher identifier from the given isbn range and
     * assigns it to the given publisher.
     * @param int $rangeId isbn range id that used for generating the identifier
     * @param int $publisherId id of the publisher to which the identifier is assigned
     * @return mixed returns 0 if the operation fails; on success the generated
     * publisher identifier string is returned
     */
    public function getPublisherIdentifier($rangeId, $publisherId) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Get ISBN range object
        $range = $dao->getRange($rangeId, true);

        // Check that we have a result
        if ($range != null) {
            // Get the next available number
            $publisherIdentifier = $range->next;
            // Is this the last value of the range
            if ($range->next == $range->range_end) {
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
            if ($dao->updateRange($range)) {
                // Format publisher identifier
                $result = $this->formatPublisherIdentifier($range, $publisherIdentifier);
                // Get an instance of a ISBN range model
                $publisherRangeModel = $this->getInstance($this->getPublisherRangeModelName(), 'IsbnregistryModel');
                // Insert data into publisher isbn range table
                if ($publisherRangeModel->saveToDb($range, $publisherId, $result)) {
                    return $result;
                }
            }
        }
        return 0;
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
        $dao = $this->getTable();
        // Get ISBN range object
        $range = $dao->getRange($rangeId, false);

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
        $dao = $this->getTable();
        // Get ISBN range object
        $range = $dao->getRange($rangeId, false);

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
            if ($dao->updateRange($range)) {
                return true;
            }
        }
        return false;
    }

}

?>
