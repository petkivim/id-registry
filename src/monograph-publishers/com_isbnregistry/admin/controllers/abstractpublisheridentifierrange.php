<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
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

        // http://{SITE}/administrator/?option=com_isbnregistry&task=publisherisbnrange.activateIsbnRange&publisherIsbnRangeId=1&publisherId=1
        $mainframe = JFactory::getApplication();
        try {
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            // Get request parameters
            $jinput = JFactory::getApplication()->input->post;
            $publisherId = $jinput->get('publisherId', null, 'int');
            $publisherRangeId = $jinput->get('publisherRangeId', null, 'int');

            // Create response array
            $response = array();

            // Get model
            $model = $this->getModel();
            // Get new publisher identifier
            $result = $model->activateRange($publisherId, $publisherRangeId);
            if ($result) {
                $response['success'] = true;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_' . strtoupper($this->getIdentifierType()) . '_RANGE_ACTIVATION_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
            } else {
                $response['success'] = false;
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_' . strtoupper($this->getIdentifierType()) . '_RANGE_ACTIVATION_FAILED');
                if ($model->getError()) {
                    $response['message'] .= ' ' . $model->getError();
                }
            }

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

        // http://{SITE}/administrator/?option=com_isbnregistry&task=publisherisbnranges.getIsbnRanges&publisherId=1
        $mainframe = JFactory::getApplication();
        try {
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            // Get request parameters
            $jinput = JFactory::getApplication()->input->post;
            $publisherId = $jinput->get('publisherId', null, 'int');

            // Get model
            $model = $this->getModel();
            // Get new publisher identifier
            $result = $model->getPublisherIdentifiers($publisherId);

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

        // http://{SITE}/administrator/?option=com_isbnregistry&task=publisherisbnranges.deleteIsbnRange&publisherIsbnRangeId=1
        $mainframe = JFactory::getApplication();
        try {
            // Get request parameters
            $jinput = JFactory::getApplication()->input->post;
            $publisherRangeId = $jinput->get('publisherRangeId', null, 'int');

            // Create response array
            $response = array();
            // Add request parameters to response
            $response["publisherRangeId"] = $publisherRangeId;

            // Get model
            $model = $this->getModel();
            // Delete the given identifier range
            $result = $model->deleteRange($publisherRangeId);

            // Check if the result is OK and genrate response
            if ($result) {
                $response['success'] = true;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_' . strtoupper($this->getIdentifierType()) . '_RANGE_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
            } else {
                $response['success'] = false;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_' . strtoupper($this->getIdentifierType()) . '_RANGE_FAILED');
                if ($model->getError()) {
                    $response['message'] .= ' ' . $model->getError();
                }
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

        // http://{SITE}/administrator/?option=com_isbnregistry&task=publisherisbnranges.getIsbnNumbers&publisherId=1&isbnCount=5
        $mainframe = JFactory::getApplication();
        try {
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            // Get request parameters
            $jinput = JFactory::getApplication()->input->post;
            $publisherId = $jinput->get('publisherId', null, 'int');
            $count = $jinput->get('count', null, 'int');

            // Add request parameters to response
            $response["publisherId"] = $publisherId;
            $response["count"] = $count;

            // Get model
            $model = $this->getModel();
            // Get array of ISBN numbers
            $result = $model->generateIdentifiers($publisherId, $count);

            // Check if the array is empty
            if (empty($result['identifiers'])) {
                $response['success'] = false;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_NUMBERS_FAILED');
                if ($model->getError()) {
                    $response['message'] .= ' ' . $model->getError();
                }
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            } else {
                $response['success'] = true;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_NUMBERS_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
                $response['identifiers'] = $result['identifiers'];
                $response['identifier_batch_id'] = $result['identifier_batch_id'];
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

        // http://{SITE}/administrator/?option=com_isbnregistry&task=publisherisbnranges.getIsbnNumbers&publisherId=1&publicationId=5
        $mainframe = JFactory::getApplication();
        try {
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            // Get request parameters
            $jinput = JFactory::getApplication()->input->post;
            $publisherId = $jinput->get('publisherId', null, 'int');
            $publicationId = $jinput->get('publicationId', null, 'int');

            // Add request parameters to response
            $response["publisherId"] = $publisherId;
            $response["publicationId"] = $publicationId;

            // Get model
            $model = $this->getModel();
            // Get array of identifiers numbers
            $identifiers = $model->generateIdentifiers($publisherId, 0, $publicationId);

            // Check if the array is empty
            if (empty($identifiers['identifiers'])) {
                $response['success'] = false;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_NUMBER_FAILED');
                if ($model->getError()) {
                    $response['message'] .= ' ' . $model->getError();
                }
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            } else {
                // Results
                $response['success'] = true;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_NUMBER_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
                $response['publication_identifiers'] = $identifiers['identifiers'];
                $response['identifier_batch_id'] = $identifiers['identifier_batch_id'];
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
