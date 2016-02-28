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
 * Publication Archive Model
 *
 * @since  1.0.0
 */
class IssnregistryModelPublicationarchive extends JModelAdmin {

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
    public function getTable($type = 'Publicationarchive', $prefix = 'IssnregistryTable', $config = array()) {
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
                'com_issnregistry.publicationarchive', 'publicationarchive', array(
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
                'com_issnregistry.edit.publicationarchive.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Returns a publication archive object related to the publication identified
     * by the given publication id.
     * @param int $publicationId id of the publication
     * @return PublicationArchive publication archive object
     */
    public function getByPublicationId($publicationId) {
        // Get db access
        $table = $this->getTable();
        // Get publication
        return $table->getByPublicationId($publicationId);
    }

    /**
     * Delete publication archive related to the publication identified by
     * the given publication id.
     * @param int $publicationId publication id
     * @return int number of deleted rows
     */
    public function deleteByPublicationId($publicationId) {
        // Get db access
        $table = $this->getTable();
        // Get publication
        return $table->deleteByPublicationId($publicationId);
    }

}
