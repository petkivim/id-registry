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
 * Form Table class
 *
 * @since  1.0.0
 */
class IssnRegistryTableForm extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__issn_registry_form', 'id', $db);
    }

    /**
     * Stores a message type.
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

        return parent::store($updateNulls);
    }

    /**
     * Deletes a form.
     *
     * @param   integer  $pk  Primary key of the message type to be deleted.
     *
     * @return  boolean  True on success, false on failure.
     *
     */
    public function delete($pk = null) {
        // TODO: check if this form can be deleted
        // Delete form
        return parent::delete($pk);
    }

    /**
     * Returns the number of forms that are linked to the given publisher.
     * @param int $publisherId id of the publisher
     * @return int number of forms
     */
    public function getFormsCountByPublisherId($publisherId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('count(id)')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadResult();
    }

    /**
     * Sets publisher created attribute to false.
     * @param int $formId id of the form to be updated
     * @return boolean true on success; false on failure
     */
    public function removePublisherCreated($formId) {
        $conditions = array(
            'id' => $formId,
            'publisher_created' => true
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update publisher created
        $this->publisher_created = false;

        // Update object to DB
        return $this->store();
    }

}
