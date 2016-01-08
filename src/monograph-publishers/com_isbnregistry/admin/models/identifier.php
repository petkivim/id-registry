<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivim�ki
 * @copyright	Copyright (C) 2015 Petteri Kivim�ki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Identifier Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelIdentifier extends JModelAdmin {

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
    public function getTable($type = 'Identifier', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.identifier', 'identifier', array(
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
                'com_isbnregistry.edit.identifier.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Adds new identifiers to the database.
     * @param array $identifiers array that contains the identifiers to be added
     * @param int $identifierBatchId batch id of the identifiers
     * @return bool true on success; otherwise false
     */
    public function addNew($identifiers, $identifierBatchId) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->addNew($identifiers, $identifierBatchId);
    }

    /**
     * Returns identifiers with the given batch id.
     * @param int $identifierBatchId identifier batch id related to the identifiers
     * @return list of identifier objects
     */
    public function getIdentifiers($identifierBatchId) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->getIdentifiers($identifierBatchId);
    }

    /**
     * Returns identifiers with the given batch id.
     * @param int $identifierBatchId
     * @return array array identifier strings
     */
    public function getIdentifiersArray($identifierBatchId) {
        // Get result
        $list = $this->getIdentifiers($identifierBatchId);
        // Results array
        $results = array();
        // Loop through the objects list
        foreach($list as $item) {
            array_push($results, $item->identifier);
        }
        // Return array
        return $results;
    }

}