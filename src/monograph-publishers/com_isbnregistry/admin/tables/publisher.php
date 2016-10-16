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
 * Publisher Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTablePublisher extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_publisher', 'id', $db);
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
        if (isset($this->params) && is_array($this->params)) {
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

        // Question 7: from array to comma separated string
        if (is_array($this->question_7)) {
            if (count($this->question_7) > 0) {
                $this->question_7 = implode(',', $this->question_7);
            } else {
                $this->question_7 = '';
            }
        } else {
            $this->question_7 = '';
        }


        return parent::store($updateNulls);
    }

    public function delete($pk = null) {
        if ($pk != null) {
            // Delete publisher Archive
            $publisherArchiveModel = JModelLegacy::getInstance('publisherarchive', 'IsbnregistryModel');
            $publisherArchiveModel->deleteByPublisherId($pk);
            // Delete publications
            $publicationModel = JModelLegacy::getInstance('publication', 'IsbnregistryModel');
            $publicationModel->deleteByPublisherId($pk);
            // Delete ISBN ranges
            $publisherIsbnRangeModel = JModelLegacy::getInstance('publisherisbnrange', 'IsbnregistryModel');
            $publisherIsbnRangeModel->deleteByPublisherId($pk);
            // Delete ISMN ranges
            $publisherIsmnRangeModel = JModelLegacy::getInstance('publisherismnrange', 'IsbnregistryModel');
            $publisherIsmnRangeModel->deleteByPublisherId($pk);
            // Delete messages
            $messageModel = JModelLegacy::getInstance('message', 'IsbnregistryModel');
            $messageModel->deleteByPublisherId($pk);
            // Delete identifier batches
            $identifierBatchModel = JModelLegacy::getInstance('identifierbatch', 'IsbnregistryModel');
            $identifierBatchModel->deleteByPublisherId($pk);
        }
        return parent::delete($pk);
    }

    /**
     * Returns a publisher mathcing the given id.
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
     * Updates the active ISBN identifier of the publisher identified by
     * the given publisher id.
     * @param int $publisherId id of the publisher to be updated
     * @param string $identifier ISBN identifier string
     * @return true on success; false on failure
     */
    public function updateActiveIsbnIdentifier($publisherId, $identifier) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $query = $this->_db->getQuery(true);

        // Update identifier
        $fields = array(
            $this->_db->quoteName('active_identifier_isbn') . ' = ' . $this->_db->quote($identifier),
            $this->_db->quoteName('modified') . ' = ' . $this->_db->quote($date->toSql()),
            $this->_db->quoteName('modified_by') . ' = ' . $this->_db->quote($user->get('username'))
        );

        // Conditions 
        $conditions = array(
            $this->_db->quoteName('id') . ' = ' . $this->_db->quote($publisherId)
        );

        // Create query
        $query->update($this->_db->quoteName($this->_tbl))->set($fields)->where($conditions);
        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // If number of affected rows is 1, the result is OK
        if ($this->_db->getAffectedRows() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Updates the active ISMN identifier of the publisher identified by
     * the given publisher id.
     * @param int $publisherId id of the publisher to be updated
     * @param string $identifier ISMN identifier string
     * @return true on success; false on failure
     */
    public function updateActiveIsmnIdentifier($publisherId, $identifier) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $query = $this->_db->getQuery(true);

        // Update identifier
        $fields = array(
            $this->_db->quoteName('active_identifier_ismn') . ' = ' . $this->_db->quote($identifier),
            $this->_db->quoteName('modified') . ' = ' . $this->_db->quote($date->toSql()),
            $this->_db->quoteName('modified_by') . ' = ' . $this->_db->quote($user->get('username'))
        );

        // Conditions 
        $conditions = array(
            $this->_db->quoteName('id') . ' = ' . $this->_db->quote($publisherId)
        );

        // Create query
        $query->update($this->_db->quoteName($this->_tbl))->set($fields)->where($conditions);
        $this->_db->setQuery($query);
        // Execute query
        $result = $this->_db->execute();
        // If number of affected rows is 1, the result is OK
        if ($this->_db->getAffectedRows() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Returns a list of publishers and publisher ISBN identifiers that were
     * created or modified between begin date and end date.
     * If publisher has multiple identifiers, the publisher is included in the
     * list multiple times.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @return list of publishers
     */
    public function getPublishersAndIsbnIdentifiers($begin, $end) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('*');
        $query->from($this->_db->quoteName($this->_tbl) . ' AS p');
        $query->join('INNER', '#__isbn_registry_publisher_isbn_range AS pir ON p.id = pir.publisher_id');
        $query->order('p.official_name ASC');
        $query->where('(' .
                $this->_db->quoteName('p.created') . ' >= ' . $this->_db->quote($begin->toSql()) . ' AND ' .
                $this->_db->quoteName('p.created') . ' <= ' . $this->_db->quote($end->toSql()) . ') OR (' .
                $this->_db->quoteName('p.modified') . ' >= ' . $this->_db->quote($begin->toSql()) . ' AND ' .
                $this->_db->quoteName('p.modified') . ' <= ' . $this->_db->quote($end->toSql()) .
                ')');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Returns a list of publishers that belong to the given categories,
     * match the has quitted condition and are of the given type (isbn/ismn). 
     * @param array $categories allowed categories
     * @param boolean $hasQuitted has the publisher quitted
     * @param string $type publisher's type: isbn or ismn
     * @return ObjectList list of publishers matching the conditions
     */
    public function getPublishersByCategory($categories, $hasQuitted, $type) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Array length must be five
        for ($i = sizeof($categories); $i < 5; $i++) {
            $categories[$i] = 0;
        }

        // Set conditions
        $conditions = array();

        if (!$hasQuitted) {
            array_push($conditions, $this->_db->quoteName('p.has_quitted') . ' = ' . $this->_db->quote($hasQuitted));
        }
        // Create the query
        $query->select('p.*, max(pir.id) as range_id');
        $query->from($this->_db->quoteName($this->_tbl) . ' AS p');
        if (strcmp($type, 'isbn') == 0) {
            $query->join('INNER', '#__isbn_registry_publisher_isbn_range AS pir ON p.id = pir.publisher_id');
        } else {
            $query->join('INNER', '#__isbn_registry_publisher_ismn_range AS pir ON p.id = pir.publisher_id');
        }
        $query->where('(' .
                $this->_db->quoteName('pir.category') . ' = ' . $this->_db->quote($categories[0]) . ' OR ' .
                $this->_db->quoteName('pir.category') . ' = ' . $this->_db->quote($categories[1]) . ' OR ' .
                $this->_db->quoteName('pir.category') . ' = ' . $this->_db->quote($categories[2]) . ' OR ' .
                $this->_db->quoteName('pir.category') . ' = ' . $this->_db->quote($categories[3]) . ' OR ' .
                $this->_db->quoteName('pir.category') . ' = ' . $this->_db->quote($categories[4]) .
                ')');
        $query->where($conditions);
        $query->group('p.id');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Returns a list of publishers with the id of the latest publisher
     * identifier.
     * @param string $type publisher's type: isbn or ismn
     * @return ObjectList list of publishers matching the conditions
     */
    public function getPublishersLatestIdentifierId($type) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);
        // Create the query
        $query->select('p.id, max(pir.id) as range_id');
        $query->from($this->_db->quoteName($this->_tbl) . ' AS p');
        if (strcmp($type, 'isbn') == 0) {
            $query->join('INNER', '#__isbn_registry_publisher_isbn_range AS pir ON p.id = pir.publisher_id');
        } else {
            $query->join('INNER', '#__isbn_registry_publisher_ismn_range AS pir ON p.id = pir.publisher_id');
        }
        $query->group('p.id');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Loads the has quitted value of the publisher identified by the given
     * publisher id.
     * @param integer $publisherId id of the publisher
     * @return boolean true if publisher has quitted, otherwise false
     */
    public function hasQuitted($publisherId) {
        // Database connection
        $query = $this->_db->getQuery(true);
        // Create query
        $query->select('has_quitted');
        $query->from($this->_db->quoteName($this->_tbl));
        $query->where($this->_db->quoteName('id') . ' = ' . $this->_db->quote($publisherId));
        $this->_db->setQuery($query);
        // Return result
        return $this->_db->loadResult();
    }

    /**
     * Returns the number of modified publishers between the given timeframe.
     * Only one modification per publisher is calculated.
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
            $this->_db->quoteName('p.modified') . ' >= ' . $this->_db->quote($begin->toSql()),
            $this->_db->quoteName('p.modified') . ' <= ' . $this->_db->quote($end->toSql()),
            '(isbn.publisher_identifier != "" OR ismn.publisher_identifier != "")'
        );
        // Create the query
        $query->select('YEAR(p.modified) as year, MONTH(p.modified) as month, count(distinct p.id) as count');
        $query->from($this->_db->quoteName($this->_tbl) . ' as p');
        $query->join('LEFT', '#__isbn_registry_publisher_isbn_range AS isbn ON p.id = isbn.publisher_id');
        $query->join('LEFT', '#__isbn_registry_publisher_ismn_range AS ismn ON p.id = ismn.publisher_id');
        $query->where($conditions);
        // Group by year and month
        $query->group('YEAR(p.modified), MONTH(p.modified)');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Returns the number of created publishers between the given timeframe.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @param boolean $ismn is ismn publisher
     * @return ObjectList number of created publishers grouped by year and
     * month
     */
    public function getCreatedPublisherCountByDates($begin, $end, $ismn = false) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Conditions
        $conditions = array(
            $this->_db->quoteName('p.created') . ' >= ' . $this->_db->quote($begin->toSql()),
            $this->_db->quoteName('p.created') . ' <= ' . $this->_db->quote($end->toSql())
        );
        // Create the query
        $query->select('YEAR(p.created) as year, MONTH(p.created) as month, count(distinct p.id) as count');
        $query->from($this->_db->quoteName($this->_tbl) . ' as p');
        if (!$ismn) {
            $query->join('INNER', '#__isbn_registry_publisher_isbn_range AS isbn ON p.id = isbn.publisher_id');
        } else {
            $query->join('INNER', '#__isbn_registry_publisher_ismn_range AS ismn ON p.id = ismn.publisher_id');
        }
        $query->where($conditions);
        // Group by year and month
        $query->group('YEAR(p.created), MONTH(p.created)');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

    /**
     * Returns the number of self registered publishers between the given 
     * timeframe.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @return ObjectList number of self registered publishers grouped by year 
     * and month
     */
    public function getSelfRegisteredPublisherCountByDates($begin, $end) {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Conditions
        $conditions = array(
            $this->_db->quoteName('p.created') . ' >= ' . $this->_db->quote($begin->toSql()),
            $this->_db->quoteName('p.created') . ' <= ' . $this->_db->quote($end->toSql()),
            $this->_db->quoteName('p.created_by') . ' = ' . $this->_db->quote('WWW')
        );

        // Create the query
        $query->select('YEAR(p.created) as year, MONTH(p.created) as month, count(distinct p.id) as count');
        $query->from($this->_db->quoteName($this->_tbl) . ' as p');

        $query->where($conditions);
        // Group by year and month
        $query->group('YEAR(p.created), MONTH(p.created)');
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

}
