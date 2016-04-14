<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Group Message Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTableGroupmessage extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_group_message', 'id', $db);
    }

    /**
     * Stores a group message.
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

        $this->created_by = $user->get('username');
        $this->created = $date->toSql();

        // From array to comma separated string
        $this->isbn_categories = $this->fromArrayToStr($this->isbn_categories);
        $this->ismn_categories = $this->fromArrayToStr($this->ismn_categories);

        return parent::store($updateNulls);
    }

    /**
     * Deletes a group message.
     *
     * @param   integer  $pk  Primary key of the message type to be deleted.
     *
     * @return  boolean  True on success, false on failure.
     *
     */
    public function delete($pk = null) {
        // Delete messages
        $messageModel = JModelLegacy::getInstance('message', 'IsbnregistryModel');
        $messageModel->deleteByGroupMessageId($pk);
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
     * Updates the given values to the database.
     * @param integer $groupMessageId group message id
     * @param integer $successCount how many messages were succesfully send
     * @param integer $failCount how many failed messages
     * @param integer $noEmailCount how many publishers without email
     * @return integer number of affected rows
     */
    public function updateResults($groupMessageId, $successCount, $failCount, $noEmailCount) {
        // Get date and user
        $date = JFactory::getDate();

        // Database connection
        $query = $this->_db->getQuery(true);

        // Fields to update.
        $fields = array(
            $this->_db->quoteName('success_count') . ' = ' . $this->_db->quote($successCount),
            $this->_db->quoteName('fail_count') . ' = ' . $this->_db->quote($failCount),
            $this->_db->quoteName('no_email_count') . ' = ' . $this->_db->quote($noEmailCount),
            $this->_db->quoteName('finished') . ' = ' . $this->_db->quote($date->toSql())
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $this->_db->quoteName('id') . ' = ' . $this->_db->quote($groupMessageId)
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
