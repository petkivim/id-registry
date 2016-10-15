<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Publication Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTablePublication extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_publication', 'id', $db);
    }

    /**
     * Stores a publication.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   1.6
     */
    public function store($updateNulls = false) {
        // Transform the params field
        if (isset($this->params) && is_array($this->params)) {
            $registry = new Registry;
            $registry->loadArray($this->params);
            $this->params = (string) $registry;
        }

        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        if ($this->id) {
            // Is this the first modification?
            if (empty($this->modified_by)) {
                // Set on_process to true
                $this->on_process = true;
            }
            // Is no_identifier_granted set to true
            if ($this->no_identifier_granted) {
                // Set on_process to false
                $this->on_process = false;
            }
            // Existing item
            $this->modified_by = $user->get('username');
            $this->modified = $date->toSql();
        } else {
            // New item
            $this->created_by = $user->get('username');
            $this->created = $date->toSql();
        }

        // From array to comma separated string
        $this->role_1 = $this->fromArrayToStr($this->role_1);
        $this->role_2 = $this->fromArrayToStr($this->role_2);
        $this->role_3 = $this->fromArrayToStr($this->role_3);
        $this->role_4 = $this->fromArrayToStr($this->role_4);
        $this->type = $this->fromArrayToStr($this->type);
        $this->fileformat = $this->fromArrayToStr($this->fileformat);

        return parent::store($updateNulls);
    }

    /**
     * Deletes a Publication.
     *
     * @param   integer  $pk  Primary key of the publication to be deleted.
     *
     * @return  boolean  True on success, false on failure.
     *
     */
    public function delete($pk = null) {
        // Item can be deleted only if it doesn't have identifier yet
        if (!empty($this->publication_identifier_print) || !empty($this->publication_identifier_electornical)) {
            // If identifier exists, raise a warning
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_PUBLICATIONS_DELETE_FAILED'), 'warning');
            // Return false as the item can't be deleted
            return false;
        }
        // Item can be deleted only if no_identifier_granted is true
        if (!$this->no_identifier_granted) {
            // no_identifier_granted is false, raise a warning
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_PUBLICATIONS_DELETE_FAILED_IDENTIFIER_GRANTED'), 'warning');
            // Return false as the item can't be deleted
            return false;
        }
        // No identifiers found, delete the item
        return parent::delete($pk);
    }

    /**
     * Converts the given array to comma separated string.
     */
    private function fromArrayToStr($source) {
        if (is_array($source)) {
            if (count($source) > 0) {
                $source = implode(',', $source);
            } else {
                $source = '';
            }
        } else {
            $source = '';
        }
        return $source;
    }

    /**
     * Returns a list of publications without an identifier belonging to the
     * publisher specified by the publisher id.
     * @param integer $publisherId id of the publisher that owns the publications
     * @param string $type publication type, can be "ISBN" or "ISMN"
     * @return object list of publications
     */
    public function getPublicationsWithoutIdentifier($publisherId, $type) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Conditions for which records should be fetched
        $conditions = array(
            $this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId),
            $this->_db->quoteName('no_identifier_granted') . ' = ' . $this->_db->quote(false),
            $this->_db->quoteName('publication_identifier_print') . ' = ' . $this->_db->quote(''),
            $this->_db->quoteName('publication_identifier_electronical') . ' = ' . $this->_db->quote('')
        );

        // Add conditions related to publication type
        if (strcasecmp($type, 'isbn') == 0) {
            array_push($conditions, $this->_db->quoteName('publication_type') . ' != ' . $this->_db->quote('SHEET_MUSIC'));
            array_push($conditions, $this->_db->quoteName('publication_type') . ' != ' . $this->_db->quote(''));
        } else if (strcasecmp($type, 'ismn') == 0) {
            array_push($conditions, $this->_db->quoteName('publication_type') . ' = ' . $this->_db->quote('SHEET_MUSIC'));
        }

        // Create the query
        $query->select('id, title')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($conditions)
                ->order('title ASC');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Updates publication identified by the given publication id. Only
     * publication identifier(s) and publication identifier type are updated.
     * @param integer $publicationId id of the publication to be updated
     * @param integer $publisherId id of the publisher that owns the publication
     * @param string $identifiersPrint identifiers of printed publication in JSON string
     * @param string $identifiersElectronical identifiers of electronical publication in JSON string
     * @param string $identifierType type of the identifier, "ISBN" or "ISMN"
     * @param string $publicationFormat publication format
     * @return boolean true on success
     */
    public function updateIdentifiers($publicationId, $publisherId, $identifiersPrint, $identifiersElectronical, $identifierType, $publicationFormat) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $query = $this->_db->getQuery(true);

        // Fields to update.
        $fields = array(
            $this->_db->quoteName('publication_identifier_type') . ' = ' . $this->_db->quote($identifierType),
            $this->_db->quoteName('on_process') . ' = ' . $this->_db->quote(false),
            $this->_db->quoteName('modified') . ' = ' . $this->_db->quote($date->toSql()),
            $this->_db->quoteName('modified_by') . ' = ' . $this->_db->quote($user->get('username'))
        );

        // Update identifier(s)
        if (strcmp($publicationFormat, 'PRINT') == 0) {
            array_push($fields, $this->_db->quoteName('publication_identifier_print') . ' = ' . $this->_db->quote($identifiersPrint));
        } else if (strcmp($publicationFormat, 'ELECTRONICAL') == 0) {
            array_push($fields, $this->_db->quoteName('publication_identifier_electronical') . ' = ' . $this->_db->quote($identifiersElectronical));
        } else if (strcmp($publicationFormat, 'PRINT_ELECTRONICAL') == 0) {
            array_push($fields, $this->_db->quoteName('publication_identifier_print') . ' = ' . $this->_db->quote($identifiersPrint));
            array_push($fields, $this->_db->quoteName('publication_identifier_electronical') . ' = ' . $this->_db->quote($identifiersElectronical));
        }

        // Conditions for which records should be updated.
        $conditions = array(
            $this->_db->quoteName('id') . ' = ' . $this->_db->quote($publicationId),
            $this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId)
        );

        // Create query
        $query->update($this->_db->quoteName($this->_tbl))->set($fields)->where($conditions);
        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // If number of affected rows is 1, the result is OK
        if ($this->_db->getAffectedRows() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Removes both of the identifiers and identifier type replacing them
     * with an empty string.
     * @param integer $publicationId id of the publication to be updated
     * @return boolean true on success, false on failure
     */
    public function removeIdentifiers($publicationId) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $query = $this->_db->getQuery(true);

        // Fields to update.
        $fields = array(
            $this->_db->quoteName('publication_identifier_print') . ' = ' . $this->_db->quote(''),
            $this->_db->quoteName('publication_identifier_electronical') . ' = ' . $this->_db->quote(''),
            $this->_db->quoteName('publication_identifier_type') . ' = ' . $this->_db->quote(''),
            $this->_db->quoteName('on_process') . ' = ' . $this->_db->quote(true),
            $this->_db->quoteName('modified') . ' = ' . $this->_db->quote($date->toSql()),
            $this->_db->quoteName('modified_by') . ' = ' . $this->_db->quote($user->get('username'))
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $this->_db->quoteName('id') . ' = ' . $this->_db->quote($publicationId)
        );

        // Create query
        $query->update($this->_db->quoteName($this->_tbl))->set($fields)->where($conditions);
        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // If number of affected rows is 1, the result is OK
        if ($this->_db->getAffectedRows() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Loads publication identifiers specified by the given id.
     * @param integer $publicationId id of the publication to be fetched
     * @return Publication publication object holding 
     * "publication_identifier_print" and "publication_identifier_electronical"
     * attributes
     */
    public function loadPublicationIdentifier($publicationId) {
        // Database connection
        $query = $this->_db->getQuery(true);
        // Create query
        $query->select('publication_identifier_print, publication_identifier_electronical');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($publicationId));
        $this->_db->setQuery($query);
        // Return result
        return $this->_db->loadObject();
    }

    /**
     * Returns a publication mathcing the given id.
     * @param int $publicationId id of the publication
     * @return Publication publication matching the given id
     */
    public function getPublicationById($publicationId) {
        // Database connection
        $query = $this->_db->getQuery(true);
        // Create query
        $query->select('*');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($publicationId));
        $this->_db->setQuery($query);
        // Return result
        return $this->_db->loadObject();
    }

    /**
     * Loads the publication format specified by the given id.
     * @param integer $publicationId id of the publication to be fetched
     * @return mixed publication format string or null
     */
    public function loadPublicationFormat($publicationId) {
        // Database connection
        $query = $this->_db->getQuery(true);
        // Create query
        $query->select('publication_format');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($publicationId));
        $this->_db->setQuery($query);
        // Return result
        return $this->_db->loadResult();
    }

    /**
     * Returns an Object List that contains all the publications in the
     * database.
     * @return ObjectList list of all the publications
     */
    public function getPublications() {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->order('title ASC');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Returns an array that contains all the publications own by the publisher
     * spesified by the publisher id that have at least one ISBN identifier.
     * If publisher id is not given, all the publications that have at least
     * one ISBN identifier are returned.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @param integer $publisherId id of the publisher that owns the
     * publications
     * @return array array of all the publications that have ISBN
     * identifier
     */
    public function getPublicationsWithIsbnIdentifiers($begin, $end, $publisherId = 0) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Conditions for which records should be fetched
        $conditions = array(
            $this->_db->quoteName('p.publication_identifier_type') . ' = ' . $this->_db->quote('ISBN')
        );

        if ($publisherId > 0) {
            array_push($conditions, $this->_db->quoteName('p.publisher_id') . ' = ' . $this->_db->quote($publisherId));
        }

        // Create the query
        $query->select('p.*');
        $query->from($this->_db->quoteName($this->_tbl) . ' AS p');
        $query->where('((' .
                $this->_db->quoteName('p.created') . ' >= ' . $this->_db->quote($begin->toSql()) . ' AND ' .
                $this->_db->quoteName('p.created') . ' <= ' . $this->_db->quote($end->toSql()) . ') OR (' .
                $this->_db->quoteName('p.modified') . ' >= ' . $this->_db->quote($begin->toSql()) . ' AND ' .
                $this->_db->quoteName('p.modified') . ' <= ' . $this->_db->quote($end->toSql()) .
                '))');
        $query->where($conditions);
        $query->order('p.official_name ASC');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Delete all publications related to the publisher identified by
     * the given publisher id.
     * @param int $publisherId publisher id
     * @return int number of deleted rows
     */
    public function deleteByPublisherId($publisherId) {
        $query = $this->_db->getQuery(true);

        // Delete all publications related to the publisher
        $conditions = array(
            $this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId)
        );

        $query->delete($this->_db->quoteName($this->_tbl));
        $query->where($conditions);

        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // Return the number of deleted rows
        return $this->_db->getAffectedRows();
    }

    /**
     * Returns the number of received identifier applications between the given 
     * timeframe.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @param boolean $music if true the number of sheet music applications
     * is returned, otherwise the number of all the other applications is
     * returned
     * @return ObjectList number of received identifier applications grouped by 
     * year and month
     */
    public function getIdentifierApplicationCountByDates($begin, $end, $music = false) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Conditions
        $conditions = array(
            $this->_db->quoteName('p.created') . ' >= ' . $this->_db->quote($begin->toSql()),
            $this->_db->quoteName('p.created') . ' <= ' . $this->_db->quote($end->toSql()),
            $this->_db->quoteName('p.created_by') . ' = ' . $this->_db->quote('WWW')
        );

        // 
        if ($music) {
            array_push($conditions, $this->_db->quoteName('p.publication_type') . ' = ' . $this->_db->quote('SHEET_MUSIC'));
        } else {
            array_push($conditions, $this->_db->quoteName('p.publication_type') . ' != ' . $this->_db->quote('SHEET_MUSIC'));
        }

        // Create the query
        $query->select('YEAR(p.created) as year, MONTH(p.created) as month, count(distinct p.id) as count');
        $query->from($this->_db->quoteName($this->_tbl) . ' as p');

        $query->where($conditions);
        // Group by year and month
        $query->group('YEAR(p.created), MONTH(p.created)');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

}
