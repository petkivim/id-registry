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
 * Publication Table class
 *
 * @since  1.0.0
 */
class IssnRegistryTablePublication extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__issn_registry_publication', 'id', $db);
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
        return parent::store($updateNulls);
    }

    /**
     * Deletes a Publication.
     *
     * @param   integer  $pk  Primary key of the publication to be deleted.
     *
     * @return  boolean  True on success, false on failure.
     *
     */
    public function delete($pk = null) {
        // Item can be deleted only if it doesn't have ISSN yet
        if (!empty($this->issn)) {
            // If identifier exists, raise a warning
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_PUBLICATIONS_DELETE_FAILED'), 'warning');
            // Return false as the item can't be deleted
            return false;
        }
        // No identifiers found, delete the item
        if (parent::delete($pk)) {
            if ($this->form_id > 0) {
                // Load form model
                $formModel = JModelLegacy::getInstance('form', 'IssnregistryModel');
                // Get form 
                $form = $formModel->getItem($this->form_id);
                // Decrease publication count by one
                if (!$formModel->decreasePublicationCount($form->id, $form->publication_count)) {
                    JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_FORM_UPDATE_PUBLICATION_COUNT_FAILED'), 'warning');
                }
            }
            // Load publication archive model
            $publicationArchiveModel = JModelLegacy::getInstance('publicationarchive', 'IssnregistryModel');
            // Delete archive record
            $publicationArchiveModel->deleteByPublicationId($this->id);
            return true;
        }
        return false;
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
        // Conditions for which records should be updated.
        // Issn can be updated if and only if the value is empty, which
        // means that the publication does not have ISSN yet.
        $conditions = array(
            'id' => $publicationId,
            'issn' => ''
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Update identifier
        $this->issn = $issn;
        // Update status
        $this->status = 'WAITING_FOR_CONTROL_COPY';

        // Update object to DB
        return $this->store();
    }

    /**
     * Removes ISSN identifier replacing it with an empty string. If ISSN
     * is empty already, this operation returns false.
     * @param integer $publicationId id of the publication to be updated
     * @return boolean true on success, false on failure
     */
    public function removeIssn($publicationId) {
        // Conditions for which records should be updated.
        $conditions = array(
            'id' => $publicationId
        );

        // Load object
        if (!$this->load($conditions)) {
            return false;
        }

        // Check that issn is not empty already or that status is not
        // 'ISSN_FROZEN'.
        if (empty($this->issn) || strcmp($this->status, 'ISSN_FROZEN') == 0) {
            return false;
        }

        // Update ISSN
        $this->issn = '';
        // Update status
        $this->status = 'NO_PREPUBLICATION_RECORD';

        // Update object to DB
        return $this->store();
    }

    /**
     * Returns a publication mathcing the given id.
     * @param int $publicationId id of the publication
     * @return Publication publication matching the given id
     */
    public function getPublicationById($publicationId) {
        // Database connection
        $query = $this->_db->getQuery(true);
        // Create query
        $query->select('*');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($publicationId));
        $this->_db->setQuery($query);
        // Return result
        return $this->_db->loadObject();
    }

    /**
     * Returns all the publications that are related to the given form id.
     * @param int $formId id of the form
     * @return array publications matching the given form id
     */
    public function getPublicationsByFormId($formId) {
        // Database connection
        $query = $this->_db->getQuery(true);
        // Create query
        $query->select('*');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('form_id') . ' = ' . $this->_db->quote($formId));
        $query->order('title ASC');
        $this->_db->setQuery($query);
        // Return result
        return $this->_db->loadObjectList();
    }

    /**
     * Returns all the publications that are related to the given publisher id.
     * @param int $publisherId id of the publisher
     * @return array publications matching the given publisher id
     */
    public function getPublicationsByPublisherId($publisherId) {
        // Database connection
        $query = $this->_db->getQuery(true);
        // Create query
        $query->select('*');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId));
        $query->order('title ASC');
        $this->_db->setQuery($query);
        // Return result
        return $this->_db->loadObjectList();
    }

    /**
     * Returns an Object List that contains all the publications in the
     * database.
     * @return ObjectList list of all the publications
     */
    public function getPublications() {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->order('title ASC');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Updates all the given publisher id to all the publications that
     * have the given form id.
     * @param int $formId id of the form
     * @param int $publisherId publisher id
     * @return int number of updated rows
     */
    public function updatePublisherId($formId, $publisherId) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $query = $this->_db->getQuery(true);

        // Fields to update.
        $fields = array(
            $this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId),
            $this->_db->quoteName('modified') . ' = ' . $this->_db->quote($date->toSql()),
            $this->_db->quoteName('modified_by') . ' = ' . $this->_db->quote($user->get('username'))
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $this->_db->quoteName('form_id') . ' = ' . $this->_db->quote($formId)
        );
        // Create query
        $query->update($this->_db->quoteName($this->_tbl))->set($fields)->where($conditions);
        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // Return the number of rows that was updated
        return $this->_db->getAffectedRows();
    }

    /**
     * Return the number of publications related to he form identified by the
     * given id that have ISSN.
     * @param int $formId id of the form
     * @return int number of publications related to the given form that have
     * ISSN
     */
    public function getIssnCountByFormId($formId) {
        $query = $this->_db->getQuery(true);

        $conditions = array(
            $this->_db->quoteName('form_id') . ' = ' . $this->_db->quote($formId),
            $this->_db->quoteName('issn') . ' != ' . $this->_db->quote('')
        );

        // Create the query
        $query->select('count(id)')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($conditions);
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadResult();
    }

    /**
     * Returns an array containing ids of publications related to 
     * the form identified by the given id.
     * @param int $formId id of the form
     * @return array an array containing ids of publications related to the
     * given form
     */
    public function getPublicationIdsByFormId($formId) {
        $query = $this->_db->getQuery(true);
        // Create the query
        $query->select('id')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('form_id') . ' = ' . $this->_db->quote($formId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadColumn();
    }

    /**
     * Sets the status of publications related to the forms identified by the
     * given form id to "NO_ISSN_GRANTED".
     * @param int $formId form id
     * @return int number of publications that were updated
     */
    public function setStatusToNoISSNGranted($formId) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $query = $this->_db->getQuery(true);

        // Fields to update.
        $fields = array(
            $this->_db->quoteName('status') . ' = ' . $this->_db->quote('NO_ISSN_GRANTED'),
            $this->_db->quoteName('modified') . ' = ' . $this->_db->quote($date->toSql()),
            $this->_db->quoteName('modified_by') . ' = ' . $this->_db->quote($user->get('username'))
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $this->_db->quoteName('form_id') . ' = ' . $this->_db->quote($formId)
        );
        // Create query
        $query->update($this->_db->quoteName($this->_tbl))->set($fields)->where($conditions);
        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // Return the number of rows that was updated
        return $this->_db->getAffectedRows();
    }

}
