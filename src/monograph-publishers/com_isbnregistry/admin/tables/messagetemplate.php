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
 * Message template Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTableMessagetemplate extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_message_template', 'id', $db);
    }

    /**
     * Stores a message template.
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

    /**
     * Deletes a message template.
     *
     * @param   integer  $pk  Primary key of the message template to be deleted.
     *
     * @return  boolean  True on success, false on failure.
     *
     */
    public function delete($pk = null) {
        // Add configuration helper file
        require_once JPATH_COMPONENT . '/helpers/configuration.php';
        // If message template's message type is defined as default in 
        // configuration, it cannot be removed
        if (ConfigurationHelper::isMessageTypeUsedInConfiguration($this->message_type_id)) {
            // Raise a warning
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_MESSAGE_TEMPLATE_DELETE_FAILED_SETTINGS'), 'warning');
            // Return false as the item can't be deleted
            return false;
        }
        // No ISBNs have been used, delete the item
        return parent::delete($pk);
    }

    /**
     * Returns a list of message template objects that contains all the message
     * templates in the database. Only id, name, message_type_id and lang_code 
     * attributes are loaded.
     * @return list list of message template objects
     */
    public function getMessageTemplates() {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('id, name, message_type_id, lang_code')
                ->from($this->_db->quoteName($this->_tbl))
                ->order('name ASC');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Returns the number of message templates that represent the message
     * type identified by the given message type id.
     * @param int $messageTypeId id of the message type
     * @return int number of message templates
     */
    public function getMessageTemplatesCountByMessageType($messageTypeId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('count(id)')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('message_type_id') . ' = ' . $this->_db->quote($messageTypeId))
                ->order('name ASC');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadResult();
        ;
    }

    /**
     * Returns the template matching the given message type id and language 
     * code.
     * @param int $messageTypeId message type id
     * @param string $languageCode language code
     * @return MessageTemplate message template matching the given conditions
     */
    public function getMessageTemplateByTypeAndLanguage($messageTypeId, $languageCode) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Conditions for which records should be fetched
        $conditions = array(
            $this->_db->quoteName('message_type_id') . ' = ' . $this->_db->quote($messageTypeId),
            $this->_db->quoteName('lang_code') . ' = ' . $this->_db->quote($languageCode)
        );

        // Create the query
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($conditions);
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObject();
    }

    /**
     * Returns templates matching the given message type
     * @param int $messageTypeId message type id
     * @return MessageTemplate message templates matching the given conditions
     */
    public function getMessageTemplatesByType($messageTypeId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Conditions for which records should be fetched
        $conditions = array(
            $this->_db->quoteName('message_type_id') . ' = ' . $this->_db->quote($messageTypeId)
        );

        // Create the query
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($conditions);
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

}
