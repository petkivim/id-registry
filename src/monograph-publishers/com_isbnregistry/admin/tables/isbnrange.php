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

require_once __DIR__ . '/abstractidentifierrange.php';

/**
 * ISBN Range Table class
 *
 * @since  1.0.0
 */
class IsbnRegistryTableIsbnrange extends IsbnRegistryTableAbstractIdentifierRange {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_isbn_range', $db);
    }
}
