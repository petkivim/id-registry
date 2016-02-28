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
 * Form Archive Table class
 *
 * @since  1.0.0
 */
class IssnRegistryTableFormarchive extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__issn_registry_form_archive', 'id', $db);
    }

        /**
     * Stores a form archive.
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   1.6
     */
    public function store($updateNulls = false) {
    
        // Form archive record is created only when a new form
        // record is created on the public WWW sit.
        return false;
    }
    
    /**
     * Returns a form archive object related to the form identified
     * by the given form id.
     * @param int $formId id of the form
     * @return FormArchive form archive object
     */
    public function getByFormId($formId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('form_id') . ' = ' . $this->_db->quote($formId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObject();
    }

    /**
     * Delete form archive related to the form identified by
     * the given form id.
     * @param int $formId form id
     * @return int number of deleted rows
     */
    public function deleteByFormId($formId) {
        $query = $this->_db->getQuery(true);

        // Delete all form archives related to the form
        $conditions = array(
            $this->_db->quoteName('form_id') . ' = ' . $this->_db->quote($formId)
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
