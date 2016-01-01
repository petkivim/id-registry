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
 * Abstract Identifier Range Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @since       1.0.0
 */
abstract class IsbnregistryControllerAbstractPublisherIdentifierRange extends JControllerForm {

    abstract public function getIdentifierType();
    
    function activate() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherisbnrange.activateIsbnRange&publisherIsbnRangeId=1&publisherId=1
        $mainframe = JFactory::getApplication();
        try {
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            // Get request parameters
            $publisherId = JRequest::getVar("publisherId", null, "post", "int");
            $publisherRangeId = JRequest::getVar("publisherRangeId", null, "post", "int");

            // Create response array
            $response = array();

            // Get new publisher identifier
            $result = $this->getModel()->activateRange($publisherId, $publisherRangeId);

            $response['success'] = $result;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_' . strtoupper($this->getIdentifierType()) . '_RANGE_ACTIVATION_SUCCESS');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');

            // Return results in JSON
            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_' . strtoupper($this->getIdentifierType()) . '_RANGE_ACTIVATION_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            //echo new JResponseJson($e);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

    function getRanges() {
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
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_RANGES_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            //echo new JResponseJson($e);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

    function delete() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherisbnranges.deleteIsbnRange&publisherIsbnRangeId=1
        $mainframe = JFactory::getApplication();
        try {
            // Get request parameters
            $publisherRangeId = JRequest::getVar("publisherRangeId", null, "post", "int");
            // Create response array
            $response = array();
            // Add request parameters to response
            $response["publisherRangeId"] = $publisherRangeId;

            // Delete the given identifier range
            $result = $this->getModel()->deleteRange($publisherRangeId);

            // Check if the result is OK and genrate response
            if ($result) {
                $response['success'] = true;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_' . strtoupper($this->getIdentifierType()) . '_RANGE_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
            } else {
                $response['success'] = false;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_' . strtoupper($this->getIdentifierType()) . '_RANGE_FAILED');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            }
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_' . strtoupper($this->getIdentifierType()) . '_RANGE_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

    function getIdentifiers() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // http://pkrete.com/sites/idr/administrator/?option=com_isbnregistry&task=publisherisbnranges.getIsbnNumbers&publisherId=1&isbnCount=5
        $mainframe = JFactory::getApplication();
        try {
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            // Get request parameters
            $publisherId = JRequest::getVar("publisherId", null, "post", "int");
            $count = JRequest::getVar("count", null, "post", "int");

            // Add request parameters to response
            $response["publisherId"] = $publisherId;
            $response["count"] = $count;

            // Get array of ISBN numbers
            $result = $this->getModel()->generateIdentifiers($publisherId, $count);
            
            // Check if the array is empty
            if (empty($result)) {
                $response['success'] = false;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_NUMBERS_FAILED');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            } else {
                $response['success'] = true;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_NUMBERS_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
                $response['isbn_numbers'] = $result;
            }
            // Return results in JSON
            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_NUMBERS_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            //echo new JResponseJson($e);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

    function getIdentifier() {
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

            // Get array of identifiers numbers
            $result = $this->getModel()->generateIdentifiers($publisherId, 1);
            // Check if the array is empty
            if (empty($result)) {
                $response['success'] = false;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_NUMBER_FAILED');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            } else {
                // Load publication model
                $publicationModel = JModelLegacy::getInstance( 'publication', 'IsbnregistryModel' );
                // Get generated identifier
                $identifier = $result[0];
                // Update publication record
                // TODO: if this operation fails, the identifier given to it should be freed. Now the identifier is left unused.
                $updateSuccess = $publicationModel->updateIdentifier($publicationId, $publisherId, $identifier, strtoupper($this->getIdentifierType()));
                // Check if operation succeeded
                if ($updateSuccess) {
                    $response['success'] = true;
                    $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_NUMBER_SUCCESS');
                    $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
                    $response['publication_identifier'] = $identifier;
                } else {
                    // TODO: Updating publication failed, try to delete the generated identifier
                    $response['success'] = false;
                    $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_NUMBER_FAILED');
                    $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
                }
            }
            // Return results in JSON
            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_NUMBER_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            //echo new JResponseJson($e);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

}
