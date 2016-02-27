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
 * Message type Model
 *
 * @since  1.0.0
 */
class IssnregistryModelForm extends JModelAdmin {

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
    public function getTable($type = 'Form', $prefix = 'IssnregistryTable', $config = array()) {
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
                'com_issnregistry.form', 'form', array(
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
                'com_issnregistry.edit.form.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Returns the number of forms that are linked to the given publisher.
     * @param int $publisherId id of the publisher
     * @return int number of forms
     */
    public function getFormsCountByPublisherId($publisherId) {
        // Get db access
        $table = $this->getTable();
        // Get publisher
        return $table->getFormsCountByPublisherId($publisherId);
    }

    /**
     * Sets publisher created attribute to false.
     * @param int $formId id of the form to be updated
     * @return boolean true on success; false on failure
     */
    public function removePublisherCreated($formId) {
        // Get db access
        $table = $this->getTable();
        // Set "publisher_created" to false
        return $table->removePublisherCreated($formId);
    }

    /**
     * Increases the publication of the form identified by the given form id
     * by one.
     * @param int $formId form id
     * @param int $oldCount current publication count that's increased by one
     * @return boolean true on success; false on failure
     */
    public function increasePublicationCount($formId, $oldCount) {
        // Get db access
        $table = $this->getTable();
        // Increase count
        return $table->increasePublicationCount($formId, $oldCount);
    }

    /**
     * Decreases the publication of the form identified by the given form id
     * by one.
     * @param int $formId form id
     * @param int $oldCount current publication count that's decreased by one
     * @return boolean true on success; false on failure
     */
    public function decreasePublicationCount($formId, $oldCount) {
        // Get db access
        $table = $this->getTable();
        // Decrease count
        return $table->decreasePublicationCount($formId, $oldCount);
    }

}
