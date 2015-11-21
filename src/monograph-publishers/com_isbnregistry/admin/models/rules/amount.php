<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_donation
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Form Rule class for the Joomla Framework.
 */
class JFormRuleAmount extends JFormRule
{
        /**
         * The regular expression.
         *
         * @access      protected
         * @var         string
         * @since       2.5
         */
        protected $regex = '^[1-9]+[0-9]*(\,){1}[0-9]{2}$';
}
