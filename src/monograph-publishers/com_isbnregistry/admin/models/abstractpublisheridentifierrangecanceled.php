<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Abstract Publisher Identifier Range Canceled Model
 *
 * @since  1.0.0
 */
abstract class IsbnregistryModelAbstractPublisherIdentifierRangeCanceled extends JModelAdmin {

    /**
     * Delete the identifier identified by the range id and identifier.
     * @param int $rangeId identifier range id
     * @param string $identifier identifier to be deleted
     * @return boolean true on success, false on failure
     */
    public function deleteIdentifier($rangeId, $identifier) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->deleteIdentifier($rangeId, $identifier);
    }

    /**
     * Returns the smallest canceled identifier object (fifo) that belongs to 
     * the given category and that was given from the range identified by the 
     * given id.
     * @param int $category category
     * @param int $rangeId identifier range id
     * @return string smallest identifier that was given from the 
     * identifier range identified by the given id
     */
    public function getIdentifier($category, $rangeId = 0) {
        // Get db access
        $table = $this->getTable();
        // Try to get a canceled identifier from the given range
        $identifier = $table->getIdentifier($category, $rangeId);
        // Return result
        return $identifier;
    }

}
