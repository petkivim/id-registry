<?php

/**
 * @Plugin 	"ID Registry - Monograph Publishers - Forms"
 * @version 	1.0.0
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

require_once __DIR__ . '/isbnregistryFormsHtmlBuilder.php';
require_once __DIR__ . '/isbnregistryFormsHelper.php';
require_once __DIR__ . '/isbnregistryFormsLogger.php';

class plgContentIsbnregistry_forms extends JPlugin {

    function plgContentIsbnregistry_forms(&$subject, $params) {
        parent::__construct($subject, $params);
    }

    public function onContentPrepare($context, &$row, &$params, $page = 0) {
        // Search in the article text the plugin code and exit if not found
        $regex = "%\{mono_pub_form (registration|application|)\}%is";
        $found = preg_match_all($regex, $row->text, $matches);

        $count = 0;

        if ($found) {

            foreach ($matches[0] as $value) {
                $html = "";

                // This section generates and processes forms that are 
                // needed for giving donations.
                // Get admin email from Joomla config
                $dVar = new JConfig();
                $adminEmail = $dVar->mailfrom;

                // Add plugin css
                $document = JFactory::getDocument();
                $document->addStyleSheet("plugins/content/isbnregistry_forms/scripts/chosen/chosen.css");
                $document->addStyleSheet("plugins/content/isbnregistry_forms/css/style.css");
                // Add plugin scripts
                JHtml::_('jquery.framework');
                $document->addScript("plugins/content/isbnregistry_forms/scripts/chosen/chosen.jquery.js");
                $document->addScript("plugins/content/isbnregistry_forms/scripts/custom.js");

                // Email settings
                $email = $this->params->def('email', $adminEmail);
                $notifyAdmin = $this->params->def('notify_admin', true);

                // Language settings
                $lang = & JFactory::getLanguage();
                // Load the language file in the current site language
                $lang->load('plg_content_isbnregistry_forms', JPATH_ADMINISTRATOR, $lang->getTag(), true);

                // Get the post variables
                $post = JFactory::getApplication()->input->post;

                // Process forms
                if (strpos($value, 'registration') !== false) {
                    $submitRegistration = $post->get('submit_registration', null, 'string');
                    if (JSession::checkToken() && isset($submitRegistration)) {
                        // Validate input data
                        $errors = IsbnregistryFormsHelper::validateRegistrationForm();
                        // If there are no errors, continue processing
                        if (empty($errors)) {
                            // Save to DB
                            $publisherId = IsbnregistryFormsHelper::saveRegistrationToDb($lang->getTag());
                            // If publisherId is 0 saving donation to DB failed
                            if ($publisherId == 0) {
                                // Return error page
                                $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_REGISTRATION_ERROR') . '</div>';
                            } else {
                                // Return success page
                                $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_REGISTRATION_SUCCESS') . '</div>';
                                // Save publisher to session
                                IsbnregistryFormsHelper::savePublisherToSession();
                            }
                        } else {
                            $html .= IsbnregistryFormsHtmlBuilder::getRegisterMonographPublisherForm($errors);
                        }
                    } else {
                        $html .= IsbnregistryFormsHtmlBuilder::getRegisterMonographPublisherForm();
                    }
                } else if (strpos($value, 'application') !== false) {
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
                        $errors = IsbnregistryFormsHelper::validateApplicationFormPt1();
                        // If there are no errors, continue processing
                        if (empty($errors)) {
                            // Show the second page
                            $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt2();
                        } else {
                            // Show the first page with error messages
                            $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt1($errors);
                        }
                    } else if (JSession::checkToken() && isset($submitApplicationPt2)) {
                        // Validate input data
                        $errors = IsbnregistryFormsHelper::validateApplicationFormPt2();
                        // If there are no errors, continue processing
                        if (empty($errors)) {
                            // Show the second page
                            $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt3();
                        } else {
                            // Show the first page with error messages
                            $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt2($errors);
                        }
                    } else if (JSession::checkToken() && isset($submitApplicationPt3)) {
                        // Validate input data
                        $errors = IsbnregistryFormsHelper::validateApplicationFormPt3();
                        if (empty($errors)) {
                            // Show overview form
                            $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt4();
                        } else {
                            // Show the second page with error messages
                            $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt3($errors);
                        }
                    } else if (JSession::checkToken() && isset($submitApplicationPt4)) {
                        // Validate input data
                        $errorsPt1 = IsbnregistryFormsHelper::validateApplicationFormPt1();
                        $errorsPt2 = IsbnregistryFormsHelper::validateApplicationFormPt2();
                        $errorsPt3 = IsbnregistryFormsHelper::validateApplicationFormPt3();
                        if (empty($errorsPt1) && empty($errorsPt2) && empty($errorsPt3)) {
                            // Save to DB
                            $publisherId = IsbnregistryFormsHelper::saveApplicationToDb($lang->getTag());
                            // If publisherId is 0 saving donation to DB failed
                            if ($publisherId == 0) {
                                // Return error page
                                $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_ERROR') . '</div>';
                            } else {
                                // Return success page
                                $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUCCESS') . '</div>';
                            }
                        } else {
                            if (!empty($errorsPt1)) {
                                // Show the first page with error messages
                                $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt1($errorsPt1);
                            } else if (!empty($errorsPt2)) {
                                // Show the second page with error messages
                                $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt2($errorsPt2);
                            } else {
                                // Show the third page with error messages
                                $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt3($errorsPt3);
                            }
                        }
                    } else if (JSession::checkToken() && isset($backApplicationPt3)) {
                        // Back button has been pressed - generate form
                        $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt2();
                    } else if (JSession::checkToken() && isset($backApplicationPt4)) {
                        // Back button has been pressed - generate form
                        $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt3();
                    } else {
                        // Load pulisher from session if exists
                        IsbnregistryFormsHelper::loadPublisherFromSession();
                        // Generate form
                        $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt1();
                    }
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
