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

require_once __DIR__ . '/abstractidentifierrange.php';

/**
 * ISBN Range Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelIsbnrange extends IsbnregistryModelAbstractIdentifierRange {

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
    public function getTable($type = 'Isbnrange', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.isbnrange', 'isbnrange', array(
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
                'com_isbnregistry.edit.isbnrange.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Method to format the publisher identifier including prefix, language
     * group and publisher code "xxx-xxx-xxxxx".
     * 
     * @param identifierrange $identifierrange object that holds prefix 
     * and optional language code
     * @param int $publisherIdentifier publisher code
     * @return string publisher identifier
     */
    public function formatPublisherIdentifier($identifierrange, $publisherIdentifier) {
        $id = $identifierrange->prefix > 0 ? $identifierrange->prefix . '-' : '';
        $id .= $identifierrange->lang_group . '-' . $publisherIdentifier;
        return $id;
    }

    /**
     * Return the name of the publisher range model. In this case
     * "publisherisbnrange".
     * @return string "publisherisbnrange"
     */
    public function getPublisherRangeModelName() {
        return 'publisherisbnrange';
    }
}
