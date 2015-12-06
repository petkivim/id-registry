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
 * Publisher ISBN Range Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelPublisherisbnrange extends JModelAdmin {

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
    public function getTable($type = 'Publisherisbnrange', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.publisherisbnrange', 'publisherisbnrange', array(
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
                'com_isbnregistry.edit.publisherisbnrange.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Method to store a new publisher isbn range into database.
     * 
     * @param isbnrange $isbnrange isbn range object which subset the publisher isbn range is
     * @param int $publisherId id of the publisher that owns the isbn range
     * @param int $publisherIdentifier publisher identifier of the publisher that owns the range to be created
     * @return int database id of the publisher isbn range object that was created
     */
    public static function saveToDb($isbnrange, $publisherId, $publisherIdentifier) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Get category
        $category = 6 - $isbnrange->category;
        // Create an object for the record we are going to create
        $object = new stdClass();

        // Set values
        $object->publisher_identifier = $publisherIdentifier;
        $object->publisher_id = $publisherId;
        $object->isbn_range_id = $isbnrange->id;
        $object->created_by = $user->get('username');
        $object->created = $date->toSql();
        $object->category = $category;
        $object->is_active = true;
        $object->is_closed = false;
        $object->range_begin = str_pad('', $category, '0', STR_PAD_LEFT);
        $object->range_end = str_pad('', $category, '9', STR_PAD_LEFT);
        $object->free = $object->range_end - $object->range_begin + 1;
        $object->next = $object->range_begin;

        // Disactivate all the other publisher isbn ranges
        IsbnregistryModelPublisherisbnrange::disactivateAll($publisherId);

        // Add object to DB
        $result = JFactory::getDbo()->insertObject('#__isbn_registry_publisher_isbn_range', $object);

        return $result;
    }

    /**
     * Disactivates all the isbn ranges related to the publisher matching the
     * given publisher id.
     * 
     * @param int $publisherId id of the publisher which isbn ranges are disactived
     * @return int number of affected database rows
     */
    private static function disactivateAll($publisherId) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Fields to update.
        $fields = array(
            $db->quoteName('is_active') . ' = ' . $db->quote(false),
            $db->quoteName('modified') . ' = ' . $db->quote($date->toSql()),
            $db->quoteName('modified_by') . ' = ' . $db->quote($user->get('username'))
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId)
        );
        // Create query
        $query->update($db->quoteName('#__isbn_registry_publisher_isbn_range'))->set($fields)->where($conditions);
        $db->setQuery($query);
        // Execute query
        $result = $db->execute();
        return $result;
    }

}
