<?php

// Get Joomla! framework
define('_JEXEC', 1);
define('JPATH', realpath(dirname(__FILE__) . '/../../../..'));
define('JPATH_BASE', realpath(dirname(__FILE__) . '/../../..'));
define('JPATH_COMPONENT', JPATH_BASE . '/components/com_isbnregistry');
require_once ( JPATH_BASE . '/includes/defines.php' );
require_once ( JPATH_BASE . '/includes/framework.php' );
// Include component models
JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');
// Include component tables
JTable::addIncludePath(JPATH_COMPONENT . '/tables');

// Check that argument count is 2
// $ php groupmessages-script.php <groupMessageId>
if (sizeof($argv) != 2) {
    exit(1);
}
// Get group message id
$groupMessageId = (int) $argv[1];
// Check the value and exit if value is 0
if ($groupMessageId == 0) {
    exit(1);
}
// Load group message model
$groupMessageModel = JModelLegacy::getInstance('groupmessage', 'IsbnregistryModel');
// Load group message object by the given id
$groupMessage = $groupMessageModel->getItem($groupMessageId);
// Check that group message object was found
if ($groupMessage->id == null) {
    exit(1);
}

// From comma separated string to array
if ($groupMessage->isbn_categories) {
    $groupMessage->isbn_categories = $groupMessageModel->fromStrToArray($groupMessage->isbn_categories);
}
if ($groupMessage->ismn_categories) {
    $groupMessage->ismn_categories = $groupMessageModel->fromStrToArray($groupMessage->ismn_categories);
}

// Load publisher model
$publisherModel = JModelLegacy::getInstance('publisher', 'IsbnregistryModel');
// Get ISBN publishers matching the conditions
$isbnPublishers = $publisherModel->getPublishersByCategory($groupMessageModel->prepareForQuery($groupMessage->isbn_categories, 6), $groupMessage->has_quitted, 'isbn');
// Get ISMN publishers matching the conditions
$ismnPublishers = $publisherModel->getPublishersByCategory($groupMessageModel->prepareForQuery($groupMessage->ismn_categories, 8), $groupMessage->has_quitted, 'ismn');

// Load message template model
$messageTemplateModel = JModelLegacy::getInstance('messagetemplate', 'IsbnregistryModel');
// Get templates
$templates = $messageTemplateModel->getMessageTemplatesByType($groupMessage->message_type_id);
// Load message type model
$messageTypeModel = JModelLegacy::getInstance('messagetype', 'IsbnregistryModel');
// Get installed languages
$languages = $messageTypeModel->getInstalledLanguages();
// Check that there's a template in all the languages
$templateHash = $groupMessageModel->checkTemplatesAndLanguages($templates, $languages);
// If result is empty, templates are missing
if (empty($templateHash)) {
    $this->setError(JText::_('COM_ISBNREGISTRY_ERROR_GROUP_MESSAGE_INVALID_MESSAGE_TYPE'));
    return false;
}
// Merge publisher arrays
$publishers = $groupMessageModel->mergePublisherArrays($isbnPublishers, $ismnPublishers);

// Get component parameters
$params = JComponentHelper::getParams('com_isbnregistry');
// Get sleep time between emails in microseconds
$interval = $params->get('email_interval_microseconds', 500000);

// Load message  model
$messageModel = JModelLegacy::getInstance('message', 'IsbnregistryModel');

// Set some counters
$successCount = 0;
$failCount = 0;
$noEmailCount = 0;

// Loop through publishers
foreach ($publishers as $publisher) {
    if (empty($publisher->email)) {
        $noEmailCount++;
        continue;
    }
    // Get template
    $template = $templateHash[$publisher->lang_code];

    // Get new Message instance
    $messageNew = JModelLegacy::getInstance('message', 'IsbnregistryModel');
    $messageNew->message = $template->message;
    $messageNew->recipient = $publisher->email;

    // Create array for message data
    $message = array(
        'publisher_id' => $publisher->id,
        'message_type_id' => $template->message_type_id,
        'message_template_id' => $template->id,
        'group_message_id' => $groupMessageId,
        'recipient' => $publisher->email,
        'subject' => $template->subject,
        'message' => $template->message,
        'lang_code' => $publisher->lang_code,
        'sent_by' => $groupMessage->created_by
    );
    // Filter message
    $message['message'] = $messageModel->filterMessage($messageNew, $publisher, "");
    // Save message
    if ($messageModel->save($message)) {
        $successCount++;
    } else {
        $failCount++;
    }
    // Sleep 0,5 secs between loops, 0,5s = 500000 microseconds
    usleep($interval);
}
// Update results to DB
$groupMessageModel->updateResults($groupMessageId, $successCount, $failCount, $noEmailCount);
exit();
?>
