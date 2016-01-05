<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivim�ki
 * @copyright	Copyright (C) 2016 Petteri Kivim�ki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Message type Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTableMessagetype extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_message_type', 'id', $db);
    }

    /**
     * Stores a message type.
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

        return parent::store($updateNulls);
    }

    public function delete($pk = null) {

        return parent::delete($pk);
    }

    /**
     * Returns a list of message type objects that contains all the message
     * types in the database. Only id and name attributes are loaded.
     * @return list list of message type objects
     */
    public function getMessageTypes() {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('id, name')
                ->from($this->_db->quoteName($this->_tbl))
                ->order('name ASC');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

}
