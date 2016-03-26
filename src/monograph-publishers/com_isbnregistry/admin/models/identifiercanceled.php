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
 * Identifier Canceled Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelIdentifiercanceled extends JModelAdmin {

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $type    The table name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  JTable  A JTable object
     *
     * @since   1.6
     */
    public function getTable($type = 'Identifiercanceled', $prefix = 'IsbnregistryTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed    A JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm(
                'com_isbnregistry.identifiercanceled', 'identifiercanceled', array(
            'control' => 'jform', 'load_data' => $loadData
                )
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since   1.6
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState(
                'com_isbnregistry.edit.identifiercanceled.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
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
        // Get db access
        $table = $this->getTable();
        // Try to get a canceled identifiers from the given range
        $identifiersWithRange = $table->getIdentifiers($category, $publisherId, $identifierType, $count, $rangeId);
        // Return result if we have one
        if (!empty($identifiersWithRange)) {
            // Check if we got all the identifiers
            if (sizeof($identifiersWithRange) == $count) {
                return $identifiersWithRange;
            } else {
                // If identifiers are still missing, update count variable
                $count -= sizeof($identifiersWithRange);
            }
        }
        // Try to get missing canceled identifiers with the same category, 
        // but from any range
        $identifiersNoRange = $table->getIdentifiers($category, $publisherId, $identifierType, $count);
        // Merge arrays
        $identifiers = array_merge($identifiersWithRange, $identifiersNoRange);
        // We may have double values that must be removed
        $result = array();
        $index = array();
        // Loop through merged arrays and remove double values
        foreach ($identifiers as $identifier) {
            if (!array_key_exists($identifier->identifier, $index)) {
                array_push($result, $identifier);
                $index[$identifier->identifier] = 1;
            }
        }
        // Return result
        return $result;
    }

    /**
     * Delete all identifiers related to the publisher identifier range 
     * identified by the given id.
     * @param int $publisherIdenfierRangeId publisher identifier range id
     * @return int number of deleted rows
     */
    public function deleteByPublisherIdenfierRangeId($publisherIdenfierRangeId) {
        // Get db access
        $table = $this->getTable();
        // Return result
        return $table->deleteByPublisherIdenfierRangeId($publisherIdenfierRangeId);
    }

}
