<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/abstractpublisheridentifierrange.php';

/**
 * Publisher ISMN Range Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelPublisherismnrange extends IsbnregistryModelAbstractPublisherIdentifierRange {

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
    public function getTable($type = 'Publisherismnrange', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.publisherismnrange', 'publisherismnrange', array(
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
                'com_isbnregistry.edit.publisherismnrange.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Returns the name of the identifier range model
     * @return string "isbnrange"
     */
    public function getRangeModelName() {
        return "ismnrange";
    }

    /**
     * Returns the id of the given publisher ISMN range object.
     * @param object $publisherRange Publisher ISMN Range object 
     * @return integer id of the given object
     */
    public function getRangeId($publisherRange) {
        return $publisherRange->ismn_range_id;
    }

    /**
     * Counts the digit for the given ISBN number. Works with both 
     * ISBN-10 and ISBN-13.
     * @param string $identifier ISBN which check digit needs to be calculated
     * @return character check digit of the given ISBN
     */
    public function getCheckDigit($identifier) {
        // Include ISBN helper class
        require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/helpers/publisherisbnrange.php';
        // Calculate check digit - use same algorithm that's used with ISBN-13
        return PublishersisbnrangeHelper::countIsbnCheckDigit($identifier);
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
