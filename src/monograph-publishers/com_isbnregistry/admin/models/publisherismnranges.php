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
 * Publisher ISMN Ranges Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelPublisherismnranges extends JModelList {

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('*')
			  ->from($db->quoteName('#__isbn_registry_publisher_ismn_range'))
			  ->order('publisher_identifier ASC, range_begin ASC');

		return $query;
	}
	
	public static function getPublisherIdentifiers($publisherId) {
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		
        // Conditions for which records should be fetched
        $conditions = array(
            $db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId)
        );
		
		// Create the base select statement.
		$query->select('*')
			  ->from($db->quoteName('#__isbn_registry_publisher_ismn_range'))
			  ->where($conditions)
			  ->order('is_active DESC, publisher_identifier ASC, range_begin ASC');
        $db->setQuery($query);
        // Execute query
        //$result = $db->execute();
		$result = $db->loadObjectList();
        return $result;
	}
	
	public static function deleteIsmnRanges($publisherId) {
		$db = JFactory::getDbo();		 
		$query = $db->getQuery(true);
		 
		// Conditions
		$conditions = array(
			$db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId)
		);
		 
		$query->delete($db->quoteName('#__isbn_registry_publisher_ismn_range'));
		$query->where($conditions);
		 
		$db->setQuery($query);
		 
		$result = $db->execute();		
		
		return $result;
	}
}
