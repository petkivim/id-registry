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
 * Publisher Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @since       1.0.0
 */
class IsbnregistryControllerPublication extends JControllerForm
{
	function getPublicationsWithoutIdentifier() {
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		// http://{SITE}/administrator/?option=com_isbnregistry&task=publication.getPublicationsWithoutIdentifier&publisherId=1&type=(all|isbn|ismn)
        $mainframe = JFactory::getApplication();
         try {
			 // Get request parameters
			$publisherId = JRequest::getVar("publisherId",null,"post","int");
			$type = JRequest::getVar("type",null,"post","string");
			// Create response array
			$response = array();
			// Add request parameters to response
			$response["publisherId"] = $publisherId;
			$response["type"] = $type;

			// Include isbnrange model
			require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/publication.php';
			
			// Get publications
			$result = IsbnregistryModelPublication::getPublicationsWithoutIdentifier($publisherId, $type);
			// Check if the array exists
			if(!isset($result)) {
				$response['success'] = false;
				$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLICATION_GET_PUBLISHERS_WITHOUT_IDENTIFIERS_FAILED');
				$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');				
			} else {
				$response['success'] = true;	
				$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLICATION_GET_PUBLISHERS_WITHOUT_IDENTIFIERS_SUCCESS');
				$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');	
				$response['publications'] = $result;
			}
			
			// Set the MIME type for JSON output.
			header('Content-type: application/json; charset=utf-8');

			echo json_encode($response);

			$mainframe->close();
        } catch(Exception $e) {
			http_response_code(500);
			$response['success'] = false;
			$response['message'] = JText::_('COM_ISBNREGISTRY_PUBLICATION_GET_PUBLISHERS_WITHOUT_IDENTIFIERS_FAILED');
			$response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
			echo json_encode($response);
			if(!is_null($mainframe)) {
				$mainframe->close();
			}
        }
    }	
}
