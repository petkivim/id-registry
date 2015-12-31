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
 * ISMN Range Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelIsmnrange extends JModelAdmin {

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
    public function getTable($type = 'Ismnrange', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.ismnrange', 'ismnrange', array(
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
                'com_isbnregistry.edit.ismnrange.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Method to format the publisher identifier including prefix
     * and publisher code "xxx-xxxxxxx".
     * 
     * @param ismnrange $ismnrange object that holds prefix
     * @param int $publisherIdentifier publisher code
     * @return string publisher identifier
     */
    public static function formatPublisherIdentifier($ismnrange, $publisherIdentifier) {
        $id = $ismnrange->prefix . '-' . $publisherIdentifier;
        return $id;
    }

    /**
     * Generates a new publisher identifier from the given ismn range and
     * assigns it to the given publisher.
     * @param int $rangeId ismn range id that used for generating the identifier
     * @param int $publisherId id of the publisher to which the identifier is assigned
     * @return mixed returns 0 if the operation fails; on success the generated
     * publisher identifier string is returned
     */
    public static function getPublisherIdentifier($rangeId, $publisherId) {
        // Database connection
        $db = JFactory::getDBO();
        // Conditions for which records should be fetched
        $conditions = array(
            $db->quoteName('id') . " = " . $db->quote($rangeId),
            $db->quoteName('is_active') . " = " . $db->quote(true),
            $db->quoteName('is_closed') . " = " . $db->quote(false)
        );
        // Database query
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__isbn_registry_ismn_range'));
        $query->where($conditions);
        $db->setQuery((string) $query);
        $ismnrange = $db->loadObject();

        // Check that we have a result
        if ($ismnrange != null) {
            // Get the next available number
            $publisherIdentifier = $ismnrange->next;
            // Is this the last value of the range
            if ($ismnrange->next == $ismnrange->range_end) {
                // This is the last value -> range becames inactive
                $ismnrange->is_active = false;
                // Range becomes closed
                $ismnrange->is_closed = true;
            }
            // Increase next pointer
            $ismnrange->next = $ismnrange->next + 1;
            // Next pointer is a string, add left padding
            $ismnrange->next = str_pad($ismnrange->next, $ismnrange->category, "0", STR_PAD_LEFT);
            // Decrease free numbers pointer 
            $ismnrange->free -= 1;
            // Increase used numbers pointer
            $ismnrange->taken += 1;
            // Update new values to database
            $result = self::updateToDb($ismnrange);
            if ($result > 0) {
                // Format publisher identifier
                $result = self::formatPublisherIdentifier($ismnrange, $publisherIdentifier);
                // Include publisherismnrange model
                require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/publisherismnrange.php';
                // Insert data into publisher ismn range table
                $insertOk = IsbnregistryModelPublisherismnrange::saveToDb($ismnrange, $publisherId, $result);
                if ($insertOk) {
                    return $result;
                }
            }
        }
        return 0;
    }

    public static function canDeleteIdentifier($rangeId, $identifier) {
        // Database connection
        $db = JFactory::getDBO();
        // Conditions for which records should be fetched
        $conditions = array(
            $db->quoteName('id') . " = " . $db->quote($rangeId)
        );
        // Database query
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__isbn_registry_ismn_range'));
        $query->where($conditions);
        $db->setQuery((string) $query);
        $ismnrange = $db->loadObject();

        // Check that we have a result
        if ($ismnrange) {
            // Get the next available number
            $nextPointer = $ismnrange->next;
            // Decrease next pointer
            $nextPointer = $nextPointer - 1;
            // Next pointer is a string, add left padding
            $nextPointer = str_pad($nextPointer, $ismnrange->category, "0", STR_PAD_LEFT);
            // Format publisher identifier
            $result = self::formatPublisherIdentifier($ismnrange, $nextPointer);
            // Compare result to the given identifier
            if (strcmp($result, $identifier) == 0) {
                // If they match, we can delete the given identifier and decrease next pointer by one
                return true;
            }
        }
        return false;
    }

    public static function decreaseByOne($rangeId) {
        // Database connection
        $db = JFactory::getDBO();
        // Conditions for which records should be fetched
        $conditions = array(
            $db->quoteName('id') . " = " . $db->quote($rangeId)
        );
        // Database query
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__isbn_registry_ismn_range'));
        $query->where($conditions);
        $db->setQuery((string) $query);
        $ismnrange = $db->loadObject();

        // Check that we have a result
        if ($ismnrange) {
            // Decrease next pointer
            $ismnrange->next = $ismnrange->next - 1;
            // Next pointer is a string, add left padding
            $ismnrange->next = str_pad($ismnrange->next, $ismnrange->category, "0", STR_PAD_LEFT);
            // Update free
            $ismnrange->free += 1;
            // Update taken
            $ismnrange->taken -= 1;
            // Update to db
            $success = self::updateToDb($ismnrange);
            if ($success == 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * Updates the given ismn range to the database.
     * @param ismnrange $ismnrange object to be updated
     * @return int number of affected rows; 1 means success, 0 means failure
     */
    private static function updateToDb($ismnrange) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Fields to update.
        $fields = array(
            $db->quoteName('free') . ' = ' . $db->quote($ismnrange->free),
            $db->quoteName('taken') . ' = ' . $db->quote($ismnrange->taken),
            $db->quoteName('next') . ' = ' . $db->quote($ismnrange->next),
            $db->quoteName('is_active') . ' = ' . $db->quote($ismnrange->is_active),
            $db->quoteName('is_closed') . ' = ' . $db->quote($ismnrange->is_closed),
            $db->quoteName('modified') . ' = ' . $db->quote($date->toSql()),
            $db->quoteName('modified_by') . ' = ' . $db->quote($user->get('username'))
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = ' . $db->quote($ismnrange->id)
        );
        // Create query
        $query->update($db->quoteName('#__isbn_registry_ismn_range'))->set($fields)->where($conditions);
        $db->setQuery($query);
        // Execute query
        $result = $db->execute();
        // Return the number of affected rows
        return $db->getAffectedRows();
    }

}
