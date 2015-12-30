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
 * ISMN Range Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTableIsmnrange extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_ismn_range', 'id', $db);
    }

    /**
     * Stores an ISMN Range.
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
     * Deletes an ISMN Range.
     *
     * @param   integer  $pk  Primary key of the ISMN range to be deleted.
     *
     * @return  boolean  True on success, false on failure.
     *
     */
	public function delete($pk = null) {
		// Item can be deleted only if no ISBNs have been used yet
		if(strcmp($this->range_begin, $this->next) != 0) {
			// If ISMNs have been used, raise a warning
			JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_ISMN_RANGES_DELETE_FAILED'), 'warning');
			// Return false as the item can't be deleted
			return false;
		}
		// No ISMNs have been used, delete the item
		return parent::delete($pk);
	}

}
