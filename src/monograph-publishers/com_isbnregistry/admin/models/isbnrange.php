<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * ISBN Range Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelIsbnrange extends JModelAdmin {

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
    public function getTable($type = 'Isbnrange', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.isbnrange', 'isbnrange', array(
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
                'com_isbnregistry.edit.isbnrange.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Method to format the publisher identifier including prefix, language
     * group and publisher code "xxx-xxx-xxxxx".
     * 
     * @param isbnrange $isbnrange object that holds prefix and language code
     * @param int $publisherIdentifier publisher code
     * @return string publisher identifier
     */
    public static function formatPublisherIdentifier($isbnrange, $publisherIdentifier) {
        $id = $isbnrange->prefix > 0 ? $isbnrange->prefix . '-' : '';
        $id .= $isbnrange->lang_group . '-' . $publisherIdentifier;
        return $id;
    }

    /**
     * Generates a new publisher identifier from the given isbn range and
     * assigns it to the given publisher.
     * @param int $rangeId isbn range id that used for generating the identifier
     * @param int $publisherId id of the publisher to which the identifier is assigned
     * @return mixed returns 0 if the operation fails; on success the generated
     * publisher identifier string is returned
     */
    public function getPublisherIdentifier($rangeId, $publisherId) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Get ISBN range object
        $isbnrange = $dao->getRange($rangeId, true);

        // Check that we have a result
        if ($isbnrange != null) {
            // Check that there are free numbers available
            if ($isbnrange->next > $isbnrange->range_end) {
                return 0;
            }
            // Get the next available number
            $publisherIdentifier = $isbnrange->next;
            // Is this the last value of the range
            if ($isbnrange->next == $isbnrange->range_end) {
                // This is the last value -> range becames inactive
                $isbnrange->is_active = false;
            } else {
                // Increase next pointer
                $isbnrange->next = $isbnrange->next + 1;
                // Next pointer is a string, add left padding
                $isbnrange->next = str_pad($isbnrange->next, $isbnrange->category, "0", STR_PAD_LEFT);
            }
            // Decrease free numbers pointer 
            $isbnrange->free -= 1;
            // Increase used numbers pointer
            $isbnrange->taken += 1;
            // Update new values to database
            if ($dao->updateRange($isbnrange)) {
                // Format publisher identifier
                $result = self::formatPublisherIdentifier($isbnrange, $publisherIdentifier);
                // Get an instance of a ISBN range model
                $publisherIsbnRangeModel = $this->getInstance('publisherisbnrange', 'IsbnregistryModel');
                // Insert data into publisher isbn range table
                if ($publisherIsbnRangeModel->saveToDb($isbnrange, $publisherId, $result)) {
                    return $result;
                }
            }
        }
        return 0;
    }

    /**
     * Checks if the given identifier can be deleted. The identifier can be
     * deleted if and only if it is the last identifier that was generated
     * from its range. 
     * @param integer $rangeId id of the range in which the identifier belongs
     * @param string $identifier identifier to be deleted
     * @return boolean true if and only if the identifier can be deleted; 
     * otherwise false
     */
    public function canDeleteIdentifier($rangeId, $identifier) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Get ISBN range object
        $isbnrange = $dao->getRange($rangeId, false);

        // Check that we have a result
        if ($isbnrange) {
            // Get the next available number
            $nextPointer = $isbnrange->next;
            // Decrease next pointer
            $nextPointer = $nextPointer - 1;
            // Next pointer is a string, add left padding
            $nextPointer = str_pad($nextPointer, $isbnrange->category, "0", STR_PAD_LEFT);
            // Format publisher identifier
            $result = self::formatPublisherIdentifier($isbnrange, $nextPointer);
            // Compare result to the given identifier
            if (strcmp($result, $identifier) == 0) {
                // If they match, we can delete the given identifier and decrease next pointer by one
                return true;
            }
        }
        return false;
    }

    /**
     * Updates the range matching the given identifier id and decreases its
     * next pointer by one. Also free and taken properties are updated 
     * accordingly.
     * @param integer $rangeId id of the range to be updated
     * @return boolean true if and only if the operation succeeds; otherwise
     * false
     */
    public function decreaseByOne($rangeId) {
        // Get DAO for db access
        $dao = $this->getTable();
        // Get ISBN range object
        $isbnrange = $dao->getRange($rangeId, false);

        // Check that we have a result
        if ($isbnrange) {
            // Decrease next pointer
            $isbnrange->next = $isbnrange->next - 1;
            // Next pointer is a string, add left padding
            $isbnrange->next = str_pad($isbnrange->next, $isbnrange->category, "0", STR_PAD_LEFT);
            // Update free
            $isbnrange->free += 1;
            // Update taken
            $isbnrange->taken -= 1;
            // Update to db
            if ($dao->updateRange($isbnrange)) {
                return true;
            }
        }
        return false;
    }

}
