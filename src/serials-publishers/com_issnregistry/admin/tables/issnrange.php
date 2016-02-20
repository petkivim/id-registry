<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * ISSN Range Table class
 *
 * @since  1.0.0
 */
class IssnRegistryTableIssnrange extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__issn_registry_issn_range', 'id', $db);
    }

    /**
     * Stores an ISSN Range.
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
            // If this object is active, disactivate all the others
            if (!$this->is_closed && $this->is_active) {
                self::disactivateOther($this->id);
            }
        } else {
            // New item
            $this->created_by = $user->get('username');
            $this->created = $date->toSql();
            $this->free = $this->range_end - $this->range_begin + 1;

            // Add ISSN range helper file
            require_once JPATH_COMPONENT . '/helpers/issnrange.php';
            // Get range begin check digit
            $rangeBeginCheckDigit = IssnrangeHelper::countIssnCheckDigit($this->block . $this->range_begin);
            // Set range begin check digit
            $this->range_begin .= $rangeBeginCheckDigit;
            // Set next pointer
            $this->next = $this->range_begin;
            // Get range end check digit
            $rangeEndCheckDigit = IssnrangeHelper::countIssnCheckDigit($this->block . $this->range_end);
            // Set range end check digit
            $this->range_end .= $rangeEndCheckDigit;
            // If this object is active, disactivate all the others
            if ($this->is_active) {
                self::disactivateOther(0);
            }
        }

        return parent::store($updateNulls);
    }

    /**
     * Deletes an ISSN Range.
     *
     * @param   integer  $pk  Primary key of the ISSN range to be deleted.
     *
     * @return  boolean  True on success, false on failure.
     *
     */
    public function delete($pk = null) {
        // Item can be deleted only if no ISSNs have been used yet
        if (strcmp($this->range_begin, $this->next) != 0) {
            // If ISSNs have been used, raise a warning
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ISSN_RANGES_DELETE_FAILED'), 'warning');
            // Return false as the item can't be deleted
            return false;
        }
        // No ISSNs have been used, delete the item
        return parent::delete($pk);
    }

    /**
     * Disactivates all the other issn ranges except the one identified by
     * the given id.
     * 
     * @param int $rangeId id of the ISSN range that is not updated
     * @return int number of affected database rows
     */
    public function disactivateOther($rangeId) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $query = $this->_db->getQuery(true);

        // Fields to update.
        $fields = array(
            $this->_db->quoteName('is_active') . ' = ' . $this->_db->quote(false),
            $this->_db->quoteName('modified') . ' = ' . $this->_db->quote($date->toSql()),
            $this->_db->quoteName('modified_by') . ' = ' . $this->_db->quote($user->get('username'))
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $this->_db->quoteName('id') . ' != ' . $this->_db->quote($rangeId)
        );
        // Create query
        $query->update($this->_db->quoteName($this->_tbl))->set($fields)->where($conditions);
        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // Return the number of rows that was updated
        return $this->_db->getAffectedRows();
    }

}
