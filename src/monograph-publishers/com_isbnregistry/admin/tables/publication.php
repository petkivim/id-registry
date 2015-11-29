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

        // From array to comma separated string
		$this->role_1 = IsbnRegistryTablePublication::fromArrayToStr($this->role_1);
        $this->role_2 = IsbnRegistryTablePublication::fromArrayToStr($this->role_2);
        $this->role_3 = IsbnRegistryTablePublication::fromArrayToStr($this->role_3);
        $this->role_4 = IsbnRegistryTablePublication::fromArrayToStr($this->role_4);
		$this->type = IsbnRegistryTablePublication::fromArrayToStr($this->type);
		$this->fileformat = IsbnRegistryTablePublication::fromArrayToStr($this->fileformat);
		
        return parent::store($updateNulls);
    }
	
	/**
	 * Converts the given array to comma separated string.
	 */
    private static function fromArrayToStr($source) {
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
