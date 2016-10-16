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
 * Group Message Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelGroupmessage extends JModelAdmin {

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
    public function getTable($type = 'Groupmessage', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.groupmessage', 'groupmessage', array(
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
                'com_isbnregistry.edit.groupmessage.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        // From comma separated string to array
        if ($data->isbn_categories) {
            $data->isbn_categories = $this->fromStrToArray($data->isbn_categories);
        }
        if ($data->ismn_categories) {
            $data->ismn_categories = $this->fromStrToArray($data->ismn_categories);
        }

        return $data;
    }

    /**
     * Converts the given comma separated string to array.
     */
    public function fromStrToArray($source) {
        if ($source && !is_array($source)) {
            $source = explode(',', $source);
        }
        return $source;
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   1.6
     */
    public function save($data) {
        $table = $this->getTable();

        // Bind the data.
        if (!$table->bind($data)) {
            $this->setError($table->getError());

            return false;
        }

        // Load publisher model
        $publisherModel = JModelLegacy::getInstance('publisher', 'IsbnregistryModel');

        // Check that at least one category has been selected
        if (empty($table->isbn_categories) && empty($table->ismn_categories)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_GROUP_MESSAGE_NO_RECIPIENTS'));
            return false;
        }
        // Get ISBN publishers matching the conditions
        $isbnPublishers = $publisherModel->getPublishersByCategory($this->prepareForQuery($table->isbn_categories, 6), $table->has_quitted, 'isbn');
        // Get ISMN publishers matching the conditions
        $ismnPublishers = $publisherModel->getPublishersByCategory($this->prepareForQuery($table->ismn_categories, 8), $table->has_quitted, 'ismn');
        // Set publisher array sizes
        $table->isbn_publishers_count = sizeof($isbnPublishers);
        $table->ismn_publishers_count = sizeof($ismnPublishers);

        // Load message template model
        $messageTemplateModel = JModelLegacy::getInstance('messagetemplate', 'IsbnregistryModel');
        // Get templates
        $templates = $messageTemplateModel->getMessageTemplatesByType($table->message_type_id);
        // Load message type model
        $messageTypeModel = JModelLegacy::getInstance('messagetype', 'IsbnregistryModel');
        // Get installed languages
        $languages = $messageTypeModel->getInstalledLanguages();
        // Check that there's a template in all the languages
        $templateHash = $this->checkTemplatesAndLanguages($templates, $languages);
        // If result is empty, templates are missing
        if (empty($templateHash)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_GROUP_MESSAGE_INVALID_MESSAGE_TYPE'));
            return false;
        }
        // Merge publisher arrays
        $publishers = $this->mergePublisherArrays($isbnPublishers, $ismnPublishers);
        // Set publishers count
        $table->publishers_count = sizeof($publishers);

        // Store the data.
        if (!$table->store()) {
            $this->setError($table->getError());
            return false;
        }

        // Send messages. Replace "/dev/null" with "~/debug.log 2>&1" for debugging.
        $cmd = 'nohup nice -n 10 ' . PHP_BINDIR . '/php ' . JPATH_COMPONENT_ADMINISTRATOR . '/helpers/groupmessages-script.php ' . $table->id . ' > /dev/null 2>&1 &';
        exec($cmd);

        return true;
    }

    public function prepareForQuery($array, $digit) {
        $result = array();
        if (!$array) {
            return $result;
        }
        foreach ($array as $value) {
            array_push($result, $digit - $value);
        }
        return $result;
    }

    public function checkTemplatesAndLanguages($templates, $languages) {
        $result = array();
        // Create associative array where language code is the key and
        // template is the value
        foreach ($templates as $template) {
            $result[$template->lang_code] = $template;
        }
        // Check that there are templates in all the installed languages
        foreach ($languages as $language) {
            // Return an empty array, if language is not present
            if (!array_key_exists($language->lang_code, $result)) {
                return array();
            }
        }
        return $result;
    }

    public function mergePublisherArrays($isbnPublishers, $ismnPublishers) {
        $publishers = array();
        foreach ($isbnPublishers as $publisher) {
            if (!array_key_exists($publisher->id, $publishers)) {
                $publishers[$publisher->id] = $publisher;
            }
        }
        foreach ($ismnPublishers as $publisher) {
            if (!array_key_exists($publisher->id, $publishers)) {
                $publishers[$publisher->id] = $publisher;
            }
        }
        return $publishers;
    }

    /**
     * Updates the given values to the database.
     * @param integer $groupMessageId group message id
     * @param integer $successCount how many messages were succesfully send
     * @param integer $failCount how many failed messages
     * @param integer $noEmailCount how many publishers without email
     * @return boolean true on success, false on failure
     */
    public function updateResults($groupMessageId, $successCount, $failCount, $noEmailCount) {
        // Get DB access
        $table = $this->getTable();
        // Run update
        $result = $table->updateResults($groupMessageId, $successCount, $failCount, $noEmailCount);
        // Return result
        if ($result == 1) {
            return true;
        }
        return false;
    }

}
