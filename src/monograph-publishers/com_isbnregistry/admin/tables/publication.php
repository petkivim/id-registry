<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Publication Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTablePublication extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_publication', 'id', $db);
    }

    /**
     * Stores a publication.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   1.6
     */
    public function store($updateNulls = false) {
        // Transform the params field
        if (is_array($this->params)) {
            $registry = new Registry;
            $registry->loadArray($this->params);
            $this->params = (string) $registry;
        }

        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        if ($this->id) {
            // Existing item
            $this->modified_by = $user->get('username');
            $this->modified = $date->toSql();
        } else {
            // New item
            $this->created_by = $user->get('username');
            $this->created = $date->toSql();
        }

        // From array to comma separated string
        $this->role_1 = IsbnRegistryTablePublication::fromArrayToStr($this->role_1);
        $this->role_2 = IsbnRegistryTablePublication::fromArrayToStr($this->role_2);
        $this->role_3 = IsbnRegistryTablePublication::fromArrayToStr($this->role_3);
        $this->role_4 = IsbnRegistryTablePublication::fromArrayToStr($this->role_4);
        $this->type = IsbnRegistryTablePublication::fromArrayToStr($this->type);
        $this->fileformat = IsbnRegistryTablePublication::fromArrayToStr($this->fileformat);

        return parent::store($updateNulls);
    }

    /**
     * Converts the given array to comma separated string.
     */
    private static function fromArrayToStr($source) {
        if (is_array($source)) {
            if (count($source) > 0) {
                $source = implode(',', $source);
            } else {
                $source = '';
            }
        } else {
            $source = '';
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
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Conditions for which records should be fetched
        $conditions = array(
            $this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId),
            $this->_db->quoteName('no_identifier_granted') . ' = ' . $this->_db->quote(false),
            $this->_db->quoteName('publication_identifier_print') . ' = ' . $this->_db->quote(''),
            $this->_db->quoteName('publication_identifier_electronical') . ' = ' . $this->_db->quote('')
        );

        // Add conditions related to publication type
        if (strcasecmp($type, 'isbn') == 0) {
            array_push($conditions, $this->_db->quoteName('publication_type') . ' != ' . $this->_db->quote('SHEET_MUSIC'));
        } else if (strcasecmp($type, 'ismn') == 0) {
            array_push($conditions, $this->_db->quoteName('publication_type') . ' = ' . $this->_db->quote('SHEET_MUSIC'));
        }

        // Create the query
        $query->select('id, title')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($conditions)
                ->order('title ASC');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
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
        // Conditions for which records should be updated.
        $conditions = array(
            'id' => $publicationId,
            'publisher_id' => $publisherId
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update identifier(s)
        if (strcmp($publicationFormat, 'PRINT') == 0) {
            $this->publication_identifier_print = $identifiers[0];
        } else if (strcmp($publicationFormat, 'ELECTRONICAL') == 0) {
            $this->publication_identifier_electronical = $identifiers[0];
        } else if (strcmp($publicationFormat, 'PRINT_ELECTRONICAL') == 0) {
            $this->publication_identifier_print = $identifiers[0];
            $this->publication_identifier_electronical = $identifiers[1];
        }
        
        // Update identifier type
        $this->publication_identifier_type = $identifierType;

        // Update object to DB
        return $this->store();
    }

    /**
     * Loads publication identifiers specified by the given id.
     * @param integer $publicationId id of the publication to be fetched
     * @return Publication publication object holding 
     * "publication_identifier_print" and "publication_identifier_electronical"
     * attributes
     */
    public function loadPublicationIdentifier($publicationId) {
        // Database connection
        $query = $this->_db->getQuery(true);
        // Create query
        $query->select('publication_identifier_print, publication_identifier_electronical');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($publicationId));
        $this->_db->setQuery($query);
        // Return result
        return $this->_db->loadObject();
    }

    /**
     * Loads the publication format specified by the given id.
     * @param integer $publicationId id of the publication to be fetched
     * @return mixed publication format string or null
     */
    public function loadPublicationFormat($publicationId) {
        // Database connection
        $query = $this->_db->getQuery(true);
        // Create query
        $query->select('publication_format');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($publicationId));
        $this->_db->setQuery($query);
        // Return result
        return $this->_db->loadResult();
    }

}
