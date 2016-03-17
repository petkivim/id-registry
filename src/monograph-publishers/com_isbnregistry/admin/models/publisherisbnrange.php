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
 * Publisher ISBN Range Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelPublisherisbnrange extends IsbnregistryModelAbstractPublisherIdentifierRange {

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
    public function getTable($type = 'Publisherisbnrange', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.publisherisbnrange', 'publisherisbnrange', array(
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
                'com_isbnregistry.edit.publisherisbnrange.data', array()
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
        return "isbnrange";
    }

    /**
     * Returns the id of the given publisher ISBN range object.
     * @param object $publisherRange Publisher ISBN Range object 
     * @return integer id of the given object
     */
    public function getRangeId($publisherRange) {
        return $publisherRange->isbn_range_id;
    }

    /**
     * Counts the digit for the given ISBN number. Works with both 
     * ISBN-10 and ISBN-13.
     * @param string $identifier ISBN which check digit needs to be calculated
     * @return character check digit of the given ISBN
     */
    public function getCheckDigit($identifier) {
        // Include helper class
        require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/helpers/publisherisbnrange.php';
        // Calculate check digit
        return PublishersisbnrangeHelper::countIsbnCheckDigit($identifier);
    }

    /**
     * Updates the active ISBN identifier of the publisher identified by
     * the given publisher id.
     * @param int $publisherId id of the publisher to be updated
     * @param string $identifier ISBN identifier string
     */
    public function updateActiveIdentifier($publisherId, $identifier) {
        // Load publisher model
        $publisherModel = JModelLegacy::getInstance('publisher', 'IsbnregistryModel');
        // Update identifier
        return $publisherModel->updateActiveIsbnIdentifier($publisherId, $identifier);
    }

    /**
     * Returns the total length of the variable part of the identifier which
     * is 6.
     * @return int total length of the variable part of the identifier which
     * is 6
     */
    public function getIdentifierVarTotalLength() {
        return 6;
    }

}
