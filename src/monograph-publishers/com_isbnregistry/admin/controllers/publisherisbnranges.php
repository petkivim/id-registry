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
class IsbnregistryControllerPublisherisbnranges extends JControllerForm
{
	function getIsbnRanges() {
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		
		// http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherisbnranges.getIsbnRanges&publisherId=1
        $mainframe = JFactory::getApplication();
         try {
			// Set the MIME type for JSON output.
			header('Content-type: application/json; charset=utf-8');
			
			 // Get request parameters
			$publisherId = JRequest::getVar("publisherId",null,"post","int");

			// Include publisherisbnranges model
			require_once JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models/publisherisbnranges.php';
			
			// Get new publisher identifier
			$result = IsbnregistryModelPublisherisbnranges::getPublisherIdentifiers($publisherId);

			// Return results in JSON
			echo json_encode($result);

			$mainframe->close();
        } catch(Exception $e) {
			//$error['success'] = false;
			//echo json_encode($error);
			echo new JResponseJson($e);
			if(!is_null($mainframe)) {
				$mainframe->close();
			}
        }
    }	
}
