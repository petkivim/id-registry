<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * ISSN Used Model
 *
 * @since  1.0.0
 */
class IssnregistryModelIssnused extends JModelAdmin {

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
    public function getTable($type = 'Issnused', $prefix = 'IssnregistryTable', $config = array()) {
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
                'com_issnregistry.issnused', 'issnused', array(
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
                'com_issnregistry.edit.issnused.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Adds new issn used to the database.
     * @param array $params array that contains the parameters
     * @return int id of the new issn used entry
     */
    public function addNew($params) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->addNew($params);
    }

    /**
     * Delete ISSN identified by the given publication id.
     * @param int $publicationId publication id
     * @return int number of deleted rows
     */
    public function deleteByPublicationId($publicationId) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->deleteByPublicationId($publicationId);
    }

    /**
     * Delete the ISSN identified by the ISSN range id and ISSN.
     * @param int $rangeId ISSN range id
     * @param string $issn ISSN to be deleted
     * @return boolean true on success, false on failure
     */
    public function deleteIssn($rangeId, $issn) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->deleteIssn($rangeId, $issn);
    }

    /**
     * Returns the row identified by the given ISSN number.
     * @param string $issn ISSN to be searched
     * @return object ISSN used object mathing the given ISSN or null
     */
    public function findByIssn($issn) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->findByIssn($issn);
    }

    /**
     * Returns the last ISSN that was given from the range identified by the 
     * given id. 
     * @param int $rangeId ISSN range id
     * @return string last ISSN that was given from the ISSN range identified
     * by the given id
     */
    public function getLast($rangeId) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->getLast($rangeId);
    }

}
