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
class IsbnregistryControllerPublication extends JControllerForm {

    function getPublicationsWithoutIdentifier() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // http://{SITE}/administrator/?option=com_isbnregistry&task=publication.getPublicationsWithoutIdentifier&publisherId=1&type=(all|isbn|ismn)
        $mainframe = JFactory::getApplication();
        // Get type parameter
        $jinput = JFactory::getApplication()->input->post;
        $type = $jinput->get('type', null, 'string');

        try {
            // Get request parameters
            $publisherId = $jinput->get('publisherId', null, 'int');

            // Create response array
            $response = array();
            // Add request parameters to response
            $response["publisherId"] = $publisherId;
            $response["type"] = $type;

            // Get model
            $model = $this->getModel();
            // Get publications
            $result = $model->getPublicationsWithoutIdentifier($publisherId, $type);
            // Check if the array exists
            if (!isset($result)) {
                $response['success'] = false;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLICATION_GET_PUBLICATIONS_WITHOUT_' . strtoupper($type) . '_IDENTIFIERS_FAILED');
                if ($model->getError()) {
                    $response['message'] .= ' ' . $model->getError();
                }
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            } else {
                $response['success'] = true;
                $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLICATION_GET_PUBLICATIONS_WITHOUT_' . strtoupper($type) . '_IDENTIFIERS_SUCCESS');
                $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_SUCCESS_TITLE');
                $response['publications'] = $result;
            }

            // Set the MIME type for JSON output.
            header('Content-type: application/json; charset=utf-8');

            echo json_encode($response);

            $mainframe->close();
        } catch (Exception $e) {
            http_response_code(500);
            $response['success'] = false;
            $response['message'] = JText::_('COM_ISBNREGISTRY_PUBLICATION_GET_PUBLICATIONS_WITHOUT_' . strtoupper($type) . '_IDENTIFIERS_FAILED');
            $response['title'] = JText::_('COM_ISBNREGISTRY_RESPONSE_ERROR_TITLE');
            echo json_encode($response);
            if (!is_null($mainframe)) {
                $mainframe->close();
            }
        }
    }

    public function download() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // Get publication id
        $id = $this->input->getInt('id');
        // Redirect to raw format
        $this->setRedirect('index.php?option=com_isbnregistry&view=publication&id=' . $id . '&layout=edit&format=raw');
        $this->redirect();
    }

}
