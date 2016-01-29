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
 * Identifier Batch Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @since       1.0.0
 */
class IsbnregistryControllerIdentifierBatch extends JControllerForm {

    public function delete() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        $mainframe = JFactory::getApplication();
        try {
            // Get request parameters
            $batchId = JRequest::getVar("batchId", null, "post", "int");
            // Create response array
            $response = array();
            // Add request parameters to response
            $response["batchId"] = $batchId;

            // Get model
            $model = $this->getModel();
            // Get new publisher identifier
            $result = $model->safeDelete($batchId);

            // Genrate response
            $response['success'] = $result == 0 ? false : true;
            if ($result == 0) {
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_IDENTIFIER_BATCH_FAILED');
                if ($model->getError()) {
                    $response['message'] .= ' ' . $model->getError();
                }
            } else {
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_IDENTIFIER_BATCH_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
            }
            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLISHER_DELETE_IDENTIFIER_BATCH_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

}
