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
 * Identifier batch Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTableIdentifierbatch extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_identifier_batch', 'id', $db);
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

        if (!$this->id) {
            // Get date and user
            $date = JFactory::getDate();
            $user = JFactory::getUser();
            // New item
            $this->created_by = $user->get('username');
            $this->created = $date->toSql();
        }

        return parent::store($updateNulls);
    }

    /**
     * Adds new identifier batch to the database.
     * @param array $params array that contains the parameters
     * @return int id of the new identifier batch entry
     */
    public function addNew($params) {
        // Set values
        $this->identifier_type = $params['identifier_type'];
        $this->identifier_count = $params['identifier_count'];
        $this->publisher_id = $params['publisher_id'];
        $this->publication_id = $params['publication_id'];
        $this->publisher_identifier_range_id = $params['publisher_identifier_range_id'];
        // Add object to db
        if (!$this->store()) {
            return 0;
        }
        return $this->id;
    }

    /**
     * Updates the publication id of the identifier batch identified by
     * the given id.
     * @param int $identifierBatchId batch identifier id
     * @param int $publicationId publication id
     * @return boolean true on success; otherwise false
     */
    public function updatePublicationId($identifierBatchId, $publicationId) {
        // Conditions for which records should be updated.
        $conditions = array(
            'id' => $identifierBatchId
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update publication id
        $this->publication_id = $publicationId;

        // Update object to DB
        return $this->store();
    }

}
