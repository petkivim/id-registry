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

}
