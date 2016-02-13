<?php

/**
 * @Plugin 	"ID Registry - Serials Publishers - Forms"
 * @version 	1.0.0
 * @author 	Petteri Kivim�ki
 * @copyright	Copyright (C) 2015 Petteri Kivim�ki. All rights reserved.
 */
defined('_JEXEC') or die('Restricted access');

class IssnregistryFormsHelper {

    public static function validateApplicationFormPt1() {
        // Array for the error messages
        $errors = array();

        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        // Publisher - required
        $publisher = $post->get('publisher', null, 'string');
        if (empty($publisher) == true) {
            $errors['publisher'] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
        } else if (strlen($publisher) > 100) {
            $errors['publisher'] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
        }
        // Contact person - required
        $contactPerson = $post->get('contact_person', null, 'string');
        if (empty($contactPerson) == true) {
            $errors['contact_person'] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
        } elseif (strlen($contactPerson) > 100) {
            $errors['contact_person'] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
        }
        // Email - required
        $email = $post->get('email', null, 'string');
        if (empty($email) == true) {
            $errors['email'] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
        } else if (strlen($email) > 100) {
            $errors['email'] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
        }
        // Phone number - required (validate format)
        $phone = $post->get('phone', null, 'string');
        if (empty($phone) == true) {
            $errors['phone'] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
        } else if (!preg_match('/^(\+){0,1}[0-9 ]{0,30}$/i', $phone)) {
            $errors['phone'] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
        }
        // Address - optional
        $address = $post->get('address', null, 'string');
        if (strlen($address) > 50) {
            $errors['address'] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
        }
        // ZIP - optional
        $zip = $post->get('zip', null, 'string');
        if (!empty($zip) && !preg_match('/^\d{5}$/i', $zip)) {
            $errors['zip'] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
        }
        // City - optional
        $city = $post->get('city', null, 'string');
        if (strlen($city) > 50) {
            $errors['city'] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
        }
        // If address not empty, zip and city can not be empty
        if (!empty($address) == true) {
            if (strlen($zip) == 0) {
                $errors['zip'] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            }
            if (strlen($city) == 0) {
                $errors['city'] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            }
        }
        return $errors;
    }

    public static function validateApplicationFormPt2() {
        // Array for the error messages
        $errors = array();

        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        // Publication count - required
        $publicationCount = $post->get('publication_count', 0, 'integer');
        if ($publicationCount == 0) {
            $errors['publication_count'] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
        }
        return $errors;
    }

    public static function validateApplicationFormPt3() {
        // Array for the error messages
        $errors = array();

        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        // Get publication count
        $publicationCount = $post->get('publication_count', 0, 'integer');

        // Loop through all the publications
        for ($i = 0; $i < $publicationCount; $i++) {
            // Title - required
            $title = $post->get('title_' . $i, null, 'string');
            if (empty($title) == true) {
                $errors['title_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            } else if (strlen($title) > 100) {
                $errors['title_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Place of publication - required
            $placeOfPublication = $post->get('place_of_publication_' . $i, null, 'string');
            if (empty($placeOfPublication) == true) {
                $errors['place_of_publication_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            } else if (strlen($placeOfPublication) > 100) {
                $errors['place_of_publication_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Printer - optional
            $printer = $post->get('printer_' . $i, null, 'string');
            if (strlen($printer) > 100) {
                $errors['printer_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Issued from year - required
            $issuedFromYear = $post->get('issued_from_year_' . $i, null, 'string');
            if (empty($issuedFromYear) == true) {
                $errors['issued_from_year_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            } else if (strlen($issuedFromYear) > 4) {
                $errors['issued_from_year_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (!preg_match('/^[0-9]{4}$/i', $issuedFromYear)) {
                $errors['issued_from_year_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
            }
            // Issued from number - optional
            $issuedFromNumber = $post->get('issued_from_number_' . $i, null, 'string');
            if (strlen($issuedFromNumber) > 20) {
                $errors['issued_from_number_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Frequency - required
            $frequency = $post->get('frequency_' . $i, null, 'string');
            if (empty($frequency) == true) {
                $errors['frequency_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            } else if (strlen($frequency) > 30) {
                $errors['frequency_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Language - required
            $language = $post->get('language_' . $i, null, 'string');
            if (empty($language) == true) {
                $errors['language_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            } else if (strlen($language) > 50) {
                $errors['language_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Publication type - required
            $publicationType = $post->get('publication_type_' . $i, null, 'string');
            if (empty($publicationType) == true) {
                $errors['publication_type_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            } else if (strlen($publicationType) > 25) {
                $errors['publication_type_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (!self::isValidPublicationType($publicationType)) {
                $errors['publication_type_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
            }
            // Publication type other - optional
            $publicationTypeOther = $post->get('publication_type_other_' . $i, null, 'string');
            if (strlen($publicationTypeOther) > 50) {
                $errors['publication_type_other_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (preg_match('/^OTHER_SERIAL$/', $publicationType) && empty($publicationTypeOther) == true) {
                $errors['publication_type_other_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            }
            // Medium - required
            $medium = $post->get('medium_' . $i, null, 'string');
            if (empty($medium) == true) {
                $errors['medium_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            } else if (strlen($medium) > 7) {
                $errors['medium_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (!self::isValidMedium($medium)) {
                $errors['medium_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
            }
            // Medium other - optional
            $mediumOther = $post->get('medium_other_' . $i, null, 'string');
            if (strlen($mediumOther) > 50) {
                $errors['medium_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (preg_match('/^OTHER$/', $medium) && empty($mediumOther) == true) {
                $errors['medium_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            }
            // URL - optional (validate format)
            $url = $post->get('url_' . $i, null, 'string');
            if (strlen($url) > 0 && !preg_match('/^http(s)?:\/\/(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/', $url)) {
                $errors['url_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
            } else if (strlen($url) > 100) {
                $errors['url_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (preg_match('/^ONLINE$/', $medium) && empty($url)) {
                $errors['url_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            }
            // Previous title - optional
            $previousTitle = $post->get('previous_title_' . $i, null, 'string');
            if (strlen($previousTitle) > 100) {
                $errors['previous_title_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Previous ISSN - optional
            $previousIssn = $post->get('previous_issn_' . $i, null, 'string');
            if (strlen($previousIssn) > 9) {
                $errors['previous_issn_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (strlen($previousIssn) > 0 && !self::isValidIssn($previousIssn)) {
                $errors['previous_issn_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
            }
            // Previous title last issue - optional
            $previousTitleLastIssue = $post->get('previous_title_last_issue_' . $i, null, 'string');
            if (strlen($previousTitleLastIssue) > 20) {
                $errors['previous_title_last_issue_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Main series title - optional
            $mainSeriesTitle = $post->get('main_series_title_' . $i, null, 'string');
            if (strlen($mainSeriesTitle) > 100) {
                $errors['main_series_title_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Main series ISSN - optional
            $mainSeriesIssn = $post->get('main_series_issn_' . $i, null, 'string');
            if (strlen($mainSeriesIssn) > 9) {
                $errors['main_series_issn_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (strlen($mainSeriesIssn) > 0 && !self::isValidIssn($mainSeriesIssn)) {
                $errors['main_series_issn_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
            }
            // Subseries title - optional
            $subseriesTitle = $post->get('subseries_title_' . $i, null, 'string');
            if (strlen($subseriesTitle) > 100) {
                $errors['subseries_title_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Subseries ISSN - optional
            $subseriesIssn = $post->get('subseries_issn_' . $i, null, 'string');
            if (strlen($subseriesIssn) > 9) {
                $errors['subseries_issn_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (strlen($subseriesIssn) > 0 && !self::isValidIssn($subseriesIssn)) {
                $errors['subseries_issn_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
            }
            // Another medium title - optional
            $anotherMediumTitle = $post->get('another_medium_title_' . $i, null, 'string');
            if (strlen($anotherMediumTitle) > 100) {
                $errors['another_medium_title_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Another medium ISSN - optional
            $anotherMediumIssn = $post->get('another_medium_issn_' . $i, null, 'string');
            if (strlen($anotherMediumIssn) > 9) {
                $errors['another_medium_issn_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (strlen($anotherMediumIssn) > 0 && !self::isValidIssn($anotherMediumIssn)) {
                $errors['another_medium_issn_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
            }
            // Additional info - optional
            $additionalInfo = $post->get('additional_info_' . $i, null, 'string');
            if (strlen($additionalInfo) > 500) {
                $errors['additional_info_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
        }

        return $errors;
    }

    public static function saveApplicationToDb($lang_code) {
        return 1;
    }

    private static function isValidPublicationType($publicationType) {
        return preg_match('/^(JOURNAL|NEWSLETTER|STAFF_MAGAZINE|MEMBERSHIP_BASED_MAGAZINE|CARTOON|NEWSPAPER|FREE_PAPER|MONOGRAPHY_SERIES|OTHER_SERIAL)$/', $publicationType);
    }

    private static function isValidMedium($medium) {
        return preg_match('/^(PRINTED|ONLINE|CDROM|OTHER)$/', $medium);
    }

    private static function isValidIssn($issn) {
        return preg_match('/^(\d){4}\-(\d){3}[0-9X]{1}$/i', $issn);
    }

    public static function notifyAdmin($recipient, $formId) {
        // Get site's email address
        $config = JFactory::getConfig();
        $from = $config->get('mailfrom');

        // If $recipient is empty, send mail to site admin
        if (empty($recipient)) {
            $recipient = $from;        
        }

        // Sender and recipient can't be the same
        if(strcmp($from, $recipient) == 0) {
            $from = 'donotreply@example.com';
        }
        
        // Create sender array
        $sender = array(
            $from,
            ''
        );
        // Build link to the new form
        $url = JURI::base() . 'administrator/index.php?option=com_issnregistry&view=forms&layout=edit&id=' . $formId;
        // Build message
        $message = JText::_('PLG_ISSNREGISTRY_FORMS_NOTIFY_ADMIN_EMAIL_MESSAGE');
        $message .= '<br /><br /><a href="' . $url . '" target="new">' . $url . '</a>';

        // Get and configure mailer
        $mailer = JFactory::getMailer();
        $mailer->setSender($sender);
        $mailer->addRecipient($recipient);
        $mailer->setSubject(JText::_('PLG_ISSNREGISTRY_FORMS_NOTIFY_ADMIN_EMAIL_SUBJECT'));
        $mailer->isHTML(true);
        $mailer->setBody($message);

        return $mailer->Send();
    }

}

?>