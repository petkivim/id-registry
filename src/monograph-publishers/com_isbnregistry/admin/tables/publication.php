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

/**
 * Publication Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTablePublication extends JTable {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_publication', 'id', $db);
    }

    /**
     * Stores a publication.
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

        if ($this->id) {
            // Existing item
            $this->modified_by = $user->get('username');
            $this->modified = $date->toSql();
        } else {
            // New item
            $this->created_by = $user->get('username');
            $this->created = $date->toSql();
        }

        // Role 1: from array to comma separated string
        /*if (is_array($this->role_1)) {
            if (count($this->role_1) > 0) {
                $this->role_1 = implode(',', $this->role_1);
            } else {
                $this->role_1 = '';
            }
        } else {
            $this->role_1 = '';
        }*/
		$this->role_1 = IsbnRegistryTablePublication::getRoles($this->role_1);
        $this->role_2 = IsbnRegistryTablePublication::getRoles($this->role_2);
        $this->role_3 = IsbnRegistryTablePublication::getRoles($this->role_3);
        $this->role_4 = IsbnRegistryTablePublication::getRoles($this->role_4);
		$this->type = IsbnRegistryTablePublication::getRoles($this->type);
		$this->fileformat = IsbnRegistryTablePublication::getRoles($this->fileformat);
        return parent::store($updateNulls);
    }
    private function getRoles($roles) {
        if (is_array($roles)) {
            if (count($roles) > 0) {
                $roles = implode(',', $roles);
            } else {
                $roles = '';
            }
        } else {
            $roles = '';
        }
        return $roles;
    }

}
