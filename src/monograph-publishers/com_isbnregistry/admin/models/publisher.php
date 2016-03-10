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
 * Publisher Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelPublisher extends JModelAdmin {

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
    public function getTable($type = 'Publisher', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.publisher', 'publisher', array(
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
                'com_isbnregistry.edit.publisher.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        // Question 7: from comma separated string to array
        if ($data->question_7) {
            $data->question_7 = explode(',', $data->question_7);
        }

        return $data;
    }

    /**
     * Returns a publisher mathcing the given id.
     * @param int $publisherId id of the publisher
     * @return Publisher publisher matching the given id
     */
    public function getPublisherById($publisherId) {
        // Get db access
        $table = $this->getTable();
        // Get publisher
        return $table->getPublisherById($publisherId);
    }

    /**
     * Returns an array that contains publisher id and name as key value
     * pairs.
     * @return array publisher id and name as key value pairs
     */
    public function getPublishersArray() {
        // Get db access
        $table = $this->getTable();
        // Get publishers
        $publishers = $table->getPublishers();
        // Check result
        if (!$publishers) {
            return array();
        }
        $result = array();
        foreach ($publishers as $publisher) {
            $result[$publisher->id] = $publisher->official_name;
        }
        return $result;
    }

    /**
     * Updates the active ISBN identifier of the publisher identified by
     * the given publisher id.
     * @param int $publisherId id of the publisher to be updated
     * @param string $identifier ISBN identifier string
     */
    public function updateActiveIsbnIdentifier($publisherId, $identifier) {
        // Get db access
        $table = $this->getTable();
        // Update
        return $table->updateActiveIsbnIdentifier($publisherId, $identifier);
    }

    /**
     * Updates the active ISMN identifier of the publisher identified by
     * the given publisher id.
     * @param int $publisherId id of the publisher to be updated
     * @param string $identifier ISMN identifier string
     */
    public function updateActiveIsmnIdentifier($publisherId, $identifier) {
        // Get db access
        $table = $this->getTable();
        // Update
        return $table->updateActiveIsmnIdentifier($publisherId, $identifier);
    }

    /**
     * Returns a list of publishers and all the publisher ISBN identifiers.
     * If publisher has multiple identifiers, the publisher is included in the
     * list multiple times.
     * @return list of publishers
     */
    public function getPublishersAndIsbnIdentifiers() {
        // Get db access
        $table = $this->getTable();
        //   Return result
        return $table->getPublishersAndIsbnIdentifiers();
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
        // Get db access
        $table = $this->getTable();
        //   Return result
        return $table->getPublishersByCategory($categories, $hasQuitted, $type);
    }

    /**
     * Loads the has quitted value of the publisher identified by the given
     * publisher id.
     * @param integer $publisherId id of the publisher
     * @return boolean true if publisher has quitted, otherwise false
     */
    public function hasQuitted($publisherId) {
        // Get db access
        $table = $this->getTable();
        //  Return result
        return $table->hasQuitted($publisherId);
    }

}
