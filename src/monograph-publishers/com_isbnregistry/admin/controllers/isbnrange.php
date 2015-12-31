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
class IsbnregistryControllerIsbnrange extends JControllerForm {

    function getIsbnRange() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=isbnrange.getIsbnRange&publisherId=1&isbnRangeId=1
        $mainframe = JFactory::getApplication();
        try {
            // Get request parameters
            $publisherId = JRequest::getVar("publisherId", null, "post", "int");
            $isbnRangeId = JRequest::getVar("isbnRangeId", null, "post", "int");
            // Create response array
            $response = array();
            // Add request parameters to response
            $response["publisherId"] = $publisherId;
            $response["isbnRangeId"] = $isbnRangeId;

            // Get new publisher identifier
            $result = $this->getModel()->getPublisherIdentifier($isbnRangeId, $publisherId);

            // Genrate response
            $response['success'] = $result == 0 ? false : true;
            if ($result == 0) {
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISBN_RANGE_FAILED');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            } else {
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISBN_RANGE_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
            }
            // Add publisher identifier to response
            $response["publisherIdentifier"] = $result;

            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISBN_RANGE_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

}
