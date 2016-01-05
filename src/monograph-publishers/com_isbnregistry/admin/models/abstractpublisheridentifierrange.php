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
        // Get DAO for db access
        $dao = $this->getTable();
        // Disactivate all the other publisher isbn ranges
        $dao->disactivateAll($publisherId);
        // Store to db and return true/false
        return $dao->saveToDb($range, $publisherId, $publisherIdentifier);
    }

    /**
     * Activates the given publisher identifier range that belong to the given
     * publisher.
     * @param integer $publisherId id of the publisher
     * @param integer $publisherRangeId id of the range
     * @return boolean true on success
     */
    public function activateRange($publisherId, $publisherRangeId) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Disactivate all the other publisher isbn ranges
        $dao->disactivateAll($publisherId);
        // Activate given range
        return $dao->activateRange($publisherId, $publisherRangeId);
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

        // Get an instance of a identifier range model
        $rangeModel = $this->getInstance($this->getRangeModelName(), 'IsbnregistryModel');
        // Check that no other identifiers have been given from the same range 
        // since this one
        if (!$rangeModel->canDeleteIdentifier($this->getRangeId($publisherRange), $publisherRange->publisher_identifier)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLISHER_IDENTIFIER_RANGE_DELETE_FAILED_NOT_LATEST'));
            return false;
        }

        // Get DAO for db access
        $dao = $this->getTable();
        // Return false if deleting the object failed
        if (!$dao->deleteRange($publisherRangeId)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLISHER_IDENTIFIER_RANGE_DELETE_FROM_DB_FAILED'));
            return false;
        }
        // Update the ISBN range accordingly
        $rangeModel->decreaseByOne($this->getRangeId($publisherRange));
        // Return true on success
        return true;
    }

    /**
     * Checks if the range identified by the given id can be deleted.
     * @param integer $publisherRangeId publisher range id to be deleted
     * @return mixed object to be deleted on success; otherwise null
     */
    private function canBeDeleted($publisherRangeId) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Get object 
        $result = $dao->getPublisherRange($publisherRangeId, true);

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
     * @param type $publisherId id of the publisher to whom the identifiers
     * are generated
     * @param type $count number of identifiers to be generated
     * @return array array of identifiers on success, empty array on failure
     */
    public function generateIdentifiers($publisherId, $count) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Get object 
        $publisherRange = $dao->getPublisherRangeByPublisherId($publisherId);

        // Array for results
        $resultsArray = array();
        // Check that we have a result
        if ($publisherRange) {
            // Check there are enough free numbers
            if ($publisherRange->free < $count) {
                $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLISHER_IDENTIFIER_RANGE_NOT_ENOUGH_FREE_IDENTIFIERS'));
                // If not enough free numbers, return an empty array
                return $resultsArray;
            }
            // Get the next available number
            $nextPointer = (int) $publisherRange->next;
            // Generate identifiers
            for ($x = $nextPointer; $x < $nextPointer + $count; $x++) {
                // Add padding to the publication code
                $temp = str_pad($x, $publisherRange->category, "0", STR_PAD_LEFT);
                // Remove dashes
                $identifier = str_replace('-', '', $publisherRange->publisher_identifier . $temp);
                // Calculate check digit
                $checkDigit = $this->getCheckDigit($identifier);
                // Push isbn to results arrays
                array_push($resultsArray, $publisherRange->publisher_identifier . '-' . $temp . '-' . $checkDigit);
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
            if ($dao->updateToDb($publisherRange)) {
                // If update was succesfull, return the generated ISBN numbers
                return $resultsArray;
            } else {
                // If update failed, return an empty array
                return array();
            }
        }
        $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLISHER_IDENTIFIER_RANGE_PUBLISHER_HAS_NO_RANGE'));
        return $resultsArray;
    }

    /**
     * Returns a list of identifier ranges belonging to the publisher
     * identified by the given id.
     * @param integer $publisherId id of the publisher who owns the identifiers
     * @return array list of identifiers
     */
    public function getPublisherIdentifiers($publisherId) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Get results 
        return $dao->getPublisherIdentifiers($publisherId);
    }

}
