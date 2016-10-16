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
        $params = JComponentHelper::getParams('com_isbnregistry');

        // Init variable for filename
        $filename = '';
        // Load identifier model
        $identifierModel = JModelLegacy::getInstance('identifier', 'IsbnregistryModel');
        // Get identifiers
        $identifiers = $identifierModel->getIdentifiersArray($table->batch_id);
        // Get identifiers attachment limit
        $attachmentLimit = $params->get('identifiers_attachment_limit', 0);
        // If number of identifiers is greater than the limit, 
        // identifiers must be sent as an attachment
        $table->has_attachment = ($attachmentLimit > 0 && sizeof($identifiers) > $attachmentLimit ? true : false);
        // Set has attachment value
        if ($table->has_attachment) {
            $folder = JPATH_COMPONENT . '/email/';
            // Get time in milliseconds for filename
            list($usec, $sec) = explode(" ", microtime());
            $time = date("YmdHis", $sec) . intval(round($usec * 1000));
            // Set attachment name
            $table->attachment_name = $time . '.txt';
            // Set filename
            $filename = $folder . $time . '.txt';
            // Load identifier batch model
            $identifierBatchModel = JModelLegacy::getInstance('identifierbatch', 'IsbnregistryModel');
            // Get identifier type
            $identifierType = $identifierBatchModel->getIdentifierType($table->batch_id);
            // Get template for attchment header
            $attachmentTemplateId = $params->get('message_type_attachment_header_' . strtolower($identifierType), 0);
            // Variable for attachmen header
            $attachmentHeader = '';
            // Get template
            if ($attachmentTemplateId > 0) {
                // Load message template model
                $messageTemplateModel = JModelLegacy::getInstance('messagetemplate', 'IsbnregistryModel');
                // Load template
                $template = $messageTemplateModel->getMessageTemplateByTypeAndLanguage($attachmentTemplateId, $table->lang_code);
                // Check that we found a template
                if ($template) {
                    $attachmentHeader = strip_tags($template->message);
                }
            }
            // Write identifiers to file
            $this->writeIdentifiersToFile($identifiers, $filename, $attachmentHeader);
        }



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

            // Get cc address
            $cc = $params->get('email_cc', '');

            // Get and configure mailer
            $mailer = JFactory::getMailer();
            $mailer->setSender($sender);
            $mailer->addRecipient($table->recipient);
            $mailer->setSubject($table->subject);
            $mailer->isHTML(true);
            $mailer->setBody($table->message);

            // Add cc if not empty
            if (!empty($cc)) {
                $mailer->addBcc($cc);
            }

            if ($table->has_attachment) {
                $mailer->addAttachment($filename);
            }

            $send = $mailer->Send();
            if ($send !== true) {
                JFactory::getApplication()->enqueueMessage($send->__toString(), 'error');
                return false;
            }
        }
        // Store the data.
        if (!$table->store()) {
            if ($table->has_attachment) {
                unlink($filename);
            }
            $this->setError($table->getError());
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_ERROR_MESSAGE_SAVE_TO_DB_FAILED'), 'error');
            return false;
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
        // Variable for publication title
        $title = '';

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
            // Set publication title
            $title = $publication->title;
            // Get the id of the publisher that represents author publishers
            $authorPublisherId = $params->get('author_publisher_id_isbn', 0);

            // Is the publisher author publisher? If true, publisher's name,
            // address and recipient's email must be read from the publication
            if ($authorPublisherId == $publisher->id) {
                // Get publisher name and address - this change is NOT stored
                // to DB
                $publisher->official_name = $publication->official_name;
                $publisher->address = $publication->address;
                $publisher->zip = $publication->zip;
                $publisher->city = $publication->city;
                // Update recipient
                $message->recipient = $publication->email;
            } else if (!empty($publication->email)) {
                // Update recipient - publication email address is the
                // primary email address
                $message->recipient = $publication->email;
            }
        }

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
        // Filter subject for title field
        $message->subject = $this->filterPublicationTitle($message->subject, $title);
        // Set message
        $message->message = $template->message;

        // Check if publication identifiers should be added to the message
        $addPublicationIdentifiers = ConfigurationHelper::addPublicationIdentifiers($code);
        // Variable that tells if publisher identifier has been added to the message
        $publisherIdentifierAddedd = false;

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
            $publisherIdentifierAddedd = true;
        } else if ($addPublicationIdentifiers) {
            // Publisher identifier range may have been closed after identifier
            // generation and publisher does not currently have active identifier.
            // Load identifier batch model so that we can use the identifier
            // that was used during identifier generation
            $identifierBatchModel = JModelLegacy::getInstance('identifierbatch', 'IsbnregistryModel');
            // Load batch object
            $identifierBatch = $identifierBatchModel->getItem($identifierBatchId);
            // Check publisher identifier range id value
            if ($identifierBatch->publisher_identifier_range_id > 0) {
                // Get publisher identifier range object used in identifier batch
                $publisherIdentifierRange = $publisherIdentifierRangeModel->getItem($identifierBatch->publisher_identifier_range_id);
                // Check that we have a result
                if ($publisherIdentifierRange) {
                    // Add publisher identifier to the template
                    $message->message = $this->filterPublisherIdentifier($message->message, $publisherIdentifierRange->publisher_identifier);
                    $publisherIdentifierAddedd = true;
                }
            }
        } else {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_ERROR_MESSAGE_NO_ACTIVE_PUBLISHER_IDENTIFIERS_FOUND'), 'warning');
        }

        // Load language file - translations are used
        // in "filterPublicationIdentifiers" function later
        JFactory::getLanguage()->load('com_isbnregistry_email', JPATH_ADMINISTRATOR, $message->lang_code, true);

        // Add identifiers if needed
        if ($addPublicationIdentifiers) {
            // Load identifier model
            $identifierModel = JModelLegacy::getInstance('identifier', 'IsbnregistryModel');
            // Get identifiers
            $identifiers = $identifierModel->getIdentifiers($identifierBatchId);
            // Set batch id
            $message->batch_id = $identifierBatchId;
            // Get identifiers attachment limit
            $attachmentLimit = $params->get('identifiers_attachment_limit', 0);
            // If number of identifiers is greater than the limit, 
            // identifiers must be sent as an attachment
            $useAttachment = ($attachmentLimit > 0 && sizeof($identifiers) > $attachmentLimit ? true : false);
            // Set has attachment value
            $message->has_attachment = $useAttachment;
            // Add identifiers
            $message->message = $this->filterPublicationIdentifiers($message->message, $identifiers, $useAttachment, strtoupper($type));
            // Check if publisher identifier has been added already
            if (!$publisherIdentifierAddedd) {
                // Get publisher identifier range object used in the first identifier
                $publisherIdentifierRange = $publisherIdentifierRangeModel->getItem($identifiers[0]->publisher_identifier_range_id);
                // Check that we have a result
                if ($publisherIdentifierRange) {
                    // Add publisher identifier to the template
                    $message->message = $this->filterPublisherIdentifier($message->message, $publisherIdentifierRange->publisher_identifier);
                    $publisherIdentifierAddedd = true;
                } else {
                    JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_ERROR_MESSAGE_NO_PUBLISHER_IDENTIFIERS_FOUND'), 'warning');
                }
            }
        }
        // Filter message
        $message->message = $this->filterMessage($message, $publisher, $title);
        // Operation was successfull
        return true;
    }

    /**
     * Filters the message body and replaces variables with real values,
     * e.g. date, username etc.
     * @param Message $message message object to be processed
     * @param Publisher publisher object related to the message
     * @param string $title publication title
     * @return string processed message body
     */
    public function filterMessage($message, $publisher, $title) {
        $messageBody = $this->filterDate($message->message);
        $messageBody = $this->filterUser($messageBody);
        $messageBody = $this->filterEmail($messageBody, $message->recipient);
        $messageBody = $this->filterPublisher($messageBody, $publisher);
        $messageBody = $this->filterAddress($messageBody, $publisher->address, $publisher->zip, $publisher->city);
        $messageBody = $this->filterPublicationTitle($messageBody, $title);
        return $messageBody;
    }

    private function filterPublicationIdentifiers($messageBody, $identifiers, $useAttachment, $identifierType) {
        if ($useAttachment) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_MESSAGE_IDENTIFIERS_IN_ATTACHMENT'), 'notice');
            return str_replace("#IDENTIFIERS#", '', $messageBody);
        } else {
            $html = '';
            foreach ($identifiers as $identifier) {
                $html .= empty($html) ? '' : '<br />';
                $html .= $identifierType . ' ' . $identifier->identifier;
                if (!empty($identifier->publication_type)) {
                    $html .= ' (' . JText::_('COM_ISBNREGISTRY_EMAIL_IDENTIFIER_TYPE_' . $identifier->publication_type) . ')';
                }
            }
            return str_replace("#IDENTIFIERS#", $html, $messageBody);
        }
    }

    private function filterPublisher($messageBody, $publisher) {
        return str_replace("#PUBLISHER#", $publisher->official_name, $messageBody);
    }

    private function filterPublicationTitle($messageBody, $title) {
        return str_replace("#TITLE#", $title, $messageBody);
    }

    private function filterPublisherIdentifier($messageBody, $identifier) {
        return str_replace("#IDENTIFIER#", $identifier, $messageBody);
    }

    private function filterEmail($messageBody, $email) {
        return str_replace("#EMAIL#", $email, $messageBody);
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

    /**
     * Writes the given identifiers to a file.
     * @param array $identifiers identifiers to be written
     * @param string $filename name of the file
     * @param $attachmentHeader header that's written before identifiers
     * @return boolean true on success
     */
    private function writeIdentifiersToFile($identifiers, $filename, $attachmentHeader) {
        $file = fopen($filename, "w") or die("Unable to open file!");
        fwrite($file, $attachmentHeader . "\r\n\r\n");
        foreach ($identifiers as $identifier) {
            fwrite($file, $identifier . "\r\n");
        }
        fclose($file);
        return true;
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
     */
    public function deleteByPublisherId($publisherId) {
        $messageIds = $this->getMessageIdsByPublisher($publisherId);
        $this->delete($messageIds);
    }

    /**
     * Delete all messages related to the batch identified by
     * the given batch id.
     * @param int $batchId batch id
     */
    public function deleteByBatchId($batchId) {
        // Get db access
        $table = $this->getTable();
        // Get ids
        $messageIds = $table->getMessageIdsByBatchId($batchId);
        $this->delete($messageIds);
    }

    /**
     * Return the number of messages related to the batch identified by the
     * given id
     * @param integer $batchId batch id
     * @return integer number of messages related to the given batch id
     */
    public function getMessageCountByBatchId($batchId) {
        // Get db access
        $table = $this->getTable();
        // Get results 
        return $table->getMessageCountByBatchId($batchId);
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

    /**
     * Get all batch ids and message ids related to the publisher 
     * identified by the given publisher id. The result is returned as an
     * associative array where batch id is the key and the last message id
     * related to the batch id is the value.
     * @param int $publisherId publisher id
     * @return array array of batch ids and message ids
     */
    public function getBatchIdsAndMessageIdsByPublisher($publisherId) {
        // Get db access
        $table = $this->getTable();
        // Get ids
        $ids = $table->getBatchIdsAndMessageIdsByPublisher($publisherId);
        // New array for results
        $results = array();
        // Loop through the ids
        foreach ($ids as $id) {
            $results[$id->batch_id] = $id;
        }
        // Return results
        return $results;
    }

    /**
     * Returns the number of sent messages between the given timeframe.
     * @param JDate $begin begin date
     * @param JDate $end end date
     * @return ObjectList number of sent messages grouped by year and
     * month
     */
    public function getMessageCountByDates($begin, $end) {
        // Get db access
        $table = $this->getTable();
        // Return results
        return $table->getMessageCountByDates($begin, $end);
    }

}
