<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivim�ki
 * @copyright	Copyright (C) 2016 Petteri Kivim�ki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Message Model
 *
 * @since  1.0.0
 */
class IssnregistryModelMessage extends JModelAdmin {

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
    public function getTable($type = 'Message', $prefix = 'IssnregistryTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   1.6
     */
    public function save($data) {
        $table = $this->getTable();

        // Bind the data.
        if (!$table->bind($data)) {
            $this->setError($table->getError());

            return false;
        }
        // Get component parameters
        $params = JComponentHelper::getParams('com_issnregistry');

        // Check if email should be sent
        if ($params->get('send_email', false)) {
            // Get email from address
            $from = $params->get('email_from', '');
            // If empty, use site's email address
            if (empty($from)) {
                $config = JFactory::getConfig();
                $from = $config->get('mailfrom');
            }
            // Create sender array
            $sender = array(
                $from,
                ''
            );

            // Get and configure mailer
            $mailer = JFactory::getMailer();
            $mailer->setSender($sender);
            $mailer->addRecipient($table->recipient);
            $mailer->setSubject($table->subject);
            $mailer->isHTML(true);
            $mailer->setBody($table->message);

            $send = $mailer->Send();
            if ($send !== true) {
                JFactory::getApplication()->enqueueMessage($send->__toString(), 'error');
                return false;
            }
        }
        // Store the data.
        if (!$table->store()) {
            $this->setError($table->getError());
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ERROR_MESSAGE_SAVE_TO_DB_FAILED'), 'error');
            return false;
        }
        // Update form status if form has been set
        if ($table->form_id > 0) {
            // Load form model
            $formModel = JModelLegacy::getInstance('form', 'IssnregistryModel');
            // Update form status
            $formModel->setStatusCompleted($table->form_id);
        }
        return true;
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
                'com_issnregistry.message', 'message', array(
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
                'com_issnregistry.edit.message.data', array()
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
            // Get form id
            $formId = $input->get('formId', 0, 'int');
            // Get publisher id
            $publisherId = $input->get('publisherId', 0, 'int');
            // Update $data variables values
            $this->loadTemplate($data, $code, $formId, $publisherId);
        }

        return $data;
    }

    private function loadTemplate($message, $code, $formId, $publisherId) {
        // Add configuration helper file
        require_once JPATH_COMPONENT . '/helpers/configuration.php';
        // Check that code is valid
        if (!ConfigurationHelper::isValidParameterName($code)) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ERROR_MESSAGE_INVALID_CODE_PARAMETER'), 'error');
            return false;
        }
        // Get component parameters
        $params = JComponentHelper::getParams('com_issnregistry');

        // Check if this is a form handled message
        $isFormHandled = ConfigurationHelper::isFormHandled($code);
        // Check if this is a publisher summary message
        $isPublisherSummary = ConfigurationHelper::isPublisherSummary($code);

        // Init variables
        $form = null;

        // Get message type id for the given code
        $messageTypeId = $params->get($code, 0);
        // Check that the value has been defined
        if ($messageTypeId == 0) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ERROR_MESSAGE_NO_DEFAULT_MESSAGE_TYPE_FOUND'), 'warning');
            return false;
        }
        // Set message type id
        $message->message_type_id = $messageTypeId;

        // Load publisher model
        $publisherModel = JModelLegacy::getInstance('publisher', 'IssnregistryModel');
        // Variable that tell if publisher email address should be used
        $usePublisherEmail = false;

        if ($isFormHandled) {
            // Load form model
            $formModel = JModelLegacy::getInstance('form', 'IssnregistryModel');
            // Load form
            $form = $formModel->getItem($formId);
            // Check that we found a form
            if (!$form) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ERROR_MESSAGE_NO_FORM_FOUND'), 'warning');
                return false;
            }
            // Set form id
            $message->form_id = $form->id;
            // Set publisher id
            $publisherId = $form->publisher_id;
            // Set language code
            $message->lang_code = $form->lang_code;
            // Update recipient
            $message->recipient = $form->email;
            // If email is empty, use publisher email
            if (empty($message->recipient)) {
                $usePublisherEmail = true;
            }
        }
        // Load publisher
        $publisher = $publisherModel->getPublisherById($publisherId);
        // Check the result
        if (!$publisher) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ERROR_MESSAGE_NO_PUBLISHER_FOUND'), 'warning');
            return false;
        }
        if ($isPublisherSummary || $usePublisherEmail) {
            // Set language code
            $message->lang_code = $publisher->lang_code;
            // Update recipient
            $message->recipient = $this->getEmail($publisher);
        }

        // Set publisher id
        $message->publisher_id = $publisherId;

        // Load message template model
        $messageTemplateModel = JModelLegacy::getInstance('messagetemplate', 'IssnregistryModel');
        // Load template
        $template = $messageTemplateModel->getMessageTemplateByTypeAndLanguage($messageTypeId, $message->lang_code);
        // Check that we found a template
        if (!$template) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ERROR_MESSAGE_NO_TEMPLATE_FOUND'), 'warning');
            return false;
        }
        // Update template id
        $message->message_template_id = $template->id;
        // Set subject
        $message->subject = $template->subject;
        // Set message
        $message->message = $template->message;

        // Load language file - translations are used
        // in "filterPublications" function later
        JFactory::getLanguage()->load('com_issnregistry_email', JPATH_ADMINISTRATOR, $message->lang_code, true);

        // Init publications array
        $publications = array();

        // If so, load publications
        if ($isFormHandled || $isPublisherSummary) {
            // Load publication model
            $publicationModel = JModelLegacy::getInstance('publication', 'IssnregistryModel');
            // Load publications
            if ($isFormHandled) {
                $publications = $publicationModel->getPublicationsByFormId($formId);
            } else {
                $publications = $publicationModel->getPublicationsByPublisherId($publisherId);
            }
            // Check the result
            if (!$publications) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ERROR_MESSAGE_NO_PUBLICATIONS_FOUND'), 'warning');
            }
            // Add publications info
            $message->message = $this->filterPublications($message->message, $publications);
            // Add publication title
            $message->message = $this->filterPublicationTitle($message->message, $publications);
            // Add publication title to subject
            $message->subject = $this->filterPublicationTitle($message->subject, $publications);
        }
        // Filter message
        $message->message = $this->filterMessage($message->message, $form, $publisher);
        // Operation was successfull
        return true;
    }

    /**
     * Filters the message body and replaces variables with real values,
     * e.g. date, username etc.
     * @param string $messageBody message body to be processed
     * @param Form $form form object related to the message
     * @param Publisher publisher object related to the message
     * @return string processed message body
     */
    public function filterMessage($messageBody, $form, $publisher) {
        $messageBody = $this->filterDate($messageBody);
        $messageBody = $this->filterUser($messageBody);

        // Filter #ADDRESS#
        if ($form == null || (empty($form->address) && empty($form->zip) && empty($form->city))) {
            $messageBody = $this->filterAddress($messageBody, $publisher->address, $publisher->zip, $publisher->city);
        } else {
            $messageBody = $this->filterAddress($messageBody, $form->address, $form->zip, $form->city);
        }

        // Filter #PUBLISHER#
        $messageBody = str_replace("#PUBLISHER#", $publisher->official_name, $messageBody);

        // Filter #CONTACT_PERSON#
        if ($form == null || empty($form->contact_person)) {
            $messageBody = str_replace("#CONTACT_PERSON#", $this->getContactPerson($publisher), $messageBody);
        } else {
            $messageBody = str_replace("#CONTACT_PERSON#", $form->contact_person, $messageBody);
        }

        // Filter #EMAIL#
        if ($form == null || empty($form->email)) {
            $messageBody = str_replace("#EMAIL#", $this->getEmail($publisher), $messageBody);
        } else {
            $messageBody = str_replace("#EMAIL#", $form->email, $messageBody);
        }

        return $messageBody;
    }

    private function filterPublications($messageBody, $publications) {
        $html = '';
        $counter = 0;
        foreach ($publications as $publication) {
            $html .= $publication->title . '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ISSN ' . $publication->issn;
            $html .= ' (' . JText::_('COM_ISSNREGISTRY_EMAIL_PUBLICATION_MEDIUM_' . $publication->medium) . ')<br />';
            if ($counter < sizeof($publications) - 1) {
                $html .= '<br />';
            }
            $counter++;
        }
        return str_replace("#PUBLICATIONS#", $html, $messageBody);
    }

    private function filterPublicationTitle($messageBody, $publications) {
        $html = sizeof($publications) > 0 ? $publications[0]->title : '';
        return str_replace("#TITLE#", $html, $messageBody);
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

    private function getEmail($publisher) {
        $result = '';
        // Get contact persons as JSON
        $json = json_decode($publisher->contact_person);
        // Check that we have a JSON object
        if (!empty($json)) {
            // Loop through email adresses
            foreach ($json->{'email'} as $email) {
                // Check that email is not empty
                if (!empty($email)) {
                    $result = $email;
                }
            }
        }
        // If no contact person email is found, use publisher's common email
        if (empty($result)) {
            $result = $publisher->email_common;
        }
        return $result;
    }

    private function getContactPerson($publisher) {
        // Get email that's used as recipient
        $email = $this->getEmail($publisher);
        // Variable for the result
        $result = '';
        // Get contact persons as JSON 
        $json = json_decode($publisher->contact_person);
        // Check that we have a JSON object
        if (!empty($json)) {
            // Loop through names
            for ($i = 0; $i < sizeof($json->{'name'}); $i++) {
                // Check that name is not empty and that email addresses match
                if (!empty($json->{'name'}[$i]) && strcmp($email, $json->{'email'}[$i]) == 0) {
                    $result = $json->{'name'}[$i];
                }
            }
        }
        return $result;
    }

    /**
     * Get all message ids related to the publisher identified by the given
     * publisher id.
     * @param int $publisherId publisher id
     * @return array array of message ids
     */
    public function getMessageIdsByPublisher($publisherId) {
        // Get db access
        $table = $this->getTable();
        // Get results 
        return $table->getMessageIdsByPublisher($publisherId);
    }

    /**
     * Delete all messages related to the publisher identified by
     * the given publisher id.
     * @param int $publisherId publisher id
     * @return int number of deleted rows
     */
    public function deleteByPublisherId($publisherId) {
        // Get list of messages that were sent to the publisher and
        // that are not related to a form
        $messageIds = $this->getMessageIdsByPublisher($publisherId);
        // Remove the messages        
        $this->delete($messageIds);
        // Get db access
        $table = $this->getTable();
        // Remove reference to the publisher from messages that are
        // related to a form
        $table->resetPublisherId($publisherId);
    }

    /**
     * Delete all messages related to the form identified by
     * the given form id.
     * @param int $formId form id
     * @return int number of deleted rows
     */
    public function deleteByFormId($formId) {
        // Get db access
        $table = $this->getTable();
        // Get ids
        $messageIds = $table->getMessageIdsByForm($formId);
        // Delete objects
        $this->delete($messageIds);
    }

    /**
     * Return the number of messages related to the form identified by the
     * given id
     * @param integer $formId form id
     * @return integer number of messages related to the given form id
     */
    public function getMessageCountByFormId($formId) {
        // Get db access
        $table = $this->getTable();
        // Get results 
        return $table->getMessageCountByFormId($formId);
    }

    /**
     * Delete all messages related to the group message identified by
     * the given group message id.
     * @param int $groupMessageId group message id
     * @return int number of deleted rows
     */
    public function deleteByGroupMessageId($groupMessageId) {
        // Get db access
        $table = $this->getTable();
        // Get message ids
        $messageIds = $table->getMessageIdsByGroupMessageId($groupMessageId);
        // Delete messages
        $this->delete($messageIds);
    }

}
