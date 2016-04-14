<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/abstractpublisheridentifierrange.php';

/**
 * Publisher ISMN Range Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTablePublisherismnrange extends IsbnRegistryTableAbstractPublisherIdentifierRange {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_publisher_ismn_range', $db);
    }

    /**
     * Method to store a new publisher ISMN range into database.
     * 
     * @param range $range ISMN range object which subset the 
     * publisher identifier range is
     * @param int $publisherId id of the publisher that owns the range
     * @param int $publisherIdentifier publisher identifier of the publisher 
     * that owns the range to be created
     * @return boolean returns true if and only if the object was 
     * successfully saved to the database; otherwise false
     */
    public function saveToDb($range, $publisherId, $publisherIdentifier) {
        // Set ismn range id
        $this->ismn_range_id = $range->id;

        return parent::saveToDb($range, $publisherId, $publisherIdentifier);
    }

    /**
     * Returns the character count that's reserved for publisher and
     * publication identifiers.
     * @return int ISMN's char count is 8
     */
    public function getRangeLength() {
        return 8;
    }

    /**
     * Returns an array of publisher ids that contains ids of all the ISMN
     * publishers.
     * @return array array of publisher ids
     */
    public function getIsmnPublisherIds() {
        $query = $this->_db->getQuery(true);

        // Create the query
        $query->select('distinct publisher_id')
                ->from($this->_db->quoteName($this->_tbl));
        $this->_db->setQuery($query);
        // Execute query
        return $this->_db->loadColumn();
    }

}

?>