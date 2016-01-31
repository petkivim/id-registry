<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Message type Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelMessagetype extends JModelAdmin {

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $type    The table name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  JTable  A JTable object
     *
     * @since   1.6
     */
    public function getTable($type = 'Messagetype', $prefix = 'IsbnregistryTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed    A JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm(
                'com_isbnregistry.messagetype', 'messagetype', array(
            'control' => 'jform', 'load_data' => $loadData
                )
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since   1.6
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState(
                'com_isbnregistry.edit.messagetype.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Returns an associative array that contains all the message types
     * in the database as key-value pairs. Id is the key and name is the value.
     * @return array associative array
     */
    public function getMessageTypesHash() {
        // Get db access
        $table = $this->getTable();
        // Get types
        $types = $table->getMessageTypes();
        // Array for results
        $results = array();
        // Loop through the types
        foreach ($types as $type) {
            $results[$type->id] = $type->name;
        }
        // Return results
        return $results;
    }

    /**
     * Returns a list of message types that are allowed and valid for messaging 
     * settings. Only message types that have one template in each available
     * language can be used in settings.
     * @return Array array of valid message types
     */
    public function getAllowedMessageTypesForSettings() {
        // Init results array
        $result = array();
        // Load message template model
        $messageTemplateModel = JModelLegacy::getInstance('messagetemplate', 'IsbnregistryModel');
        // Load multi dimensional array that contains message 
        // type ids and language codes as keys:
        // $templates[1]['fi-FI'], $templates[1]['en-GB'] etc.
        $messageTypesLanguages = $messageTemplateModel->getMessageTemplatesGroupByTypeAndLanguage();
        // Check value
        if (!$messageTypesLanguages) {
            return $result;
        }
        // Get db access
        $table = $this->getTable();
        // Get installed languages
        $languages = $table->getInstalledLanguages();
        // Check languages
        if (!$languages) {
            return $result;
        }
        // Get message types
        $messageTypes = $table->getMessageTypes();
        // Check languages
        if (!$messageTypes) {
            return $result;
        }
        // Return results
        return $this->filterAllowedMessageTypes($messageTypes, $messageTypesLanguages, $languages);
    }

    /**
     * Filter message types and remove all the types that do not have one
     * and only one template in each installed language. The result is an
     * array of message type objects that meet the condition.
     * @param array $messageTypes all message types in the database
     * @param array $messageTypesLanguages multi dimensional array that 
     * contains message  type ids and language codes as keys
     * @param array $languages all the installed languages
     * @return array array of message type objects that have one and only
     * one template in each installed language
     */
    private function filterAllowedMessageTypes($messageTypes, $messageTypesLanguages, $languages) {
        // Init results array
        $result = array();
        // Loop through the message types
        foreach ($messageTypes as $messageType) {
            // If this message type does not exist in the array -> skip.
            // Message types that do not have any templates don't exist in
            // the array
            if (!$messageTypesLanguages[$messageType->id]) {
                continue;
            }
            // Initially message type is OK
            $ok = true;
            // Check that the message type has one template in all the
            // installed languages
            foreach ($languages as $lang) {
                // Init array
                if (!$messageTypesLanguages[$messageType->id]) {
                    $messageTypesLanguages[$messageType->id] = array();
                }
                // If message type does not have templates in this language
                // => not OK
                if (!array_key_exists($lang->lang_code, $messageTypesLanguages[$messageType->id])) {
                    $ok = false;
                    break;
                } else if ($messageTypesLanguages[$messageType->id][$lang->lang_code] != 1) {
                    // If message type has more than one template in this
                    // language => not OK
                    $ok = false;
                    break;
                }
            }
            // If message type is OK, add it to the results
            if ($ok) {
                array_push($result, $messageType);
            }
        }
        // Return results
        return $result;
    }

    /**
     * Checks if any templates use the message type identified by the given id
     * and if the message type is set as default in settings. If one of the
     * two alternative is true, the message type cannot be deleted.
     * @param int $messageTypeId message type id
     * @return boolean true if message type can be deleted; otherwise false
     */
    public function canBeDeleted($messageTypeId) {
        // Load message template model
        $messageTemplateModel = JModelLegacy::getInstance('messagetemplate', 'IsbnregistryModel');
        // Get number of templates that use this message type
        $templateCount = $messageTemplateModel->getMessageTemplatesCountByMessageType($messageTypeId);
        // If template count is not zero, message type cannot be deleted
        if ($templateCount != 0) {
            return false;
        }
        return true;
    }

    /**
     * Returns an array that contains all the installed languages. Only lang_id
     * and lang_code attributes are loaded.
     * @return array installed languages
     */
    public function getInstalledLanguages() {
        // Get db access
        $table = $this->getTable();
        // Get installed languages
        return $table->getInstalledLanguages();
    }

}
