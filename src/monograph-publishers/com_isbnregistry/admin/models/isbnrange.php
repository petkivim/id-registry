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
    public static function getPublisherIdentifier($rangeId, $publisherId) {
        // Database connection
        $db = JFactory::getDBO();
        // Conditions for which records should be fetched
        $conditions = array(
            $db->quoteName('id') . " = " . $db->quote($rangeId),
            $db->quoteName('is_active') . " = " . $db->quote(true),
        );
        // Database query
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__isbn_registry_isbn_range'));
        $query->where($conditions);
        $db->setQuery((string) $query);
        $isbnrange = $db->loadObject();

        // Check that we have a result
        if ($isbnrange) {
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
            $result = IsbnregistryModelIsbnrange::updateToDb($isbnrange);
            if ($result > 0) {
                // Format publisher identifier
                $result = IsbnregistryModelIsbnrange::formatPublisherIdentifier($isbnrange, $publisherIdentifier);
                // Include publisherisbnrange model
                require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/publisherisbnrange.php';
                // Insert data into publisher isbn range table
                $insertOk = IsbnregistryModelPublisherisbnrange::saveToDb($isbnrange, $publisherId, $result);
				if($insertOk) {
					return $result;
				}
            }
        }
        return 0;
    }

    /**
     * Updates the given isbn range to the database.
     * @param isbnrange $isbnrange object to be updated
     * @return int number of affected rows; 1 means success, 0 means failure
     */
    private static function updateToDb($isbnrange) {
        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        // Database connection
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Fields to update.
        $fields = array(
            $db->quoteName('free') . ' = ' . $db->quote($isbnrange->free),
            $db->quoteName('taken') . ' = ' . $db->quote($isbnrange->taken),
            $db->quoteName('next') . ' = ' . $db->quote($isbnrange->next),
            $db->quoteName('is_active') . ' = ' . $db->quote($isbnrange->is_active),
            $db->quoteName('modified') . ' = ' . $db->quote($date->toSql()),
            $db->quoteName('modified_by') . ' = ' . $db->quote($user->get('username'))
        );

        // Conditions for which records should be updated.
        $conditions = array(
            $db->quoteName('id') . ' = ' . $db->quote($isbnrange->id)
        );
        // Create query
        $query->update($db->quoteName('#__isbn_registry_isbn_range'))->set($fields)->where($conditions);
        $db->setQuery($query);
        // Execute query
        $result = $db->execute();
        return $result;
    }

}
