<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Group Message Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTableGroupmessage extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_group_message', 'id', $db);
    }

    /**
     * Stores a group message.
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

        // Get date and user
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        $this->created_by = $user->get('username');
        $this->created = $date->toSql();

        // From array to comma separated string
        $this->isbn_categories = $this->fromArrayToStr($this->isbn_categories);
        $this->ismn_categories = $this->fromArrayToStr($this->ismn_categories);

        return parent::store($updateNulls);
    }

    /**
     * Deletes a group message.
     *
     * @param   integer  $pk  Primary key of the message type to be deleted.
     *
     * @return  boolean  True on success, false on failure.
     *
     */
    public function delete($pk = null) {
        return parent::delete($pk);
    }

    /**
     * Converts the given array to comma separated string.
     */
    private function fromArrayToStr($source) {
        if (is_array($source)) {
            if (count($source) > 0) {
                $source = implode(',', $source);
            } else {
                $source = '';
            }
        } else {
            $source = '';
        }
        return $source;
    }

}
