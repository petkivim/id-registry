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
 * Publication Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelPublication extends JModelAdmin {

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
    public function getTable($type = 'Publication', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.publication', 'publication', array(
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
                'com_isbnregistry.edit.publication.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        // From comma separated string to array
		$data->role_1 = IsbnregistryModelPublication::fromStrToArray($data->role_1);
		$data->role_2 = IsbnregistryModelPublication::fromStrToArray($data->role_2);
		$data->role_3 = IsbnregistryModelPublication::fromStrToArray($data->role_3);
		$data->role_4 = IsbnregistryModelPublication::fromStrToArray($data->role_4);
		$data->type = IsbnregistryModelPublication::fromStrToArray($data->type);
		$data->fileformat = IsbnregistryModelPublication::fromStrToArray($data->fileformat);
		
        return $data;
    }

	/**
	 * Converts the given comma separated string to array.
	 */
	private static function fromStrToArray($source) {
        if ($source && !is_array($source)) {
            $source = explode(',', $source);
        }		
		return $source;
	}
	
	public static function getPublicationsWithoutIdentifier($publisherId, $type) {
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		
        // Conditions for which records should be fetched
        $conditions = array(
            $db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId),
			$db->quoteName('publication_identifier') . ' = ' . $db->quote('')
        );
		
		// Add conditions related to publication type
		if (strcasecmp($type, 'isbn') == 0) {
			array_push($conditions, $db->quoteName('publication_type') . ' != ' . $db->quote('SHEET_MUSIC'));
		} else if (strcasecmp($type, 'ismn') == 0) {			
			array_push($conditions, $db->quoteName('publication_type') . ' = ' . $db->quote('SHEET_MUSIC'));
		}
		
		// Create the query
		$query->select('id, title')
			  ->from($db->quoteName('#__isbn_registry_publication'))
			  ->where($conditions)
			  ->order('title ASC');
        $db->setQuery($query);
        // Execute query
		$result = $db->loadObjectList();
        return $result;
	}	
	
	public static function updateIdentifier($publicationId, $publisherId, $identifier, $identifierType) {
		// Check that identifier type is valid
		if(!self::isValidIdentifierType($identifierType)) {
			return false;
		}
		// Check that publication does not have an identifier yet
		if(self::hasIdentifier($publicationId)) {
			return false;
		}
		
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Fields to update.
        $fields = array(
            $db->quoteName('publication_identifier') . ' = ' . $db->quote($identifier),
            $db->quoteName('publication_identifier_type') . ' = ' . $db->quote($identifierType)
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = ' . $db->quote($publicationId),
			$db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId)
        );
		
        // Create query
        $query->update($db->quoteName('#__isbn_registry_publication'))->set($fields)->where($conditions);
        $db->setQuery($query);
        // Execute query
        $result = $db->execute();
		// Operation succeeded if affected rows returns 1
        if($db->getAffectedRows() == 1) {
			return true;
		}		
		// Otherwise operation failed
		return false;
	}
	
	private static function isValidIdentifierType($type) {
		return preg_match('/^(ISBN|ISMN)$/', $type);
	}
	
	private static function hasIdentifier($publicationId) {
        // Database connection
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
		// Create query
		$query->select('publication_identifier');
		$query->from($db->quoteName('#__isbn_registry_publication'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($publicationId));
		 
		$db->setQuery($query);
		$publicationIdentifier = $db->loadResult();	
		// If publication_identifier column length is 0, 
		// publication does not have an identifier yet
		if(strlen($publicationIdentifier) == 0) {
			return false;
		}
		return true;
	}
}
