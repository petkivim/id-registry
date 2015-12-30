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
 * ISMN Range Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @since       1.0.0
 */
class IsbnregistryControllerIsmnrange extends JControllerForm
{
	function getIsmnRange() {
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		// http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=ismnrange.getIsmnRange&publisherId=1&ismnRangeId=1
        $mainframe = JFactory::getApplication();
         try {
			 // Get request parameters
			$publisherId = JRequest::getVar("publisherId",null,"post","int");
			$ismnRangeId = JRequest::getVar("ismnRangeId",null,"post","int");
			// Create response array
			$response = array();
			// Add request parameters to response
			$response["publisherId"] = $publisherId;
			$response["ismnRangeId"] = $ismnRangeId;

			// Include ismnrange model
			require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/ismnrange.php';
			
			// Get new publisher identifier
			$result = IsbnregistryModelIsmnrange::getPublisherIdentifier($ismnRangeId, $publisherId);
			// Genrate response
			$response['success'] = $result == 0 ? false : true;
			if($result == 0) {
				$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISMN_RANGE_FAILED');
				$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
			} else {
				$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISMN_RANGE_SUCCESS');
				$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');				
			}
			// Add publisher identifier to response
			$response["publisherIdentifier"] = $result;
			
			// Set the MIME type for JSON output.
			header('Content-type: application/json; charset=utf-8');

			echo json_encode($response);

			$mainframe->close();
        } catch(Exception $e) {
			http_response_code(500);
			$response['success'] = false;
			$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISMN_RANGE_FAILED');
			$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
			echo json_encode($response);
			if(!is_null($mainframe)) {
				$mainframe->close();
			}
        }
    }
}
