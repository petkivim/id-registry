<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/abstractpublisheridentifierrangecanceled.php';

/**
 * Publisher ISMN Range CanceledTable class
 *
 * @since  1.0.0
 */
class IsbnRegistryTablePublisherIsmnrangecanceled extends IsbnRegistryTableAbstractPublisherIdentifierRangeCanceled {

    /**
     * Constructor
     *
     * @param   JDatabaseDriver  &$db  A database connector object
     */
    function __construct(&$db) {
        parent::__construct('#__isbn_registry_publisher_ismn_range_canceled', $db);
    }
}
