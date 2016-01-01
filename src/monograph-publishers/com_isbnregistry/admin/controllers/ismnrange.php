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

require_once __DIR__ . '/abstractidentifierrange.php';

/**
 * ISMN Range Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @since       1.0.0
 */
class IsbnregistryControllerIsmnrange extends IsbnregistryControllerAbstractIdentifierRange {

    /**
     * Returns the type of the identifiers that this controller is handling.
     * @return string "isbn"
     */
    public function getIdentifierType() {
        return "ismn";
    }

}
