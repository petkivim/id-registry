<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Publication Archive Table class
 *
 * @since  1.0.0
 */
class IssnRegistryTablePublicationarchive extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__issn_registry_publication_archive', 'id', $db);
    }

        /**
     * Stores a publication archive.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   1.6
     */
    public function store($updateNulls = false) {
    
        // Publication archive record is created only when a new publication
        // record is created on the public WWW sit.
        return false;
    }
    
    /**
     * Returns a publication archive object related to the publication identified
     * by the given publication id.
     * @param int $publicationId id of the publication
     * @return PublicationArchive publication archive object
     */
    public function getByPublicationId($publicationId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('publication_id') . ' = ' . $this->_db->quote($publicationId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObject();
    }

    /**
     * Delete publication archive related to the publication identified by
     * the given publication id.
     * @param int $publicationId publication id
     * @return int number of deleted rows
     */
    public function deleteByPublicationId($publicationId) {
        $query = $this->_db->getQuery(true);

        // Delete all publication archives related to the publication
        $conditions = array(
            $this->_db->quoteName('publication_id') . ' = ' . $this->_db->quote($publicationId)
        );

        $query->delete($this->_db->quoteName($this->_tbl));
        $query->where($conditions);

        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // Return the number of deleted rows
        return $this->_db->getAffectedRows();
    }

}
