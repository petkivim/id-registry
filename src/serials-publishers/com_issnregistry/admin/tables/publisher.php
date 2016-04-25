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
 * Publisher Table class
 *
 * @since  1.0.0
 */
class IssnRegistryTablePublisher extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__issn_registry_publisher', 'id', $db);
    }

    /**
     * Stores a publisher.
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

    public function delete($pk = null) {
        // Get form model
        $formModel = JModelLegacy::getInstance('form', 'IssnregistryModel');
        if ($pk != null) {
            // Get number of forms related to this publisher
            $formsCount = $formModel->getFormsCountByPublisherId($pk);
            // Check result
            if ($formsCount != 0) {
                // If there are forms, the publisher can't be deleted
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_PUBLISHER_DELETE_FAILED_FORMS_EXIST'), 'warning');
                // Return false as the item can't be deleted
                return false;
            }
            // Delete messages
            //$messageModel = JModelLegacy::getInstance('message', 'IssnregistryModel');
            //$messageModel->deleteByPublisherId($pk);
        }
        if (parent::delete($pk)) {
            // If this publisher was created based on a form, remove 
            // publisher created attribute from the form
            if ($this->form_id != 0) {
                $formModel->removePublisherCreated($this->form_id);
            }
            // Get message model
            $messageModel = JModelLegacy::getInstance('message', 'IssnregistryModel');
            // Delete messages related to this publisher
            $messageModel->deleteByPublisherId($pk);
            // Return true
            return true;
        }
        return false;
    }

    /**
     * Returns a publisher matching the given id.
     * @param int $publisherId id of the publisher
     * @return Publisher publisher matching the given id
     */
    public function getPublisherById($publisherId) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($publisherId));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObject();
    }

    /**
     * Returns an Object List that contains all the publishers in the
     * database.
     * @return ObjectList list of all the publishers
     */
    public function getPublishers() {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->order('official_name ASC');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Returns a publisher identified by the given form id.
     * @param int $formId id of the form
     * @return Publisher publisher matching the given form id
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
     * Removes reference to the form identified by the given id by setting
     * form id to zero.
     * @param int $formId id of the form
     * @return int number of affected rows
     */
    public function resetFormId($formId) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $query = $this->_db->getQuery(true);

        // Fields to update.
        $fields = array(
            $this->_db->quoteName('form_id') . ' = ' . $this->_db->quote(0),
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
     * Returns the number of created publishers between the given timeframe.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @return ObjectList number of created publishers grouped by year and
     * month
     */
    public function getCreatedPublisherCountByDates($begin, $end) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Conditions
        $conditions = array(
            $this->_db->quoteName('created') . ' >= ' . $this->_db->quote($begin->toSql()),
            $this->_db->quoteName('created') . ' <= ' . $this->_db->quote($end->toSql())
        );
        // Create the query
        $query->select('YEAR(created) as year, MONTH(created) as month, count(distinct id) as count');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($conditions);
        // Group by year and month
        $query->group('YEAR(created), MONTH(created)');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Returns the number of modified publishers between the given timeframe.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @return ObjectList number of modified publishers grouped by year and
     * month
     */
    public function getModifiedPublisherCountByDates($begin, $end) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Conditions
        $conditions = array(
            $this->_db->quoteName('modified') . ' >= ' . $this->_db->quote($begin->toSql()),
            $this->_db->quoteName('modified') . ' <= ' . $this->_db->quote($end->toSql())
        );
        // Create the query
        $query->select('YEAR(modified) as year, MONTH(modified) as month, count(distinct id) as count');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($conditions);
        // Group by year and month
        $query->group('YEAR(modified), MONTH(modified)');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

}
