<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * ISBN Registry Component Controller
 */
class IsbnregistryController extends JControllerLegacy
{
	function display(){
		parent::display();
	}

    function search() {

        $mainframe = JFactory::getApplication();
         try {
			$search = JRequest::getVar("search",null,"get","String");
			$response = array();
			$response["search"] = $search;

			// TODO: implement DB search using the given parameters
			
			// Set the MIME type for JSON output.
			header('Content-type: application/json; charset=utf-8');

			echo json_encode($response);

			$mainframe->close();
        } catch(Exception $e) {
			echo new JResponseJson($e);
			if(!is_null($mainframe)) {
					$mainframe->close();
			}
        }
    }
}
