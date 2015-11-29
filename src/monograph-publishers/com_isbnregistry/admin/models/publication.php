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

/**
 * Publication Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelPublication extends JModelAdmin {

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
    public function getTable($type = 'Publication', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.publication', 'publication', array(
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
                'com_isbnregistry.edit.publication.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        // From comma separated string to array
		$data->role_1 = IsbnregistryModelPublication::fromStrToArray($data->role_1);
		$data->role_2 = IsbnregistryModelPublication::fromStrToArray($data->role_2);
		$data->role_3 = IsbnregistryModelPublication::fromStrToArray($data->role_3);
		$data->role_4 = IsbnregistryModelPublication::fromStrToArray($data->role_4);
		$data->type = IsbnregistryModelPublication::fromStrToArray($data->type);
		$data->fileformat = IsbnregistryModelPublication::fromStrToArray($data->fileformat);
		
        return $data;
    }

	/**
	 * Converts the given comma separated string to array.
	 */
	private static function fromStrToArray($source) {
        if ($source && !is_array($source)) {
            $source = explode(',', $source);
        }		
		return $source;
	}
}
