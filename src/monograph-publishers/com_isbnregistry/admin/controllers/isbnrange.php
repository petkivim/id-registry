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
 * ISBN Range Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @since       1.0.0
 */
class IsbnregistryControllerIsbnrange extends JControllerForm
{
	function getIsbnRange() {
		// http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=isbnrange.getIsbnRange&publisherId=1&isbnRangeId=1
        $mainframe = JFactory::getApplication();
         try {
			 // Get request parameters
			$publisherId = JRequest::getVar("publisherId",null,"get","int");
			$isbnRangeId = JRequest::getVar("isbnRangeId",null,"get","int");
			// Create response array
			$response = array();
			// Add request parameters to reposposen
			$response["publisherId"] = $publisherId;
			$response["isbnRangeId"] = $isbnRangeId;

			// Include isbnrange model
			require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/isbnrange.php';
			
			// Get new publisher identifier
			$result = isbnregistryModelIsbnrange::getPublisherIdentifier($isbnRangeId, $publisherId);
			// Add publisher identifier to response
			$response["publisherIdentifier"] = $result;
			
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
