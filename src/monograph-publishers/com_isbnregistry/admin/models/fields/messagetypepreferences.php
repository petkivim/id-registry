<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

/**
 * Message type Form Field class for the ISBN Registry component
 *
 * @since  1.0.0
 */
class JFormFieldMessagetypepreferences extends JFormFieldList {

    /**
     * Local cache for db query results.
     * 
     * @var Object List
     */
    private static $cache;

    /**
     * The field type.
     *
     * @var         string
     */
    protected $type = 'MessageTypePreferences';

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array  An array of JHtml options.
     */
    protected function getOptions() {
        // Are results already cached?
        if (!self::$cache) {
            // Get all the installed languages
            $languages = $this->getLanguages();
            // Get multi dimensional array that contains message type ids and 
            // language codes as keys:
            // $result[1]['fi-FI'], $result[1]['en-GB'] etc.
            $messageTypeIds = $this->getMessageTypeIdsAndLanguages();

            // Get message types
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select('id,name');
            $query->from('#__isbn_registry_message_type');
            $query->order('name ASC');
            $db->setQuery((string) $query);
            $msgTypes = $db->loadObjectList();
            $options = array('' => JText::_('COM_ISBNREGISTRY_FIELD_SELECT_MESSAGE_TYPE'));

            // Check that we have message types, languages and message type ids
            if ($msgTypes && $languages && $messageTypeIds) {
                // Go through message types
                foreach ($msgTypes as $msgType) {
                    // If this message type does not exist in the array -> skip.
                    // Message types that do not have any templates don't exist in
                    // the array
                    if (!$messageTypeIds[$msgType->id]) {
                        continue;
                    }
                    // Initially message type is OK
                    $ok = true;
                    // Go through all the languages and check that the message 
                    // type has one template in all the installed languages
                    foreach ($languages as $lang) {
                        if (!array_key_exists($lang->lang_code, $messageTypeIds[$msgType->id])) {
                            // If message type does not have templates in this language
                            // => not OK
                            $ok = false;
                            break;
                        } else if ($messageTypeIds[$msgType->id][$lang->lang_code] != 1) {
                            // If message type has more than one template in this
                            // language => not OK
                            $ok = false;
                            break;
                        }
                    }
                    // If message type is OK, add it to the results
                    if ($ok) {
                        $options[] = JHtml::_('select.option', $msgType->id, $msgType->name);
                    }
                }
            }
            // Store results into cache
            self::$cache = array_merge(parent::getOptions(), $options);
        }
        return self::$cache;
    }

    /**
     * Returns an array that contains all the installed languages. Only lang_id
     * and lang_code attributes are loaded.
     * @return array installed languages
     */
    private function getLanguages() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('lang_id, lang_code');
        $query->from('#__languages');
        $query->order('lang_code ASC');
        $db->setQuery((string) $query);
        return $db->loadObjectList();
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
    private function getMessageTypeIdsAndLanguages() {
        // Get templates from the DB
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('id, message_type_id, lang_code');
        $query->from('#__isbn_registry_message_template');
        $query->order('message_type_id ASC');
        $db->setQuery((string) $query);
        $templates = $db->loadObjectList();
        // Init array for results
        $result = array();
        // Loop through the templates
        foreach ($templates as $template) {
            // Init array for the message type id
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
        return $result;
    }

}
