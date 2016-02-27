<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * ISSN Range Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @since       1.0.0
 */
class IssnregistryControllerIssnrange extends JControllerForm {

    public function getIssn() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
        // http://{SITE}/administrator/index.php?option=com_issnregistry&task=issnrange.getIssn&publicationId=1
        $mainframe = JFactory::getApplication();
        try {
            // Get request parameters
            $jinput = JFactory::getApplication()->input->post;
            $publicationId = $jinput->get('publicationId', 0, 'int');

            // Create response array
            $response = array();
            // Add request parameters to response
            $response['publicationId'] = $publicationId;

            // Get model
            $model = $this->getModel();
            // Get new ISSN
            $result = $model->getIssn($publicationId);

            // Generate response
            $response['success'] = empty($result) ? false : true;
            if (empty($result)) {
                $response['title'] = JText::_('COM_ISSNREGISTRY_RESPONSE_ERROR_TITLE');
                $response['message'] = JText::_('COM_ISSNREGISTRY_ISSN_RANGE_GET_ISSN_FAILED');
                if ($model->getError()) {
                    $response['message'] .= ' ' . $model->getError();
                }
            } else {
                $response['message'] = JText::_('COM_ISSNREGISTRY_ISSN_RANGE_GET_ISSN_SUCCESS');
                $response['title'] = JText::_('COM_ISSNREGISTRY_RESPONSE_SUCCESS_TITLE');
            }
            // Add new ISSN to response
            $response["issn"] = $result;

            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISSNREGISTRY_ISSN_RANGE_GET_ISSN_FAILED');
            $response['title'] = JText::_('COM_ISSNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

    public function deleteIssn() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
        // http://{SITE}/administrator/index.php?option=com_issnregistry&task=issnrange.deleteIssn&issn=xxxx-xxxx
        $mainframe = JFactory::getApplication();
        try {
            // Get request parameters
            $jinput = JFactory::getApplication()->input->post;
            $issn = $jinput->get('issn', '', 'string');

            // Create response array
            $response = array();
            // Add request parameters to response
            $response['issn'] = $issn;

            // Get model
            $model = $this->getModel();
            // Delete ISSN
            $result = $model->deleteIssn($issn);

            // Generate response
            $response['success'] = $result;
            if (!$result) {
                $response['title'] = JText::_('COM_ISSNREGISTRY_RESPONSE_ERROR_TITLE');
                $response['message'] = JText::_('COM_ISSNREGISTRY_ISSN_RANGE_DELETE_ISSN_FAILED');
                if ($model->getError()) {
                    $response['message'] .= ' ' . $model->getError();
                }
            } else {
                $response['message'] = JText::_('COM_ISSNREGISTRY_ISSN_RANGE_DELETE_ISSN_SUCCESS');
                $response['title'] = JText::_('COM_ISSNREGISTRY_RESPONSE_SUCCESS_TITLE');
            }
            // Add new ISSN to response
            $response["issn"] = $result;

            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISSNREGISTRY_ISSN_RANGE_DELETE_ISSN_FAILED');
            $response['title'] = JText::_('COM_ISSNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

}
