<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivim�ki
 * @copyright	Copyright (C) 2015 Petteri Kivim�ki. All rights reserved.
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
abstract class IsbnregistryControllerAbstractIdentifierRange extends JControllerForm {

    abstract public function getIdentifierType();

    public function getRange() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        $mainframe = JFactory::getApplication();
        try {
            // Get request parameters
            $jinput = JFactory::getApplication()->input->post;
            $publisherId = $jinput->get('publisherId', null, 'int');
            $rangeId = $jinput->get('rangeId', null, 'int');

            // Create response array
            $response = array();
            // Add request parameters to response
            $response["publisherId"] = $publisherId;
            $response["rangeId"] = $rangeId;

            // Get model
            $model = $this->getModel();
            // Get new publisher identifier
            $result = $model->getPublisherIdentifier($rangeId, $publisherId);

            // Genrate response
            $response['success'] = $result == 0 ? false : true;
            if ($result == 0) {
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_RANGE_FAILED');
                if ($model->getError()) {
                    $response['message'] .= ' ' . $model->getError();
                }
            } else {
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_RANGE_SUCCESS');
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
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_' . strtoupper($this->getIdentifierType()) . '_RANGE_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

}
