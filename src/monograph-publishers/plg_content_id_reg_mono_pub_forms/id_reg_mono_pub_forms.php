<?php

/**
 * @Plugin 	"ID Registry - Monograph Publishers - Forms"
 * @version 	1.0.0
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

require_once __DIR__ . '/idRegMonoPubFormsHtmlBuilder.php';
require_once __DIR__ . '/idRegMonoPubFormsHelper.php';
require_once __DIR__ . '/idRegMonoPubFormsLogger.php';

class plgContentId_reg_mono_pub_forms extends JPlugin {

    function plgContentId_reg_mono_pub_forms(&$subject, $params) {
        parent::__construct($subject, $params);
    }

    public function onContentPrepare($context, &$row, &$params, $page = 0) {
        // Search in the article text the plugin code and exit if not found
        $regex = "%\{mono_pub_form (registration|)\}%is";
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
				$document->addStyleSheet("plugins/content/id_reg_mono_pub_forms/css/style.css");

				// Email settings
				$email = $this->params->def('email', $adminEmail);
				$notifyAdmin = $this->params->def('notify_admin', true);
				
				// Language settings
				$lang =& JFactory::getLanguage();
				// Load the language file in the current site language
				$lang->load('plg_content_id_reg_mono_pub_forms', JPATH_ADMINISTRATOR, $lang->getTag(), true);
						
				// TODO: create and process forms
				if(strpos($value,'registration') !== false) {
					if(!isset($_POST['submit_registration'])) {
						$html .= IdRegMonoPubFormsHtmlBuilder::getRegisterMonographPublisherForm();
					} elseif(JSession::checkToken() && isset($_POST['submit_registration'])) {
						// Validate input data
						$errors = IdRegMonoPubFormsHelper::validateRegistrationForm();
						// If there are no errors, continue processing
						if (empty($errors)) {
							// TODO: return success form
							$html .= 'OK';
						} else {
							$html .= IdRegMonoPubFormsHtmlBuilder::getRegisterMonographPublisherForm($errors);
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
