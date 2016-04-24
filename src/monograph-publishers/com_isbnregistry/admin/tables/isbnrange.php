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

require_once __DIR__ . '/abstractidentifierrange.php';

/**
 * ISBN Range Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTableIsbnrange extends IsbnRegistryTableAbstractIdentifierRange {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_isbn_range', $db);
    }

    /**
     * Return a list of all the ISMN ranges in the database.
     * @return ObjectList list of all the ISMN ranges in the database
     */
    public function getRanges() {
        // Initialize variables.
        $query = $this->_db->getQuery(true);

        // Create the base select statement.
        $query->select('*')
                ->from($this->_db->quoteName($this->_tbl))
                ->order('prefix ASC, lang_group ASC, category ASC, range_begin ASC');

        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadObjectList();
    }

}
