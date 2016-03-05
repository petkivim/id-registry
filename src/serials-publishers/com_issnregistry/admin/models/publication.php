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
 * Publication Model
 *
 * @since  1.0.0
 */
class IssnregistryModelPublication extends JModelAdmin {

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
    public function getTable($type = 'Publication', $prefix = 'IssnregistryTable', $config = array()) {
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
                'com_issnregistry.publication', 'publication', array(
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
                'com_issnregistry.edit.publication.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Returns a publication mathcing the given id.
     * @param int $publicationId id of the publication
     * @return Publication publication matching the given id
     */
    public function getPublicationById($publicationId) {
        // Get db access
        $table = $this->getTable();
        // Get publication
        return $table->getPublicationById($publicationId);
    }

    /**
     * Returns all the publications that are related to the given form id.
     * @param int $formId id of the form
     * @return array publications matching the given form id
     */
    public function getPublicationsByFormId($formId) {
        // Get db access
        $table = $this->getTable();
        // Get publications
        return $table->getPublicationsByFormId($formId);
    }

    /**
     * Returns all the publications that are related to the given publisher id.
     * @param int $publisherId id of the publisher
     * @return array publications matching the given publisher id
     */
    public function getPublicationsByPublisherId($publisherId) {
        // Get db access
        $table = $this->getTable();
        // Get publications
        return $table->getPublicationsByPublisherId($publisherId);
    }

    /**
     * Updates all the given publisher id to all the publications that
     * have the given form id.
     * @param int $formId id of the form
     * @param int $publisherId publisher id
     * @return int number of publications to be updated
     */
    public function updatePublisherId($formId, $publisherId) {
        // Get db access
        $table = $this->getTable();
        // Update publications
        return $table->updatePublisherId($formId, $publisherId);
    }

    /**
     * Updates publication identified by the given publication id. Only
     * publication ISSN is updated. ISSN can be updated if and only if the
     * publication does not currently have ISSN.
     * @param integer $publicationId id of the publication to be updated
     * @param string $issn ISSN identifier string
     * @return boolean true on success
     */
    public function updateIssn($publicationId, $issn) {
        // Check that publication does not have an identifier yet
        if ($this->hasIdentifier($publicationId)) {
            $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_PUBLICATION_HAS_IDENTIFIER'));
            return false;
        }

        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->updateIssn($publicationId, $issn);
    }

    /**
     * Chacks if the publication identified by the given id has an identifier 
     * yet.
     * @param integer $publicationId id of the publication to be checked
     * @return boolean true if the publication doesn't have an identifier yet;
     * otherwise false
     */
    private function hasIdentifier($publicationId) {
        // Get db access
        $table = $this->getTable();
        // Get object
        $publication = $table->getPublicationById($publicationId);

        // If issn column length is 0, 
        // publication does not have an identifier yet
        if (empty($publication->issn)) {
            return false;
        }
        return true;
    }

    /**
     * Returns an array that contains publication id and title as key value
     * pairs.
     * @return array publication id and title as key value pairs
     */
    public function getPublicationsArray() {
        // Get db access
        $table = $this->getTable();
        // Get publications
        $publications = $table->getPublications();
        // Check result
        if (!$publications) {
            return array();
        }
        $result = array();
        foreach ($publications as $publication) {
            $result[$publication->id] = $publication->title;
        }
        return $result;
    }

    /**
     * Removes ISSN identifier replacing it with an empty string.
     * @param integer $publicationId id of the publication to be updated
     * @return boolean true on success, false on failure
     */
    public function removeIssn($publicationId) {
        // Get db access
        $table = $this->getTable();
        // Update
        return $table->removeIssn($publicationId);
    }

    /**
     * Return the number of publications related to he form identified by the
     * given id that have ISSN.
     * @param int $formId id of the form
     * @return int number of publications related to the given form that have
     * ISSN
     */
    public function getIssnCountByFormId($formId) {
        // Get db access
        $table = $this->getTable();
        // Get publication count
        return $table->getIssnCountByFormId($formId);
    }

    /**
     * Returns an array containing ids of publications related to 
     * the form identified by the given id.
     * @param int $formId id of the form
     * @return array an array containing ids of publications related to the
     * given form
     */
    public function getPublicationIdsByFormId($formId) {
        // Get db access
        $table = $this->getTable();
        // Get publication ids
        return $table->getPublicationIdsByFormId($formId);
    }

}
