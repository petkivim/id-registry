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
 * Message Table class
 *
 * @since  1.0.0
 */
class IssnRegistryTableMessage extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__issn_registry_message', 'id', $db);
    }

    /**
     * Stores a message.
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

        // New item
        if (!$this->id) {
            // New item
            $username = $user->get('username');
            $this->sent_by = empty($username) ? 'SYSTEM' : $username;
            $this->sent = $date->toSql();
        }

        return parent::store($updateNulls);
    }

    /**
     * Deletes a message.
     *
     * @param   integer  $pk  Primary key of the message to be deleted.
     *
     * @return  boolean  True on success, false on failure.
     *
     */
    public function delete($pk = null) {
        return parent::delete($pk);
    }

    /**
     * Get all message ids that are related to the publisher identified by the given
     * publisher id, but that are not related to a form
     * @param int $publisherId publisher id
     * @return array array of message ids
     */
    public function getMessageIdsByPublisher($publisherId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        $conditions = array(
            $this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId),
            $this->_db->quoteName('form_id') . ' = ' . $this->_db->quote(0)
        );

        // Create the query
        $query->select('id')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($conditions);
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadColumn();
    }

    /**
     * Get all message ids related to the form identified by the given
     * form id.
     * @param int $formId form id
     * @return array array of message ids
     */
    public function getMessageIdsByForm($formId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('id')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('form_id') . ' = ' . $this->_db->quote($formId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadColumn();
    }

    /**
     * Return the number of messages related to the form identified by the
     * given id
     * @param integer $formId form id
     * @return integer number of messages related to the given form id
     */
    public function getMessageCountByFormId($formId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('count(id)')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('form_id') . ' = ' . $this->_db->quote($formId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadResult();
    }

    /**
     * Get all message ids related to the group message identified by the given
     * group message id.
     * @param int $groupMessageId group message id
     * @return array array of message ids
     */
    public function getMessageIdsByGroupMessageId($groupMessageId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('id')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('group_message_id') . ' = ' . $this->_db->quote($groupMessageId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadColumn();
    }

    /**
     * Removes reference to the publisher by the given id by setting
     * publisher id to zero. Publisher id is reseted only in case the
     * messages is related to a form.
     * @param int $publisherId id of the publisher
     * @return int number of affected rows
     */
    public function resetPublisherId($publisherId) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $query = $this->_db->getQuery(true);

        // Fields to update.
        $fields = array(
            $this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote(0)
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId),
            $this->_db->quoteName('form_id') . ' != ' . $this->_db->quote(0)
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
