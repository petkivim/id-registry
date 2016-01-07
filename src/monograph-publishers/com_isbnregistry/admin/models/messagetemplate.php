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
 * Message template Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelMessagetemplate extends JModelAdmin {

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
    public function getTable($type = 'Messagetemplate', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.messagetemplate', 'messagetemplate', array(
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
                'com_isbnregistry.edit.messagetemplate.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Returns a list of message template objects that contains all the message
     * templates in the database. Only id, name, message_type_id and lang_code 
     * attributes are loaded.
     * @return list list of message template objects
     */
    public function getMessageTemplates() {
        // Get DAO for db access
        $dao = $this->getTable();
        // Get templates
        return $dao->getMessageTemplates();
    }

    /**
     * Returns a multi dimensional array that contains message types and 
     * language codes of their templates. The array also tells if the message
     * type has one or more templates in the same language. Message types
     * that do not have any templates are excluded from the result.
     * or more template 
     * @return array multi dimensional array array that contains message 
     * type ids and language codes as keys
     */
    public function getMessageTemplatesGroupByTypeAndLanguage() {
        // Get templates
        $templates = $this->getMessageTemplates();
        // Init array for results
        $result = array();
        // Check templates variable
        if (!$templates) {
            return $result;
        }
        // Loop through templates and create a multi dimensional array that
        // contains message type ids and language codes as keys:
        // $result[1]['fi-FI'], $result[1]['en-GB'] etc.
        foreach ($templates as $template) {
            // Init array
            if (!$result[$template->message_type_id]) {
                $result[$template->message_type_id] = array();
            }
            // Does this message type already have a template in this language?
            if (!array_key_exists($template->lang_code, $result[$template->message_type_id])) {
                // This is the first occurrence, so set the value to true
                $result[$template->message_type_id][$template->lang_code] = true;
            } else {
                // This is not the first occurrence, set value to false
                $result[$template->message_type_id][$template->lang_code] = false;
            }
        }
        // Return result
        return $result;
    }

    /**
     * Returns the number of message templates that represent the message
     * type identified by the given message type id.
     * @param int $messageTypeId id of the message type
     * @return int number of message templates
     */
    public function getMessageTemplatesCountByMessageType($messageTypeId) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Get number of templates
        return $dao->getMessageTemplatesCountByMessageType($messageTypeId);
    }

    /**
     * Returns the template matching the given message type id and language 
     * code.
     * @param int $messageTypeId message type id
     * @param string $languageCode language code
     * @return MessageTemplate message template matching the given conditions
     */
    public function getMessageTemplateByTypeAndLanguage($messageTypeId, $languageCode) {
        // Get db access
        $table = $this->getTable();
        // Get template
        return $table->getMessageTemplateByTypeAndLanguage($messageTypeId, $languageCode);
    }

}
