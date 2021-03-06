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

require_once __DIR__ . '/abstractidentifierrange.php';

/**
 * ISMN Range Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelIsmnrange extends IsbnregistryModelAbstractIdentifierRange {

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
    public function getTable($type = 'Ismnrange', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.ismnrange', 'ismnrange', array(
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
                'com_isbnregistry.edit.ismnrange.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Method to format the publisher identifier including prefix
     * and publisher code "xxx-xxxxxxx".
     * 
     * @param ismnrange $identifierrange object that holds prefix
     * @param int $publisherIdentifier publisher code
     * @return string publisher identifier
     */
    public function formatPublisherIdentifier($identifierrange, $publisherIdentifier) {
        $id = $identifierrange->prefix . '-' . $publisherIdentifier;
        return $id;
    }

    /**
     * Return the name of the publisher range model. In this case
     * "publisherismnrange".
     * @return string "publisherismnrange"
     */
    public function getPublisherRangeModelName() {
        return 'publisherismnrange';
    }

    /**
     * Updates the active ISMN identifier of the publisher identified by
     * the given publisher id.
     * @param int $publisherId id of the publisher to be updated
     * @param string $identifier ISMN identifier string
     */
    public function updateActiveIdentifier($publisherId, $identifier) {
        // Load publisher model
        $publisherModel = JModelLegacy::getInstance('publisher', 'IsbnregistryModel');
        // Update identifier
        return $publisherModel->updateActiveIsmnIdentifier($publisherId, $identifier);
    }

}
