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
 * Publisher ISBN Range Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelPublisherisbnrange extends JModelAdmin {

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
    public function getTable($type = 'Publisherisbnrange', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.publisherisbnrange', 'publisherisbnrange', array(
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
                'com_isbnregistry.edit.publisherisbnrange.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Method to store a new publisher isbn range into database. All the other
     * ranges are disactivated and the new range is set active.
     * 
     * @param isbnrange $isbnrange isbn range object which subset the publisher 
     * isbn range is
     * @param int $publisherId id of the publisher that owns the isbn range
     * @param int $publisherIdentifier publisher identifier of the publisher 
     * that owns the range to be created
     * @return boolean returns true if and only if the object was successfully 
     * saved to the database; otherwise false
     */
    public function saveToDb($isbnrange, $publisherId, $publisherIdentifier) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Disactivate all the other publisher isbn ranges
        $dao->disactivateAll($publisherId);
        // Store to db and return true/false
        return $dao->saveToDb($isbnrange, $publisherId, $publisherIdentifier);
    }

    /**
     * Activates the given publisher isbn range that belong to the given
     * publisher.
     * @param integer $publisherId id of the publisher
     * @param integer $publisherIsbnRangeId id of the range
     * @return boolean true on success
     */
    public function activateIsbnRange($publisherId, $publisherIsbnRangeId) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Disactivate all the other publisher isbn ranges
        $dao->disactivateAll($publisherId);
        // Activate given range
        return $dao->activateIsbnRange($publisherId, $publisherIsbnRangeId);
    }

    /**
     * Deletes the given publisher range.
     * @param integer $publisherIsbnRangeId id of the range to be deleted
     * @return boolean true on success
     */
    public function deleteIsbnRange($publisherIsbnRangeId) {
        // Check if the given publisher isbn range can be deleted
        $publisherIsbnRange = $this->canBeDeleted($publisherIsbnRangeId);
        if ($publisherIsbnRange == null) {
            return false;
        }

        // Get an instance of a ISBN range model
        $isbnRangeModel = $this->getInstance('isbnrange', 'IsbnregistryModel');
        // Check that no other identifiers have been given from the same range 
        // since this one
        if (!$isbnRangeModel->canDeleteIdentifier($publisherIsbnRange->isbn_range_id, $publisherIsbnRange->publisher_identifier)) {
            return false;
        }

        // Get DAO for db access
        $dao = $this->getTable();
        // Return false if deleting the object failed
        if (!$dao->deleteIsbnRange($publisherIsbnRangeId)) {
            return false;
        }
        // Update the ISBN range accordingly
        $isbnRangeModel->decreaseByOne($publisherIsbnRange->isbn_range_id);
        // Return true on success
        return true;
    }

    /**
     * Checks if the range identified by the given id can be deleted.
     * @param integer $publisherIsbnRangeId publisher range id to be deleted
     * @return mixed object to be deleted on success; otherwise null
     */
    private function canBeDeleted($publisherIsbnRangeId) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Get object 
        $result = $dao->getPublisherRange($publisherIsbnRangeId, true);

        // Check for null
        if ($result == null) {
            return null;
        }
        // If no ISBNs have been given yet, the item can be deleted
        if (strcmp($result->range_begin, $result->next) == 0) {
            return $result;
        }
        // Otherwise the item can't be deleted
        return null;
    }

    /**
     * Returns an array of ISBN numbers that are generated from the active
     * range of the given publisher. The number of identifiers that are generated
     * is defined by the count parameter.
     * @param type $publisherId id of the publisher to whom the identifiers
     * are generated
     * @param type $isbnCount number of identifiers to be generated
     * @return array array of identifiers on success, empty array on failure
     */
    public function generateIsbnNumbers($publisherId, $isbnCount) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Get object 
        $publisherIsbnrange = $dao->getPublisherRangeByPublisherId($publisherId);

        // Array for results
        $resultsArray = array();
        // Check that we have a result
        if ($publisherIsbnrange) {
            // Check there are enough free numbers
            if ($publisherIsbnrange->free < $isbnCount) {
                // If not enough free numbers, return an empty array
                return $resultsArray;
            }
            // Include helper class
            require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/helpers/publisherisbnrange.php';
            // Get the next available number
            $nextPointer = (int) $publisherIsbnrange->next;
            // Generate ISBNs
            for ($x = $nextPointer; $x < $nextPointer + $isbnCount; $x++) {
                // Add padding to the publication code
                $temp = str_pad($x, $publisherIsbnrange->category, "0", STR_PAD_LEFT);
                // Remove dashes
                $isbn = str_replace('-', '', $publisherIsbnrange->publisher_identifier . $temp);
                // Calculate check digit
                $checkDigit = PublishersisbnrangeHelper::countIsbnCheckDigit($isbn);
                // Push isbn to results arrays
                array_push($resultsArray, $publisherIsbnrange->publisher_identifier . '-' . $temp . '-' . $checkDigit);
            }
            // Increase the pointer
            $publisherIsbnrange->next += $isbnCount;
            // Increase taken
            $publisherIsbnrange->taken += $isbnCount;
            // Decreseace free
            $publisherIsbnrange->free -= $isbnCount;
            // Next pointer is a string, add left padding
            $publisherIsbnrange->next = str_pad($publisherIsbnrange->next, $publisherIsbnrange->category, "0", STR_PAD_LEFT);

            // Are there any free numbers left?
            if ($publisherIsbnrange->free == 0) {
                // If all the numbers are used, closed and disactivate
                $publisherIsbnrange->is_active = false;
                $publisherIsbnrange->is_closed = true;
            }

            // Update changed publisher isbn range to the database
            if ($dao->updateToDb($publisherIsbnrange)) {
                // If update was succesfull, return the generated ISBN numbers
                return $resultsArray;
            } else {
                // If update failed, return an empty array
                return array();
            }
        }
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
