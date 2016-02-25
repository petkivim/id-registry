<?php

/**
 * @Plugin 	"ID Registry - Serials Publishers - Forms"
 * @version 	1.0.0
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

require_once __DIR__ . '/issnregistryFormsHtmlBuilder.php';
require_once __DIR__ . '/issnregistryFormsHelper.php';
require_once __DIR__ . '/issnregistryFormsLogger.php';

class plgContentIssnregistry_forms extends JPlugin {

    function plgContentIssnregistry_forms(&$subject, $params) {
        parent::__construct($subject, $params);
    }

    public function onContentPrepare($context, &$row, &$params, $page = 0) {
        // Search in the article text the plugin code and exit if not found
        $regex = "%\{serials_pub_form\}%is";
        $found = preg_match_all($regex, $row->text, $matches);

        $count = 0;

        if ($found) {

            foreach ($matches[0] as $value) {
                $html = "";

                // Add plugin css
                $document = JFactory::getDocument();
                $document->addStyleSheet("plugins/content/issnregistry_forms/css/style.css");
                // Add plugin scripts
                JHtml::_('jquery.framework');
                JHtml::_('formbehavior.chosen');
                $document->addScript("plugins/content/issnregistry_forms/scripts/custom.js");

                // Email settings
                $email = $this->params->def('email', '');
                $notifyAdmin = $this->params->def('notify_admin', true);
                // Get max publications count
                $maxPublicationsCount = $this->params->def('max_publications_count', 1);
                
                // Language settings
                $lang = JFactory::getLanguage();
                // Load the language file in the current site language
                $lang->load('plg_content_issnregistry_forms', JPATH_ADMINISTRATOR, $lang->getTag(), true);

                // Get the post variables
                $post = JFactory::getApplication()->input->post;

                // Get submit button values
                $submitApplicationPt1 = $post->get('submit_application_pt1', null, 'string');
                $submitApplicationPt2 = $post->get('submit_application_pt2', null, 'string');
                $submitApplicationPt3 = $post->get('submit_application_pt3', null, 'string');
                $submitApplicationPt4 = $post->get('submit_application_pt4', null, 'string');
                // Get back button values
                $backApplicationPt3 = $post->get('back_application_pt3', null, 'string');
                $backApplicationPt4 = $post->get('back_application_pt4', null, 'string');

                // Process
                if (JSession::checkToken() && isset($submitApplicationPt1)) {
                    // Validate input data
                    $errors = IssnregistryFormsHelper::validateApplicationFormPt1();
                    // If there are no errors, continue processing
                    if (empty($errors)) {
                        // Show the second page
                        $html .= IssnregistryFormsHtmlBuilder::getIssnApplicationFormPt2($maxPublicationsCount);
                    } else {
                        // Show the first page with error messages
                        $html .= IssnregistryFormsHtmlBuilder::getIssnApplicationFormPt1($errors);
                    }
                } else if (JSession::checkToken() && isset($submitApplicationPt2)) {
                    // Validate input data
                    $errors = IssnregistryFormsHelper::validateApplicationFormPt2();
                    // If there are no errors, continue processing
                    if (empty($errors)) {
                        // Show the second page
                        $html .= IssnregistryFormsHtmlBuilder::getIssnApplicationFormPt3($maxPublicationsCount);
                    } else {
                        // Show the first page with error messages
                        $html .= IssnregistryFormsHtmlBuilder::getIssnApplicationFormPt2($maxPublicationsCount, $errors);
                    }
                } else if (JSession::checkToken() && isset($submitApplicationPt3)) {
                    // Validate input data
                    $errors = IssnregistryFormsHelper::validateApplicationFormPt3();
                    if (empty($errors)) {
                        // Show overview form
                        $html .= IssnregistryFormsHtmlBuilder::getIssnApplicationFormPt4($maxPublicationsCount);
                    } else {
                        // Show the second page with error messages
                        $html .= IssnregistryFormsHtmlBuilder::getIssnApplicationFormPt3($maxPublicationsCount, $errors);
                    }
                } else if (JSession::checkToken() && isset($submitApplicationPt4)) {
                    // Validate input data
                    $errorsPt1 = IssnregistryFormsHelper::validateApplicationFormPt1();
                    $errorsPt2 = IssnregistryFormsHelper::validateApplicationFormPt2();
                    $errorsPt3 = IssnregistryFormsHelper::validateApplicationFormPt3();
                    if (empty($errorsPt1) && empty($errorsPt2) && empty($errorsPt3)) {
                        // Save to DB
                        $formId = IssnregistryFormsHelper::saveApplicationToDb($maxPublicationsCount);
                        // If formId is 0 saving donation to DB failed
                        if ($formId == 0) {
                            // Return error page
                            $html .= '<div>' . JText::_('PLG_ISSNREGISTRY_FORMS_APPLICATION_ERROR') . '</div>';
                        } else {
                            // Return success page
                            $html .= '<div>' . JText::_('PLG_ISSNREGISTRY_FORMS_APPLICATION_SUCCESS') . '</div>';
                            // Notify admin if necessary
                            if ($notifyAdmin) {
                                IssnregistryFormsHelper::notifyAdmin($email, $formId);
                            }
                        }
                    } else {
                        if (!empty($errorsPt1)) {
                            // Show the first page with error messages
                            $html .= IssnregistryFormsHtmlBuilder::getIssnApplicationFormPt1($errorsPt1);
                        } else if (!empty($errorsPt2)) {
                            // Show the second page with error messages
                            $html .= IssnregistryFormsHtmlBuilder::getIssnApplicationFormPt2($maxPublicationsCount, $errorsPt2);
                        } else {
                            // Show the third page with error messages
                            $html .= IssnregistryFormsHtmlBuilder::getIssnApplicationFormPt3($maxPublicationsCount, $errorsPt3);
                        }
                    }
                } else if (JSession::checkToken() && isset($backApplicationPt3)) {
                    // Back button has been pressed - generate form
                    $html .= IssnregistryFormsHtmlBuilder::getIssnApplicationFormPt2($maxPublicationsCount);
                } else if (JSession::checkToken() && isset($backApplicationPt4)) {
                    // Back button has been pressed - generate form
                    $html .= IssnregistryFormsHtmlBuilder::getIssnApplicationFormPt3($maxPublicationsCount);
                } else {
                    // Generate form
                    $html .= IssnregistryFormsHtmlBuilder::getIssnApplicationFormPt1();
                }

                // Add HTML code
                $replacement[$count] = $html;
                // Increase counter
                $count++;
            }
            for ($i = 0; $i < count($replacement); $i++) {
                $row->text = preg_replace($regex, $replacement[$i], $row->text, 1);
            }
        }
        return true;
    }

}
