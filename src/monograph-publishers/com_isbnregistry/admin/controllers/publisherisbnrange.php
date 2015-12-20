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
class IsbnregistryControllerPublisherisbnrange extends JControllerForm
{
	function activateIsbnRange() {
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		// http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherisbnrange.activateIsbnRange&publisherIsbnRangeId=1&publisherId=1
        $mainframe = JFactory::getApplication();
         try {
			// Set the MIME type for JSON output.
			header('Content-type: application/json; charset=utf-8');
			
			 // Get request parameters
			$publisherId = JRequest::getVar("publisherId",null,"post","int");
			$publisherIsbnRangeId = JRequest::getVar("publisherIsbnRangeId",null,"post","int");
			
			// Create response array
			$response = array();

			// Include isbnrange model
			require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/publisherisbnrange.php';
			
			// Get new publisher identifier
			$result = IsbnregistryModelPublisherisbnrange::activateIsbnRange($publisherId, $publisherIsbnRangeId);

			$response['success'] = $result;
			$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_ISBN_RANGE_ACTIVATION_SUCCESS');
			$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
			
			// Return results in JSON
			echo json_encode($response);

			$mainframe->close();
        } catch(Exception $e) {
			http_response_code(500);
			$response['success'] = false;
			$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_ISBN_RANGE_ACTIVATION_FAILED');
			$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
			echo json_encode($response);
			//echo new JResponseJson($e);
			if(!is_null($mainframe)) {
				$mainframe->close();
			}
        }
    }	
}
