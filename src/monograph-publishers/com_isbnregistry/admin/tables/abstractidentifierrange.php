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
 * Abstract Identifier Range Table class
 *
 * @since  1.0.0
 */
abstract class IsbnRegistryTableAbstractIdentifierRange extends JTable {

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
     * Stores an Indetifier Range.
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
            $this->category = strlen($this->range_begin);
            $this->free = $this->range_end - $this->range_begin + 1;
            $this->next = $this->range_begin;
        }

        return parent::store($updateNulls);
    }

    /**
     * Deletes an ISBN Range.
     *
     * @param   integer  $pk  Primary key of the ISBN range to be deleted.
     *
     * @return  boolean  True on success, false on failure.
     *
     */
    public function delete($pk = null) {
        // Item can be deleted only if no ISBNs have been used yet
        if (strcmp($this->range_begin, $this->next) != 0) {
            // If ISBNs have been used, raise a warning
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_IDENTIFIER_RANGES_DELETE_FAILED'), 'warning');
            // Return false as the item can't be deleted
            return false;
        }
        // No ISBNs have been used, delete the item
        return parent::delete($pk);
    }

    /**
     * Returns the identifier range identified by the given id. The range must
     * be marked as active.
     * @param integer $rangeId id of the range to be fetched
     * @param boolean $mustBeActive must range be active
     * @return object identifier range object on success; null on failure
     */
    public function getRange($rangeId, $mustBeActive) {
        $conditions = array(
            $this->_db->quoteName('id') . " = " . $this->_db->quote($rangeId)          
        );
        if($mustBeActive) {
            array_push($conditions, $this->_db->quoteName('is_active') . " = " . $this->_db->quote(true));
            array_push($conditions, $this->_db->quoteName('is_closed') . " = " . $this->_db->quote(false));
        }
        // Database query
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($conditions);
        $this->_db->setQuery((string) $query);

        return $this->_db->loadObject();
    }

    /**
     * Updates the given identifier range to the database.
     * @param Object $range object to be updated
     * @return boolean true on success
     */
    public function updateRange($range) {
        // Load object
        if (!$this->load($range->id)) {
            return false;
        }

        // Update fields
        $this->free = $range->free;
        $this->taken = $range->taken;
        $this->next = $range->next;
        $this->is_active = $range->is_active;
        $this->is_closed = $range->is_closed;

        // Update object to DB
        return $this->store();
    }

}
