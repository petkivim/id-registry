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
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->loadPublicationFormat($publicationId);
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

        // Convert identifiers print to JSON
        $identifiersPrint = $this->identifiersPrintToJSON($identifiers);
        // Convert identifiers electronical to JSON
        $identifiersElectronical = $this->identifiersElectronicalToJSON($identifiers);

        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->updateIdentifiers($publicationId, $publisherId, $identifiersPrint, $identifiersElectronical, $identifierType, $publicationFormat);
    }

    /**
     * Removes the given identifier from the publication identified by the
     * given id.
     * @param int $publicationId publication id
     * @param int $identifier identifier to be removed
     * @return boolean true on success; false on failure
     */
    public function removeIdentifier($publicationId, $identifier) {
        // Get publication
        $publication = $this->getItem($publicationId);
        // Check that we have a result
        if (!$publication) {
            return false;
        }
        // Get print identifiers as array
        $identifiersPrintJson = json_decode($publication->publication_identifier_print, true);
        // Remove the identifier
        unset($identifiersPrintJson[$identifier]);
        // Get print identifiers as sting
        $identifiersPrintString = empty($identifiersPrintJson) ? '' : json_encode($identifiersPrintJson);
        // Get electronical identifiers as array
        $identifiersElectronicalJson = json_decode($publication->publication_identifier_electronical, true);
        // Remove the identifier
        unset($identifiersElectronicalJson[$identifier]);
        // Get electronical identifiers as sting
        $identifiersElectronicalString = empty($identifiersElectronicalJson) ? '' : json_encode($identifiersElectronicalJson);
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->updateIdentifiers($publicationId, $publication->publisher_id, $identifiersPrintString, $identifiersElectronicalString, $publication->publication_identifier_type, $publication->publication_format);
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
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @param integer $publisherId id of the publisher that owns the
     * publications
     * @return array array of all the publications that have ISBN
     * identifier
     */
    public function getPublicationsWithIsbnIdentifiers($begin, $end, $publisherId = 0) {
        // Get db access
        $table = $this->getTable();
        // Get publications
        return $table->getPublicationsWithIsbnIdentifiers($begin, $end, $publisherId);
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

    /**
     * Creates a comma separated string that contains identifiers of both
     * printed and electronical publications.
     * @param string $printIdentifiers print identifiers strign
     * @param string $electronicalIdentifiers electronical identifiers string
     * @return string comma separated string that contains identifiers of both
     * printed and electronical publications
     */
    public function getIdentifiersString($printIdentifiers, $electronicalIdentifiers) {
        $identifiers = array();
        $json = json_decode($printIdentifiers);
        if (!empty($json)) {
            foreach ($json as $identifier => $type) {
                array_push($identifiers, $identifier . ' (' . JText::_('COM_ISBNREGISTRY_PUBLICATION_JSON_TYPE_' . $type) . ')');
            }
        }
        $json = json_decode($electronicalIdentifiers);
        if (!empty($json)) {
            foreach ($json as $identifier => $type) {
                array_push($identifiers, $identifier . ' (' . JText::_('COM_ISBNREGISTRY_PUBLICATION_JSON_TYPE_' . $type) . ')');
            }
        }
        if (!empty($identifiers)) {
            return implode(', ', $identifiers);
        } else {
            return '-';
        }
    }

    /**
     * Returns the number of received identifier applications between the given 
     * timeframe.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @param boolean $music if true the number of sheet music applications
     * is returned, otherwise the number of all the other applications is
     * returned
     * @return ObjectList number of received identifier applications grouped by 
     * year and month
     */
    public function getIdentifierApplicationCountByDates($begin, $end, $music = false) {
        // Get db access
        $table = $this->getTable();
        // Return results
        return $table->getIdentifierApplicationCountByDates($begin, $end, $music);
    }

    private function identifiersPrintToJSON($identifiers) {
        $types = array('PAPERBACK', 'HARDBACK', 'SPIRAL_BINDING', 'OTHER_PRINT');
        $json = array();
        foreach ($identifiers as $identifier => $type) {
            if (in_array($type, $types)) {
                $json[$identifier] = $type;
            }
        }
        if (empty($json)) {
            return '';
        }
        return json_encode($json);
    }

    private function identifiersElectronicalToJSON($identifiers) {
        $types = array('PDF', 'EPUB', 'CD_ROM', 'OTHER');
        $json = array();
        foreach ($identifiers as $identifier => $type) {
            if (in_array($type, $types)) {
                $json[$identifier] = $type;
            }
        }
        if (empty($json)) {
            return '';
        }
        return json_encode($json);
    }

}
