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
 * Message type Table class
 *
 * @since  1.0.0
 */
class IssnRegistryTableMessagetype extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__issn_registry_message_type', 'id', $db);
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

    /**
     * Deletes a message type.
     *
     * @param   integer  $pk  Primary key of the message type to be deleted.
     *
     * @return  boolean  True on success, false on failure.
     *
     */
    public function delete($pk = null) {
        // Add configuration helper file
        require_once JPATH_COMPONENT . '/helpers/configuration.php';
        // If message type is defined as default in configuration, it cannot be removed
        if (ConfigurationHelper::isMessageTypeUsedInConfiguration($this->id)) {
            // Raise a warning
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_MESSAGE_TYPE_DELETE_FAILED_SETTINGS'), 'warning');
            // Return false as the item can't be deleted
            return false;
        }
        // Load message template model
        $messageTemplateModel = JModelLegacy::getInstance('messagetemplate', 'IssnregistryModel');
        // Get number of templates that use this message type
        $templateCount = $messageTemplateModel->getMessageTemplatesCountByMessageType($this->id);
        // If template count is not zero, message type cannot be deleted
        if ($templateCount != 0) {
            // Raise a warning
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_MESSAGE_TYPE_DELETE_FAILED_USED'), 'warning');
            // Return false as the item can't be deleted
            return false;
        }

        // No ISSNs have been used, delete the item
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

    /**
     * Returns an array that contains all the installed languages. Only lang_id
     * and lang_code attributes are loaded.
     * @return array installed languages
     */
    public function getInstalledLanguages() {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('lang_id, lang_code')
                ->from($this->_db->quoteName('#__languages'))
                ->order('lang_code ASC');
        $this->_db->setQuery($query);

        return $this->_db->loadObjectList();
    }

}
