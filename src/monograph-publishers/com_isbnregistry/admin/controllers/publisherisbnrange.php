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
class IsbnregistryControllerPublisherisbnrange extends JControllerForm {

    function activateIsbnRange() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherisbnrange.activateIsbnRange&publisherIsbnRangeId=1&publisherId=1
        $mainframe = JFactory::getApplication();
        try {
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            // Get request parameters
            $publisherId = JRequest::getVar("publisherId", null, "post", "int");
            $publisherIsbnRangeId = JRequest::getVar("publisherIsbnRangeId", null, "post", "int");

            // Create response array
            $response = array();

            // Get new publisher identifier
            $result = $this->getModel()->activateIsbnRange($publisherId, $publisherIsbnRangeId);

            $response['success'] = $result;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_ISBN_RANGE_ACTIVATION_SUCCESS');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');

            // Return results in JSON
            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_ISBN_RANGE_ACTIVATION_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            //echo new JResponseJson($e);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

    function getIsbnRanges() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherisbnranges.getIsbnRanges&publisherId=1
        $mainframe = JFactory::getApplication();
        try {
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            // Get request parameters
            $publisherId = JRequest::getVar("publisherId", null, "post", "int");

            // Get new publisher identifier
            $result = $this->getModel()->getPublisherIdentifiers($publisherId);

            // Return results in JSON
            echo json_encode($result);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISBN_RANGES_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            //echo new JResponseJson($e);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

    function deleteIsbnRange() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherisbnranges.deleteIsbnRange&publisherIsbnRangeId=1
        $mainframe = JFactory::getApplication();
        try {
            // Get request parameters
            $publisherIsbnRangeId = JRequest::getVar("publisherIsbnRangeId", null, "post", "int");
            // Create response array
            $response = array();
            // Add request parameters to response
            $response["publisherIsbnRangeId"] = $publisherIsbnRangeId;

            // Delete the given ISBN range
            $result = $this->getModel()->deleteIsbnRange($publisherIsbnRangeId);

            // Check if the result is OK and genrate response
            if ($result) {
                $response['success'] = true;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_ISBN_RANGE_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
            } else {
                $response['success'] = false;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_ISBN_RANGE_FAILED');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            }
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_ISBN_RANGE_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

    function getIsbnNumbers() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherisbnranges.getIsbnNumbers&publisherId=1&isbnCount=5
        $mainframe = JFactory::getApplication();
        try {
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            // Get request parameters
            $publisherId = JRequest::getVar("publisherId", null, "post", "int");
            $isbnCount = JRequest::getVar("isbnCount", null, "post", "int");

            // Add request parameters to response
            $response["publisherId"] = $publisherId;
            $response["isbnCount"] = $isbnCount;

            // Get array of ISBN numbers
            $result = $this->getModel()->generateIsbnNumbers($publisherId, $isbnCount);
            
            // Check if the array is empty
            if (empty($result)) {
                $response['success'] = false;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISBN_NUMBERS_FAILED');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            } else {
                $response['success'] = true;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISBN_NUMBERS_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
                $response['isbn_numbers'] = $result;
            }
            // Return results in JSON
            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISBN_NUMBERS_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            //echo new JResponseJson($e);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

    function getIsbnNumber() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherisbnranges.getIsbnNumbers&publisherId=1&publicationId=5
        $mainframe = JFactory::getApplication();
        try {
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            // Get request parameters
            $publisherId = JRequest::getVar("publisherId", null, "post", "int");
            $publicationId = JRequest::getVar("publicationId", null, "post", "int");

            // Add request parameters to response
            $response["publisherId"] = $publisherId;
            $response["publicationId"] = $publicationId;

            // Get array of ISBN numbers
            $result = $this->getModel()->generateIsbnNumbers($publisherId, 1);
            // Check if the array is empty
            if (empty($result)) {
                $response['success'] = false;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISBN_NUMBER_FAILED');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            } else {
                // Load publication model
                $publicationModel = JModelLegacy::getInstance( 'publication', 'IsbnregistryModel' );
                // Get generated identifier
                $isbn = $result[0];
                // Update publication record
                $updateSuccess = $publicationModel->updateIdentifier($publicationId, $publisherId, $isbn, 'ISBN');
                // Check if operation succeeded
                if ($updateSuccess) {
                    $response['success'] = true;
                    $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISBN_NUMBER_SUCCESS');
                    $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
                    $response['publication_identifier'] = $isbn;
                } else {
                    // TODO: Updating publication failed, try to delete the generated identifier
                    $response['success'] = false;
                    $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISBN_NUMBER_FAILED');
                    $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
                }
            }
            // Return results in JSON
            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_ISBN_NUMBER_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            //echo new JResponseJson($e);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

}
