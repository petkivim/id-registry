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
        $data->role_1 = $this->fromStrToArray($data->role_1);
        $data->role_2 = $this->fromStrToArray($data->role_2);
        $data->role_3 = $this->fromStrToArray($data->role_3);
        $data->role_4 = $this->fromStrToArray($data->role_4);
        $data->type = $this->fromStrToArray($data->type);
        $data->fileformat = $this->fromStrToArray($data->fileformat);

        return $data;
    }

    /**
     * Converts the given comma separated string to array.
     */
    private function fromStrToArray($source) {
        if ($source && !is_array($source)) {
            $source = explode(',', $source);
        }
        return $source;
    }

    /**
     * Returns a list of publications without an identifier belonging to the
     * publisher specified by the publisher id.
     * @param integer $publisherId id of the publisher that owns the publications
     * @param string $type publication type, can be "ISBN" or "ISMN"
     * @return object list of publications
     */
    public function getPublicationsWithoutIdentifier($publisherId, $type) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Return result
        return $dao->getPublicationsWithoutIdentifier($publisherId, $type);
    }

    /**
     * Loads the publication format specified by the given id.
     * @param integer $publicationId id of the publication to be fetched
     * @return mixed publication format string or null
     */
    public function getPublicationFormat($publicationId) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Return result
        return $dao->loadPublicationFormat($publicationId);
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
     * Updates publication identified by the given publication id. Only
     * publication identifier(s) and publication identifier type are updated.
     * @param integer $publicationId id of the publication to be updated
     * @param integer $publisherId id of the publisher that owns the publication
     * @param array $identifiers array of new identifier
     * @param string $identifierType type of the identifier, "ISBN" or "ISMN"
     * @param string $publicationFormat publication format
     * @return boolean true on success
     */
    public function updateIdentifiers($publicationId, $publisherId, $identifiers, $identifierType, $publicationFormat) {
        // Check that identifier type is valid
        if (!$this->isValidIdentifierType($identifierType)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLICATION_INVALID_IDENTIFIER_TYPE'));
            return false;
        }
        // Check that publication format is valid
        if (!$this->isValidPublicationFormat($publicationFormat)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLICATION_INVALID_PUBLICATION_FORMAT'));
            return false;
        }
        // Check that publication does not have an identifier yet
        if ($this->hasIdentifier($publicationId)) {
            $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_PUBLICATION_HAS_IDENTIFIER'));
            return false;
        }

        // Get DAO for db access
        $dao = $this->getTable();
        // Return result
        return $dao->updateIdentifiers($publicationId, $publisherId, $identifiers, $identifierType, $publicationFormat);
    }

    /**
     * Validates the given pubalication format. Valid values are "PRINT",
     * "ELECTRONICAL" and "PRINT_ELECTRONICAL".
     * @param string $format publication format to be validated
     * @return boolean true if format is valid; otherwise false
     */
    private function isValidPublicationFormat($format) {
        return preg_match('/^(PRINT|ELECTRONICAL|PRINT_ELECTRONICAL)$/', $format);
    }

    /**
     * Validates the given identifier type. Valid values are "ISBN" and "ISMN".
     * @param string $type identifier type to be validated
     * @return boolean true if type is valid; otherwise false
     */
    private function isValidIdentifierType($type) {
        return preg_match('/^(ISBN|ISMN)$/', $type);
    }

    /**
     * Chacks if the publication identified by the given id has an identifier 
     * yet.
     * @param integer $publicationId id of the publication to be checked
     * @return boolean true if the publication doesn't have an identifier yet;
     * otherwise false
     */
    private function hasIdentifier($publicationId) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Get object
        $publication = $dao->loadPublicationIdentifier($publicationId);

        // If publication_identifier column length is 0, 
        // publication does not have an identifier yet
        if (empty($publication->publication_identifier_print) && empty($publication->publication_identifier_electronical)) {
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
     * Returns an array that contains all the publications own by the publisher
     * spesified by the publisher id that have at least one ISBN identifier.
     * If publisher id is not given, all the publications that have at least
     * one ISBN identifier are returned.
     * @return array array of all the publications that have ISBN
     * identifier
     * @param integer $publisherId id of the publisher that owns the
     * publications
     */
    public function getPublicationsWithIsbnIdentifiers($publisherId = 0) {
        // Get db access
        $table = $this->getTable();
        // Get publications
        return $table->getPublicationsWithIsbnIdentifiers($publisherId);
    }

    /**
     * Delete all publications related to the publisher identified by
     * the given publisher id.
     * @param int $publisherId publisher id
     * @return int number of deleted rows
     */
    public function deleteByPublisherId($publisherId) {
        // Get db access
        $table = $this->getTable();
        // Get publications
        return $table->deleteByPublisherId($publisherId);
    }

    /**
     * Removes both of the identifiers and identifier type replacing them
     * with an empty string.
     * @param integer $publicationId id of the publication to be updated
     * @return boolean true on success, false on failure
     */
    public function removeIdentifiers($publicationId) {
        // Get db access
        $table = $this->getTable();
        // Update
        return $table->removeIdentifiers($publicationId);
    }

}
