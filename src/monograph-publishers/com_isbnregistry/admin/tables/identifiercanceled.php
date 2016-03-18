<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Identifier Canceled Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTableIdentifiercanceled extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_identifier_canceled', 'id', $db);
    }

    /**
     * Stores a canceled identifier.
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
            $this->canceled_by = $user->get('username');
            $this->canceled = $date->toSql();
        }

        return parent::store($updateNulls);
    }

    /**
     * Returns the smallest canceled identifier objects (fifo) that belong to 
     * the given category and publisher, presents the given type and that 
     * was given from the range identified by the given id. If id is not 
     * defined, the smallest identifiers from any range are returned.
     * @param int $category category identifier category
     * @param int $publisherId publisher id
     * @param string $identifierType ISBN or ISMN
     * @param int $count number of identifiers
     * @param int $rangeId identifier range id
     * @return string smallest identifier that was given from the 
     * identifier range identified by the given id
     */
    public function getIdentifiers($category, $publisherId, $identifierType, $count, $rangeId = 0) {
        // Get query
        $query = $this->_db->getQuery(true);

        $conditions = array(
            $this->_db->quoteName('category') . ' = ' . $this->_db->quote($category),
            $this->_db->quoteName('publisher_id') . ' = ' . $this->_db->quote($publisherId),
            $this->_db->quoteName('identifier_type') . ' = ' . $this->_db->quote($identifierType)
        );

        if ($rangeId > 0) {
            array_push($conditions, $this->_db->quoteName('publisher_identifier_range_id') . ' = ' . $this->_db->quote($rangeId));
        }

        // Create the query
        $query->select('*')->from($this->_db->quoteName($this->_tbl));
        $query->where($conditions);
        $query->order('identifier ASC')->setLimit((int)$count);
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

}
