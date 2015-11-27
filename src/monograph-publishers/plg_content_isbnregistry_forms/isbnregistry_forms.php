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
				$lang =& JFactory::getLanguage();
				// Load the language file in the current site language
				$lang->load('plg_content_isbnregistry_forms', JPATH_ADMINISTRATOR, $lang->getTag(), true);
						
				// TODO: create and process forms
				if(strpos($value,'registration') !== false) {
					if(!isset($_POST['submit_registration'])) {
						$html .= IsbnregistryFormsHtmlBuilder::getRegisterMonographPublisherForm($lang->getTag());
					} elseif(JSession::checkToken() && isset($_POST['submit_registration'])) {
						// Validate input data
						$errors = IsbnregistryFormsHelper::validateRegistrationForm();
						// If there are no errors, continue processing
						if (empty($errors)) {
							// Get the post variables
							$post = JFactory::getApplication()->input->post;
							// Save to DB
							$publisherId = IsbnregistryFormsHelper::saveToDb($post);
							 // If publisherId is 0 saving donation to DB failed
                             if ($publisherId == 0) {
								 // TODO: return error form
								$html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_REGISTRATION_ERROR') . '</div>';
							 } else {
								// TODO: return success form
								$html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_REGISTRATION_SUCCESS') . '</div>';
								// Save publisher to session
								IsbnregistryFormsHelper::savePublisherToSession();
							 }
						} else {
							$html .= IsbnregistryFormsHtmlBuilder::getRegisterMonographPublisherForm($lang->getTag(), $errors);
						}
					}
				} else if(strpos($value,'application') !== false) {
					// No buttons presed - show the first page
					if(!isset($_POST['submit_application_pt1']) && !isset($_POST['submit_application_pt2'])) {
						// Load pulisher from session if exists
						IsbnregistryFormsHelper::loadPublisherFromSession();
						// Generate form
						$html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt1();
					} elseif(JSession::checkToken() && isset($_POST['back_application_pt2'])) {
						// Back button has been pressed - generate form
						$html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt1();						
					} elseif(JSession::checkToken() && isset($_POST['submit_application_pt1'])) {
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
					} elseif(JSession::checkToken() && isset($_POST['submit_application_pt2'])) {
						// Validate input data
						$errors = IsbnregistryFormsHelper::validateApplicationFormPt2();
						if (empty($errors)) {
							// TODO: show overview form
						} else {
							// Show the second page with error messages
							$html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt2($errors);
						}
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
