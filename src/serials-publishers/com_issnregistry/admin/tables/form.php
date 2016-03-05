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
        // Get publication model
        $publicationModel = JModelLegacy::getInstance('publication', 'IssnregistryModel');
        if ($pk != null) {
            // Check if there are publications that have ISSN
            if ($publicationModel->getIssnCountByFormId($pk) != 0) {
                // If there are publication with ISSN, the form can't be deleted
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_FORM_DELETE_FAILED_PUBLICATIONS_WITH_ISSN'), 'warning');
                // Return false as the item can't be deleted
                return false;
            }
        }
        // Delete form
        if (parent::delete($pk)) {
            // Get publications related to this form
            $publicationIds = $publicationModel->getPublicationIdsByFormId($pk);
            // Check if there are publications
            if (sizeof($publicationIds) != 0) {
                // Delete the publications
                if (!$publicationModel->delete($publicationIds)) {
                    JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_FORM_DELETE_PUBLICATIONS_FAILED'), 'warning');
                }
            }
            // Get publisher model
            $publisherrModel = JModelLegacy::getInstance('publisher', 'IssnregistryModel');
            // Remove reference to this form
            $publisherrModel->resetFormId($pk);

            // Load form archive model
            $formArchiveModel = JModelLegacy::getInstance('formarchive', 'IssnregistryModel');
            // Delete archive record
            $formArchiveModel->deleteByFormId($this->id);

            // Get message model
            $messageModel = JModelLegacy::getInstance('message', 'IssnregistryModel');
            // Delete messages related to this publisher
            $messageModel->deleteByFormId($pk);

            return true;
        }
        return false;
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

    /**
     * Increases the publication count of the form identified by the given form id
     * by one.
     * @param int $formId form id
     * @param int $oldCount current publication count that's increased by one
     * @return boolean true on success; false on failure
     */
    public function increasePublicationCount($formId, $oldCount) {
        $conditions = array(
            'id' => $formId,
            'publication_count' => $oldCount
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update publication count
        $this->publication_count = $oldCount + 1;

        // Update object to DB
        return $this->store();
    }

    /**
     * Decreases the publication count of the form identified by the given form id
     * by one.
     * @param int $formId form id
     * @param int $oldCount current publication count that's decreased by one
     * @return boolean true on success; false on failure
     */
    public function decreasePublicationCount($formId, $oldCount) {
        $conditions = array(
            'id' => $formId,
            'publication_count' => $oldCount
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update publication count
        $this->publication_count = $oldCount - 1;

        // Update object to DB
        return $this->store();
    }

    /**
     * Increases the publication with ISSN count of the form identified by 
     * the given form id by one.
     * @param int $formId form id
     * @param int $oldCount current publication with ISSN count that's 
     * increased by one
     * @return boolean true on success; false on failure
     */
    public function increasePublicationWithIssnCount($formId, $oldCount) {
        $conditions = array(
            'id' => $formId,
            'publication_count_issn' => $oldCount
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update publication count issn
        $this->publication_count_issn = $oldCount + 1;

        // Update object to DB
        return $this->store();
    }

    /**
     * Decreases the publication with ISSN count of the form identified by 
     * the given form id by one.
     * @param int $formId form id
     * @param int $oldCount current publication with ISSN count that's 
     * decreased by one
     * @return boolean true on success; false on failure
     */
    public function decreasePublicationWithIssnCount($formId, $oldCount) {
        $conditions = array(
            'id' => $formId,
            'publication_count_issn' => $oldCount
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update publication count issn
        $this->publication_count_issn = $oldCount - 1;

        // Update object to DB
        return $this->store();
    }

    /**
     * Adds a refenence to a publisher that was created using the information
     * from the form identified by the given form id.
     * @param int $formId form id
     * @param int $publisherId publisher id
     * @return boolean true on success; false on failure
     */
    public function addCreatedPublisher($formId, $publisherId) {
        $conditions = array(
            'id' => $formId,
            'publisher_id' => 0,
            'publisher_created' => false
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update values
        $this->publisher_id = $publisherId;
        $this->publisher_created = true;

        // Update object to DB
        return $this->store();
    }

    /**
     * Starts a transaction.
     */
    public function transactionStart() {
        $this->_db->transactionStart();
    }

    /**
     * Commits a transaction.
     */
    public function transactionCommit() {
        $this->_db->transactionCommit();
    }

    /**
     * Transaction rollback.
     */
    public function transactionRollback() {
        $this->_db->transactionRollback();
    }

}
