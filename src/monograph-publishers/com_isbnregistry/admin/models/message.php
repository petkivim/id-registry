<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Message Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelMessage extends JModelAdmin {

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $type    The table name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  JTable  A JTable object
     *
     * @since   1.6
     */
    public function getTable($type = 'Message', $prefix = 'IsbnregistryTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed    A JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm(
                'com_isbnregistry.message', 'message', array(
            'control' => 'jform', 'load_data' => $loadData
                )
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since   1.6
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState(
                'com_isbnregistry.edit.message.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }
        // Get access to request parameters
        $input = JFactory::getApplication()->input;
        // Get code parameter
        $code = $input->get('code', '', 'string');
        // If code is not empty, data must be modified
        if (!empty($code)) {
            // Update code value
            $code = 'message_type_' . $code;
            // Get publisher id
            $publisherId = $input->get('publisherId', 0, 'int');
            // Get publication id
            $publicationId = $input->get('publicationId', 0, 'int');
            // Get identifier batch id
            $identifierBatchId = $input->get('batchId', 0, 'int');
            // Update $data variables values
            $this->loadTemplate($data, $code, $publisherId, $publicationId, $identifierBatchId);
        }

        return $data;
    }

    private function loadTemplate($message, $code, $publisherId, $publicationId, $identifierBatchId) {
        // Add configuration helper file
        require_once JPATH_COMPONENT . '/helpers/configuration.php';
        // Check that code is valid
        if (!ConfigurationHelper::isValidParameterName($code)) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_ERROR_MESSAGE_INVALID_CODE_PARAMETER'), 'error');
            return false;
        }
        // Get component parameters
        $params = JComponentHelper::getParams('com_isbnregistry');
        // Get message type id for the given code
        $messageTypeId = $params->get($code, 0);
        // Check that the value has been defined
        if ($messageTypeId == 0) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_ERROR_MESSAGE_NO_DEFAULT_MESSAGE_TYPE_FOUND'), 'warning');
            return false;
        }
        // Set message type id
        $message->message_type_id = $messageTypeId;
        // Load publisher model
        $publisherModel = JModelLegacy::getInstance('publisher', 'IsbnregistryModel');
        // Load publisher
        $publisher = $publisherModel->getPublisherById($publisherId);
        // Check the result
        if (!$publisher) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_ERROR_MESSAGE_NO_PUBLISHER_FOUND'), 'warning');
            return false;
        }
        // Set publisher id
        $message->publisher_id = $publisherId;
        // Set publisher language code by default
        $message->lang_code = $publisher->lang_code;
        // Update recipient
        $message->recipient = $publisher->email;

        // Load message template model
        $messageTemplateModel = JModelLegacy::getInstance('messagetemplate', 'IsbnregistryModel');
        // Load template
        $template = $messageTemplateModel->getMessageTemplateByTypeAndLanguage($messageTypeId, $message->lang_code);
        // Check that we found a template
        if (!$template) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_ERROR_MESSAGE_NO_TEMPLATE_FOUND'), 'warning');
            return false;
        }
        // Update template id
        $message->message_template_id = $template->id;
        // Set subject
        $message->subject = $template->subject;
        // Set message
        $message->message = $template->message;

        // Check if identifiers are related to a publication
        $isPublicationIdentifierCreated = ConfigurationHelper::isPublicationIdentifierCreated($code);
        // If so, load publication
        if ($isPublicationIdentifierCreated) {
            // Load publication model
            $publicationModel = JModelLegacy::getInstance('publication', 'IsbnregistryModel');
            // Load publication
            $publication = $publicationModel->getPublicationById($publicationId);
            // Check the result
            if (!$publication) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_ERROR_MESSAGE_NO_PUBLICATION_FOUND'), 'warning');
                return false;
            }
            // Set publication id
            $message->publication_id = $publicationId;
            // Set publication lang code
            $message->lang_code = $publication->lang_code;
            // Update recipient
            $message->recipient = $publication->email;
        }

        // Load publisher identifier
        // Do we need to load ISBN or ISMN?
        $type = ConfigurationHelper::isIsbn($code) ? 'isbn' : 'ismn';
        // Load model
        $publisherIdentifierRangeModel = JModelLegacy::getInstance('publisher' . $type . 'range', 'IsbnregistryModel');
        // Load active publisher identifier range
        $publisherIdentifierRange = $publisherIdentifierRangeModel->getActivePublisherIdentifierRange($publisherId);
        // Check that we have a result
        if ($publisherIdentifierRange) {
            // Add publisher identifier to the template
            $message->message = $this->filterPublisherIdentifier($message->message, $publisherIdentifierRange->publisher_identifier);
        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_ERROR_MESSAGE_NO_ACTIVE_PUBLISHER_IDENTIFIERS_FOUND'), 'warning');
        }

        // Check if publication identifiers should be added to the message
        $addPublicationIdentifiers = ConfigurationHelper::addPublicationIdentifiers($code);
        // Add identifiers if needed
        if ($addPublicationIdentifiers) {
            // Load identifier model
            $identifierModel = JModelLegacy::getInstance('identifier', 'IsbnregistryModel');
            // Get identifiers
            $identifiers = $identifierModel->getIdentifiersArray($identifierBatchId);
            // Add identifiers
            $message->message = $this->filterPublicationIdentifiers($message->message, $identifiers);
        }
        // Filter message
        $message->message = $this->filterMessage($message->message, $publisher);
        // Operation was successfull
        return true;
    }

    /**
     * Filters the message body and replaces variables with real values,
     * e.g. date, username etc.
     * @param string $messageBody message body to be processed
     * @param Publisher publisher object related to the message
     * @return string processed message body
     */
    private function filterMessage($messageBody, $publisher) {
        $messageBody = $this->filterDate($messageBody);
        $messageBody = $this->filterUser($messageBody);
        $messageBody = $this->filterAddress($messageBody, $publisher->address, $publisher->zip, $publisher->city);
        return $messageBody;
    }

    private function filterPublicationIdentifiers($messageBody, $identifiers) {
        return str_replace("#IDENTIFIERS#", implode('<br />', $identifiers), $messageBody);
    }

    private function filterPublisherIdentifier($messageBody, $identifier) {
        return str_replace("#IDENTIFIER#", $identifier, $messageBody);
    }

    private function filterDate($messageBody) {
        // Get date and user
        $date = JFactory::getDate()->format('d.m.Y');
        return str_replace("#DATE#", $date, $messageBody);
    }

    private function filterUser($messageBody) {
        $user = JFactory::getUser();
        return str_replace("#USER#", $user->name, $messageBody);
    }

    private function filterAddress($messageBody, $street, $zip, $city) {
        return str_replace("#ADDRESS#", $street . '<br />' . $zip . ' ' . $city, $messageBody);
    }

}
