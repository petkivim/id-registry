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
 * Message Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTableMessage extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_message', 'id', $db);
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
        if (isset($this->params) && is_array($this->params)) {
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
        // Check if message has attachments
        if ($this->has_attachment) {
            if (!unlink(JPATH_COMPONENT . '/email/' . $this->attachment_name)) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_ERROR_MESSAGE_DELETE_ATTACHMENT_FAILED'), 'warning');
            }
        }

        return parent::delete($pk);
    }

    /**
     * Get all message ids related to the publisher identified by the given
     * publisher id.
     * @param int $publisherId publisher id
     * @return array array of message ids
     */
    public function getMessageIdsByPublisher($publisherId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('id')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadColumn();
    }

    /**
     * Get all message ids related to the batch identified by the given
     * batch id.
     * @param int $batchId batch id
     * @return array array of message ids
     */
    public function getMessageIdsByBatchId($batchId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('id')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('batch_id') . ' = ' . $this->_db->quote($batchId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadColumn();
    }

    /**
     * Return the number of messages related to the batch identified by the
     * given id
     * @param integer $batchId batch id
     * @return integer number of messages related to the given batch id
     */
    public function getMessageCountByBatchId($batchId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('count(id)')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('batch_id') . ' = ' . $this->_db->quote($batchId));
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
     * Get all batch ids and message ids related to the publisher 
     * identified by the given publisher id.
     * @param int $publisherId publisher id
     * @return array array of batch ids and message ids
     */
    public function getBatchIdsAndMessageIdsByPublisher($publisherId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('id, batch_id')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId));
        $query->order('id ASC');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Returns the number of sent messages between the given timeframe.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @return ObjectList number of sent messages grouped by year and
     * month
     */
    public function getMessageCountByDates($begin, $end) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Conditions
        $conditions = array(
            $this->_db->quoteName('sent') . ' >= ' . $this->_db->quote($begin->toSql()),
            $this->_db->quoteName('sent') . ' <= ' . $this->_db->quote($end->toSql())
        );
        // Create the query
        $query->select('YEAR(sent) as year, MONTH(sent) as month, count(distinct id) as count')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($conditions);
        // Group by year and month
        $query->group('YEAR(sent), MONTH(sent)');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

}
