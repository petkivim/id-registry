<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Identifier Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @since       1.0.0
 */
class IsbnregistryControllerIdentifier extends JControllerForm {

    function cancel() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // http://{SITE}/administrator/?option=com_isbnregistry&task=identifier.cancel&identifier=xxx-xxx-xxxxx-x-x
        $mainframe = JFactory::getApplication();
        try {
            // Get request parameters
            $jinput = JFactory::getApplication()->input->post;
            $identifier = $jinput->get('identifier', null, 'string');

            // Create response array
            $response = array();
            // Add request parameters to response
            $response["identifier"] = $identifier;

            // Get model
            $model = $this->getModel();
            // Cancel the given identifier
            $result = $model->cancelIdentifier($identifier);

            // Check if the result is OK and genrate response
            if ($result) {
                $response['success'] = true;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_CANCEL_IDENTIFIER_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
            } else {
                $response['success'] = false;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_CANCEL_IDENTIFIER_FAILED');
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
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_CANCEL_IDENTIFIER_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

    function delete() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // http://{SITE}/administrator/?option=com_isbnregistry&task=identifier.delete&identifier=xxx-xxx-xxxxx-x-x
        $mainframe = JFactory::getApplication();
        try {
            // Get request parameters
            $jinput = JFactory::getApplication()->input->post;
            $identifier = $jinput->get('identifier', null, 'string');

            // Create response array
            $response = array();
            // Add request parameters to response
            $response["identifier"] = $identifier;

            // Get model
            $model = $this->getModel();
            // Delete the given identifier
            $result = $model->cancelIdentifier($identifier, true);

            // Check if the result is OK and genrate response
            if ($result) {
                $response['success'] = true;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_IDENTIFIER_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
            } else {
                $response['success'] = false;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_IDENTIFIER_FAILED');
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
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_IDENTIFIER_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

}
