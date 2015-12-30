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
 * Publisher ISMN Range Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelPublisherismnrange extends JModelAdmin {

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
    public function getTable($type = 'Publisherismnrange', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.publisherismnrange', 'publisherismnrange', array(
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
                'com_isbnregistry.edit.publisherismnrange.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Method to store a new publisher ismn range into database.
     * 
     * @param ismnrange $ismnrange ismn range object which subset the publisher ismn range is
     * @param int $publisherId id of the publisher that owns the ismn range
     * @param int $publisherIdentifier publisher identifier of the publisher that owns the range to be created
     * @return boolean returns true if and only if the object was successfully saved to the database; otherwise false
     */
    public static function saveToDb($ismnrange, $publisherId, $publisherIdentifier) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Get category
        $category = 8 - $ismnrange->category;
        // Create an object for the record we are going to create
        $object = new stdClass();

        // Set values
        $object->publisher_identifier = $publisherIdentifier;
        $object->publisher_id = $publisherId;
        $object->ismn_range_id = $ismnrange->id;
        $object->created_by = $user->get('username');
        $object->created = $date->toSql();
        $object->category = $category;
        $object->is_active = true;
        $object->is_closed = false;
        $object->range_begin = str_pad('', $category, '0', STR_PAD_LEFT);
        $object->range_end = str_pad('', $category, '9', STR_PAD_LEFT);
        $object->free = $object->range_end - $object->range_begin + 1;
        $object->next = $object->range_begin;

        // Disactivate all the other publisher ismn ranges
        self::disactivateAll($publisherId);

        // Add object to DB
        $ret = JFactory::getDbo()->insertObject('#__isbn_registry_publisher_ismn_range', $object);
		
		if (JFactory::getDbo()->getAffectedRows() == 0) {
			$this->setError($db->getErrorMsg());
			return false;
		}

        return true;
    }

	public static function activateIsmnRange($publisherId, $publisherIsmnRangeId) {
        // Disactivate all the other publisher ismn ranges
        self::disactivateAll($publisherId);
		
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();		
		
		// Database connection
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
		
        // Fields to update.
        $fields = array(
            $db->quoteName('is_active') . ' = ' . $db->quote(true),
            $db->quoteName('modified') . ' = ' . $db->quote($date->toSql()),
            $db->quoteName('modified_by') . ' = ' . $db->quote($user->get('username'))
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId),
			$db->quoteName('id') . ' = ' . $db->quote($publisherIsmnRangeId)
        );
		
        // Create query
        $query->update($db->quoteName('#__isbn_registry_publisher_ismn_range'))->set($fields)->where($conditions);
        $db->setQuery($query);
        // Execute query
        $result = $db->execute();
		
		// Return true or false
		if($db->getAffectedRows() == 0) {
			return false;
		}
        return true;
	}

    /**
     * Disactivates all the ismn ranges related to the publisher matching the
     * given publisher id.
     * 
     * @param int $publisherId id of the publisher which ismn ranges are disactived
     * @return int number of affected database rows
     */
    private static function disactivateAll($publisherId) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Fields to update.
        $fields = array(
            $db->quoteName('is_active') . ' = ' . $db->quote(false),
            $db->quoteName('modified') . ' = ' . $db->quote($date->toSql()),
            $db->quoteName('modified_by') . ' = ' . $db->quote($user->get('username'))
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId)
        );
        // Create query
        $query->update($db->quoteName('#__isbn_registry_publisher_ismn_range'))->set($fields)->where($conditions);
        $db->setQuery($query);
        // Execute query
        $result = $db->execute();
        return $db->getAffectedRows();
    }
	
	public static function deleteIsmnRange($publisherIsmnRangeId) {
        // Check if the given publisher ismn range can be deleted
		$publisherIsmnRange = self::canBeDeleted($publisherIsmnRangeId);
        if($publisherIsmnRange == null) {
			return false;
		}
		
		// Include ismnrange model
		require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/ismnrange.php';
		// Check that no other identifiers have been given from the same range since this one
		if(!IsbnregistryModelIsmnrange::canDeleteIdentifier($publisherIsmnRange->ismn_range_id, $publisherIsmnRange->publisher_identifier)) {
			return false;
		}			
		
		// Database connection
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Conditions for delete operation - delete by id
        $conditions = array(
			$db->quoteName('id') . ' = ' . $db->quote($publisherIsmnRangeId)
        );
		// Create query		
		$query->delete($db->quoteName('#__isbn_registry_publisher_ismn_range'));
		$query->where($conditions);
		$db->setQuery($query);
		// Execute query  
		$result = $db->execute();		
		
		// Return true or false
		if($db->getAffectedRows() == 0) {
			return false;
		}
		// Update the ISMN range accordingly
		IsbnregistryModelIsmnrange::decreaseByOne($publisherIsmnRange->ismn_range_id);

        return true;
	}

	private static function canBeDeleted($publisherIsmnRangeId) {
		// Database connection
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Conditions for which records should be updated.
        $conditions = array(
			$db->quoteName('id') . ' = ' . $db->quote($publisherIsmnRangeId)
        );
		// Create query		
		$query->select('*');
		$query->from($db->quoteName('#__isbn_registry_publisher_ismn_range'));
		$query->where($conditions);
		$db->setQuery($query);
		// Execute query  
		$result = $db->loadObject();

		// Check for null
		if($result == null) {
			return null;
		}
		// If no ISMNs have been given yet, the item can be deleted
		if(strcmp($result->range_begin, $result->next) == 0) {
			return $result;
		}
		// Otherwise the item can't be deleted
		return null;
	}

    public static function generateIsmnNumbers($publisherId, $ismnCount) {
        // Database connection
        $db = JFactory::getDBO();
        // Conditions for which records should be fetched
        $conditions = array(
            $db->quoteName('publisher_id') . " = " . $db->quote($publisherId),
			$db->quoteName('is_active') . " = " . $db->quote(true),
			$db->quoteName('is_closed') . " = " . $db->quote(false)
        );
        // Database query
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__isbn_registry_publisher_ismn_range'));
        $query->where($conditions);
        $db->setQuery((string) $query);
        $publisherIsmnrange = $db->loadObject();

		// Array for results
		$resultsArray = array();
        // Check that we have a result
        if ($publisherIsmnrange) {
			// Check there are enough free numbers
			if($publisherIsmnrange->free < $ismnCount) {
				// If not enough free numbers, return an empty array
				return $resultsArray;
			}
			// Include helper class
			require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/helpers/publisherismnrange.php';
			 // Get the next available number
            $nextPointer = (int)$publisherIsmnrange->next;
			// Generate ISMNs
			for ($x = $nextPointer; $x < $nextPointer + $ismnCount; $x++) {
				// Add padding to the publication code
				$temp = str_pad($x, $publisherIsmnrange->category, "0", STR_PAD_LEFT);
				// Remove dashes
				$ismn = str_replace('-', '', $publisherIsmnrange->publisher_identifier . $temp);
				// Calculate check digit
				$checkDigit = PublishersismnrangeHelper::countIsmnCheckDigit($ismn);
				// Push ismn to results arrays
				array_push($resultsArray, $publisherIsmnrange->publisher_identifier . '-' . $temp . '-' . $checkDigit);
			}
			// Increase the pointer
			$publisherIsmnrange->next += $ismnCount;
			// Increase taken
			$publisherIsmnrange->taken += $ismnCount;
			// Decreseace free
			$publisherIsmnrange->free -= $ismnCount;
			// Next pointer is a string, add left padding
			$publisherIsmnrange->next = str_pad($publisherIsmnrange->next, $publisherIsmnrange->category, "0", STR_PAD_LEFT);

			// Are there any free numbers left?
			if($publisherIsmnrange->free == 0) {
				// If all the numbers are used, closed and disactivate
				$publisherIsmnrange->is_active = false;
				$publisherIsmnrange->is_closed = true;
			}
			
			// Update changed publisher ismn range to the database
			if(self::updateToDb($publisherIsmnrange) == 1) {
				// If update was succesfull, return the generated ISMN numbers
				return $resultsArray;
			} else {
				// If update failed, return an empty array
				return array();
			}
		}
		return $resultsArray;
	}	
	
    /**
     * Updates the given publisher ismn range to the database.
     * @param publisherIsmnrange $publisherIsmnrange object to be updated
     * @return int number of affected rows; 1 means success, 0 means failure
     */
    private static function updateToDb($publisherIsmnrange) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Fields to update.
        $fields = array(
            $db->quoteName('free') . ' = ' . $db->quote($publisherIsmnrange->free),
            $db->quoteName('taken') . ' = ' . $db->quote($publisherIsmnrange->taken),
            $db->quoteName('next') . ' = ' . $db->quote($publisherIsmnrange->next),
            $db->quoteName('is_active') . ' = ' . $db->quote($publisherIsmnrange->is_active),
			$db->quoteName('is_closed') . ' = ' . $db->quote($publisherIsmnrange->is_closed),
            $db->quoteName('modified') . ' = ' . $db->quote($date->toSql()),
            $db->quoteName('modified_by') . ' = ' . $db->quote($user->get('username'))
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = ' . $db->quote($publisherIsmnrange->id)
        );
        // Create query
        $query->update($db->quoteName('#__isbn_registry_publisher_ismn_range'))->set($fields)->where($conditions);
        $db->setQuery($query);
        // Execute query
        $result = $db->execute();
		// Return the number of affected rows
        return $db->getAffectedRows();
    }	
}
