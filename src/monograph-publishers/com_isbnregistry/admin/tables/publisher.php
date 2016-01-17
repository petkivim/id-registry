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
 * Publisher Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTablePublisher extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_publisher', 'id', $db);
    }

    /**
     * Stores a publisher.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   1.6
     */
    public function store($updateNulls = false) {
        // Transform the params field
        if (is_array($this->params)) {
            $registry = new Registry;
            $registry->loadArray($this->params);
            $this->params = (string) $registry;
        }

        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        if ($this->id) {
            // Existing item
            $this->modified_by = $user->get('username');
            $this->modified = $date->toSql();
        } else {
            // New item
            $this->created_by = $user->get('username');
            $this->created = $date->toSql();
        }

        // Question 7: from array to comma separated string
        if (is_array($this->question_7)) {
            if (count($this->question_7) > 0) {
                $this->question_7 = implode(',', $this->question_7);
            } else {
                $this->question_7 = '';
            }
        } else {
            $this->question_7 = '';
        }


        return parent::store($updateNulls);
    }

    public function delete($pk = null) {

        return parent::delete($pk);
    }

    /**
     * Returns a publisher mathcing the given id.
     * @param int $publisherId id of the publisher
     * @return Publisher publisher matching the given id
     */
    public function getPublisherById($publisherId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($publisherId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObject();
    }

    /**
     * Returns an Object List that contains all the publishers in the
     * database.
     * @return ObjectList list of all the publishers
     */
    public function getPublishers() {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->order('official_name ASC');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Updates the active ISBN identifier of the publisher identified by
     * the given publisher id.
     * @param int $publisherId id of the publisher to be updated
     * @param string $identifier ISBN identifier string
     * @return true on success; false on failure
     */
    public function updateActiveIsbnIdentifier($publisherId, $identifier) {
        // Conditions for which records should be updated.
        $conditions = array(
            'id' => $publisherId
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update identifier type
        $this->active_identifier_isbn = $identifier;

        // Update object to DB
        return $this->store();
    }

    /**
     * Updates the active ISMN identifier of the publisher identified by
     * the given publisher id.
     * @param int $publisherId id of the publisher to be updated
     * @param string $identifier ISMN identifier string
     * @return true on success; false on failure
     */
    public function updateActiveIsmnIdentifier($publisherId, $identifier) {
        // Conditions for which records should be updated.
        $conditions = array(
            'id' => $publisherId
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update identifier type
        $this->active_identifier_ismn = $identifier;

        // Update object to DB
        return $this->store();
    }

    /**
     * Returns a list of publishers and all the publisher ISBN identifiers.
     * If publisher has multiple identifiers, the publisher is included in the
     * list multiple times.
     * @return list of publishers
     */
    public function getPublishersAndIsbnIdentifiers() {
        // Initialize variables.
        $query = $this->_db->getQuery(true);
        

        // Create the query
        $query->select('*');
        $query->from($this->_db->quoteName($this->_tbl) . ' AS p');
        $query->join('INNER', '#__isbn_registry_publisher_isbn_range AS pir ON p.id = pir.publisher_id');
        $query->order('p.official_name ASC');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

}
