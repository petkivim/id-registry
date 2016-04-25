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
 * Publisher Model
 *
 * @since  1.0.0
 */
class IssnregistryModelPublisher extends JModelAdmin {

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
    public function getTable($type = 'Publisher', $prefix = 'IssnregistryTable', $config = array()) {
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
                'com_issnregistry.publisher', 'publisher', array(
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
                'com_issnregistry.edit.publisher.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Returns a publisher mathcing the given id.
     * @param int $publisherId id of the publisher
     * @return Publisher publisher matching the given id
     */
    public function getPublisherById($publisherId) {
        // Get db access
        $table = $this->getTable();
        // Get publisher
        return $table->getPublisherById($publisherId);
    }

    /**
     * Returns an array that contains publisher id and name as key value
     * pairs.
     * @return array publisher id and name as key value pairs
     */
    public function getPublishersArray() {
        // Get db access
        $table = $this->getTable();
        // Get publishers
        $publishers = $table->getPublishers();
        // Check result
        if (!$publishers) {
            return array();
        }
        $result = array();
        foreach ($publishers as $publisher) {
            $result[$publisher->id] = $publisher->official_name;
        }
        return $result;
    }

    /**
     * Returns a publisher identified by the given form id.
     * @param int $formId id of the form
     * @return Publisher publisher matching the given form id
     */
    public function getByFormId($formId) {
        // Get db access
        $table = $this->getTable();
        // Get publisher
        return $table->getByFormId($formId);
    }

    /**
     * Removes reference to the form identified by the given id by setting
     * form id to zero.
     * @param int $formId id of the form
     * @return int number of publishers that we updated
     */
    public function resetFormId($formId) {
        // Get db access
        $table = $this->getTable();
        // Reset id
        return $table->resetFormId($formId);
    }

    /**
     * Returns the number of created publishers between the given timeframe.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @return ObjectList number of created publishers grouped by year and
     * month
     */
    public function getCreatedPublisherCountByDates($begin, $end) {
        // Get db access
        $table = $this->getTable();
        // Return results
        return $table->getCreatedPublisherCountByDates($begin, $end);
    }

    /**
     * Returns the number of modified publishers between the given timeframe.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @return ObjectList number of modified publishers grouped by year and
     * month
     */
    public function getModifiedPublisherCountByDates($begin, $end) {
        // Get db access
        $table = $this->getTable();
        // Return results
        return $table->getModifiedPublisherCountByDates($begin, $end);
    }

}
