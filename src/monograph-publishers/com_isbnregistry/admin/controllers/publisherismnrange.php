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
class IsbnregistryControllerPublisherismnrange extends JControllerForm
{
	function activateIsmnRange() {
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		// http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherismnrange.activateIsmnRange&publisherIsmnRangeId=1&publisherId=1
        $mainframe = JFactory::getApplication();
         try {
			// Set the MIME type for JSON output.
			header('Content-type: application/json; charset=utf-8');
			
			 // Get request parameters
			$publisherId = JRequest::getVar("publisherId",null,"post","int");
			$publisherIsmnRangeId = JRequest::getVar("publisherIsmnRangeId",null,"post","int");
			
			// Create response array
			$response = array();

			// Include ismnrange model
			require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/publisherismnrange.php';
			
			// Get new publisher identifier
			$result = IsbnregistryModelPublisherismnrange::activateIsmnRange($publisherId, $publisherIsmnRangeId);

			$response['success'] = $result;
			$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_ISMN_RANGE_ACTIVATION_SUCCESS');
			$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
			
			// Return results in JSON
			echo json_encode($response);

			$mainframe->close();
        } catch(Exception $e) {
			http_response_code(500);
			$response['success'] = false;
			$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_ISMN_RANGE_ACTIVATION_FAILED');
			$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
			echo json_encode($response);
			//echo new JResponseJson($e);
			if(!is_null($mainframe)) {
				$mainframe->close();
			}
        }
    }
	
	function getIsmnRanges() {
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		// http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherismnranges.getIsmnRanges&publisherId=1
        $mainframe = JFactory::getApplication();
         try {
			// Set the MIME type for JSON output.
			header('Content-type: application/json; charset=utf-8');
			
			 // Get request parameters
			$publisherId = JRequest::getVar("publisherId",null,"post","int");

			// Include publisherismnranges model
			require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/publisherismnranges.php';
			
			// Get new publisher identifier
			$result = IsbnregistryModelPublisherismnranges::getPublisherIdentifiers($publisherId);

			// Return results in JSON
			echo json_encode($result);

			$mainframe->close();
        } catch(Exception $e) {
			http_response_code(500);
			$response['success'] = false;
			$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISMN_RANGES_FAILED');
			$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
			echo json_encode($response);
			//echo new JResponseJson($e);
			if(!is_null($mainframe)) {
				$mainframe->close();
			}
        }
    }
	
	function deleteIsmnRange() {
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		// http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherismnranges.deleteIsmnRange&publisherIsmnRangeId=1
        $mainframe = JFactory::getApplication();
         try {
			 // Get request parameters
			$publisherIsmnRangeId = JRequest::getVar("publisherIsmnRangeId",null,"post","int");
			// Create response array
			$response = array();
			// Add request parameters to response
			$response["publisherIsmnRangeId"] = $publisherIsmnRangeId;

			// Include ismnrange model
			require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/publisherismnrange.php';
			
			// Get new publisher identifier
			$result = IsbnregistryModelPublisherismnrange::deleteIsmnRange($publisherIsmnRangeId);
			
			// Check if the result is OK and genrate response
			if($result) {
				$response['success'] = true;	
				$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_ISMN_RANGE_SUCCESS');
				$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');				
			} else {
				$response['success'] = false;
				$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_ISMN_RANGE_FAILED');
				$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');				
			}			
			// Set the MIME type for JSON output.
			header('Content-type: application/json; charset=utf-8');

			echo json_encode($response);

			$mainframe->close();
        } catch(Exception $e) {
			http_response_code(500);
			$response['success'] = false;
			$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_ISMN_RANGE_FAILED');
			$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
			echo json_encode($response);
			if(!is_null($mainframe)) {
				$mainframe->close();
			}
        }
    }

	function getIsmnNumbers() {
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		// http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherismnranges.getIsmnNumbers&publisherId=1&ismnCount=5
        $mainframe = JFactory::getApplication();
         try {
			// Set the MIME type for JSON output.
			header('Content-type: application/json; charset=utf-8');
			
			 // Get request parameters
			$publisherId = JRequest::getVar("publisherId",null,"post","int");
			$ismnCount = JRequest::getVar("ismnCount",null,"post","int");

			// Add request parameters to response
			$response["publisherId"] = $publisherId;
			$response["ismnCount"] = $ismnCount;
			
			// Include publisherismnrange model
			require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/publisherismnrange.php';
			
			// Get array of ISBN numbers
			$result = IsbnregistryModelPublisherismnrange::generateIsmnNumbers($publisherId, $ismnCount);
			// Check if the array is empty
			if(empty($result)) {
				$response['success'] = false;
				$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISMN_NUMBERS_FAILED');
				$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');				
			} else {
				$response['success'] = true;	
				$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISMN_NUMBERS_SUCCESS');
				$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');	
				$response['ismn_numbers'] = $result;
			}
			// Return results in JSON
			echo json_encode($response);

			$mainframe->close();
        } catch(Exception $e) {
			http_response_code(500);
			$response['success'] = false;
			$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISMN_NUMBERS_FAILED');
			$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
			echo json_encode($response);
			//echo new JResponseJson($e);
			if(!is_null($mainframe)) {
				$mainframe->close();
			}
        }
    }	

	function getIsmnNumber() {
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		// http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherismnranges.getIsmnNumbers&publisherId=1&publicationId=5
        $mainframe = JFactory::getApplication();
         try {
			// Set the MIME type for JSON output.
			header('Content-type: application/json; charset=utf-8');
			
			 // Get request parameters
			$publisherId = JRequest::getVar("publisherId",null,"post","int");
			$publicationId = JRequest::getVar("publicationId",null,"post","int");

			// Add request parameters to response
			$response["publisherId"] = $publisherId;
			$response["publicationId"] = $publicationId;
			
			// Include publisherismnrange model
			require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/publisherismnrange.php';
			
			// Get array of ISBN numbers
			$result = IsbnregistryModelPublisherismnrange::generateIsmnNumbers($publisherId, 1);
			// Check if the array is empty
			if(empty($result)) {
				$response['success'] = false;
				$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISMN_NUMBER_FAILED');
				$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');				
			} else {
				// Include publication model
				require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/publication.php';
				// Get generated identifier
				$ismn = $result[0];
				// Update publication record
				$updateSuccess = IsbnregistryModelPublication::updateIdentifier($publicationId, $publisherId, $ismn, 'ISBN');
				// Check if operation succeeded
				if($updateSuccess) {
					$response['success'] = true;	
					$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISMN_NUMBER_SUCCESS');
					$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');						
					$response['publication_identifier'] = $ismn;
				} else {
					// TODO: Updating publication failed, try to delete the generated identifier
					$response['success'] = false;
					$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISMN_NUMBER_FAILED');
					$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');						
				}
			}
			// Return results in JSON
			echo json_encode($response);

			$mainframe->close();
        } catch(Exception $e) {
			http_response_code(500);
			$response['success'] = false;
			$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISMN_NUMBER_FAILED');
			$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
			echo json_encode($response);
			//echo new JResponseJson($e);
			if(!is_null($mainframe)) {
				$mainframe->close();
			}
        }
    }		
}
