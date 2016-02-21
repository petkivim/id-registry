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

    /**
     * Returns the ISSN range identified by the given id. The range must
     * be marked as active.
     * @param integer $rangeId id of the range to be fetched
     * @param boolean $mustBeActive must range be active
     * @return object identifier range object on success; null on failure
     */
    public function getRange($rangeId, $mustBeActive) {
        $conditions = array(
            $this->_db->quoteName('id') . " = " . $this->_db->quote($rangeId)
        );
        if ($mustBeActive) {
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
     * Returns the currently active ISSN range. Only one range can be active
     * at a time.
     * @return object identifier range object on success; null on failure
     */
    public function getActiveRange() {
        $conditions = array(
            $this->_db->quoteName('is_active') . " = " . $this->_db->quote(true),
            $this->_db->quoteName('is_closed') . " = " . $this->_db->quote(false)
        );
        // Database query
        $query = $this->_db->getQuery(true);
        $query->select('*');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($conditions);
        $this->_db->setQuery((string) $query);

        return $this->_db->loadObject();
    }

    /**
     * Updates the given ISSN range to the database. This method must
     * be used when the number of used identifiers is being increased.
     * @param Object $range object to be updated
     * @return boolean true on success
     */
    public function updateIncrease($range) {
        // Conditions for which records should be updated.
        $conditions = array(
            $this->_db->quoteName('id') . ' = ' . $this->_db->quote($range->id),
            $this->_db->quoteName('free') . ' = ' . $this->_db->quote(($range->free + 1)),
            $this->_db->quoteName('taken') . ' = ' . $this->_db->quote(($range->taken - 1))
        );
        return $this->updateRange($range, $conditions);
    }

    /**
     * Updates the given ISSN range to the database. This method must
     * be used when the number of used identifiers is being decreased.
     * @param Object $range object to be updated
     * @return boolean true on success
     */
    public function updateDecrease($range) {
        // Conditions for which records should be updated.
        $conditions = array(
            $this->_db->quoteName('id') . ' = ' . $this->_db->quote($range->id),
            $this->_db->quoteName('free') . ' = ' . $this->_db->quote(($range->free - 1)),
            $this->_db->quoteName('taken') . ' = ' . $this->_db->quote(($range->taken + 1))
        );
        return $this->updateRange($range, $conditions);
    }

    /**
     * Updates the given ISSN range to the database.
     * @param Object $range object to be updated
     * @param array $conditions conditions for the update operation
     * @return boolean true on success
     */
    private function updateRange($range, $conditions) {
        $query = $this->_db->getQuery(true);

        // Fields to update.
        $fields = array(
            $this->_db->quoteName('free') . ' = ' . $this->_db->quote($range->free),
            $this->_db->quoteName('taken') . ' = ' . $this->_db->quote($range->taken),
            $this->_db->quoteName('next') . ' = ' . $this->_db->quote($range->next),
            $this->_db->quoteName('is_active') . ' = ' . $this->_db->quote($range->is_active),
            $this->_db->quoteName('is_closed') . ' = ' . $this->_db->quote($range->is_closed)
        );

        // Set update query
        $query->update($this->_db->quoteName($this->_tbl))->set($fields)->where($conditions);
        $this->_db->setQuery($query);

        // Execute query
        $result = $this->_db->execute();

        // If operation succeeded, one row was affected
        if ($this->_db->getAffectedRows() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Starts a transaction.
     */
    public function transactionStart() {
        $this->_db->transactionStart();
    }

    /**
     * Commits a transaction.
     */
    public function transactionCommit() {
        $this->_db->transactionCommit();
    }

    /**
     * Transaction rollback.
     */
    public function transactionRollback() {
        $this->_db->transactionRollback();
    }

}
