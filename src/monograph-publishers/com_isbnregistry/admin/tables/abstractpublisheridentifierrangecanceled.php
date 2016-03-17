<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Abstract Publisher Identifier Range Canceled Table class
 *
 * @since  1.0.0
 */
abstract class IsbnRegistryTableAbstractPublisherIdentifierRangeCanceled extends JTable {

    /**
     * Constructor
     *
     * @param   String Name of the table
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct($tableName, &$db) {
        parent::__construct($tableName, 'id', $db);
    }

    /**
     * Stores a canceled identifier.
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

        if (!$this->id) {
            // Get date and user
            $date = JFactory::getDate();
            $user = JFactory::getUser();
            // New item
            $this->canceled_by = $user->get('username');
            $this->canceled = $date->toSql();
        }

        return parent::store($updateNulls);
    }

    /**
     * Delete the identifier identified by the range id and identifier.
     * @param int $rangeId identifier range id
     * @param string $identifier identifier to be deleted
     * @return boolean true on success, false on failure
     */
    public function deleteIdentifier($rangeId, $identifier) {
        $query = $this->_db->getQuery(true);

        // Delete identifier
        $conditions = array(
            $this->_db->quoteName('range_id') . ' = ' . $this->_db->quote($rangeId),
            $this->_db->quoteName('identifier') . ' = ' . $this->_db->quote($identifier)
        );

        $query->delete($this->_db->quoteName($this->_tbl));
        $query->where($conditions);

        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // If affected rows returns 1, operation succeeded
        if ($this->_db->getAffectedRows() == 1) {
            return true;
        }
        // Otherwise return false
        return false;
    }

    /**
     * Returns the smallest canceled identifier object (fifo) that belongs to 
     * the given category and that was given from the range identified by the 
     * given id. If id is not defined, the smallest issn from any range is 
     * returned.
     * @param int $category category
     * @param int $rangeId identifier range id
     * @return string smallest identifier that was given from the 
     * identifier range identified by the given id
     */
    public function getIdentifier($category, $rangeId = 0) {
        // Get query
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*')->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('category') . ' = ' . $this->_db->quote($category));
        if ($rangeId > 0) {
            $query->where($this->_db->quoteName('range_id') . ' = ' . $this->_db->quote($rangeId));
        }
        $query->order('identifier ASC')->setLimit('1');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObject();
    }

    /**
     * Returns the smallest canceled identifier object (fifo) belonging to the
     * category identified by the given category. If no identifiers is 
     * available from the given category, null is returned.
     * @param int $category category
     * @return string smallest identifier that from the category
     */
    public function getIdentifierByCategory($category) {
        // Get query
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*')->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('category') . ' = ' . $this->_db->quote($category));
        $query->order('identifier ASC')->setLimit('1');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObject();
    }

}
