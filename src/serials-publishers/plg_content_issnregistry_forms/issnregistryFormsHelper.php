<?php

/**
 * @Plugin 	"ID Registry - Serials Publishers - Forms"
 * @version 	1.0.0
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 */
defined('_JEXEC') or die('Restricted access');

class IssnregistryFormsHelper {

    public static function filterFields1() {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        // ZIP - required
        $zip = $post->get('zip', null, 'string');
        $zip = preg_replace("/[^0-9]/", "", $zip);
        $post->set('zip', $zip);

        // Phone number - required (validate format)
        $phone = $post->get('phone', null, 'string');
        $phone = trim($phone);
        $phone = preg_replace("/[^0-9 \+]/", "", $phone);
        $post->set('phone', $phone);

        // Email - required
        $email = $post->get('email', null, 'string');
        $email = trim($email);
        $post->set('email', $email);
    }

    public static function filterFields2($maxPublicationCount = 1) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        // Publication count - required
        $publicationCount = $post->get('publication_count', 0, 'integer');

        // Sanity check for publication count
        if ($publicationCount > $maxPublicationCount) {
            $publicationCount = $maxPublicationCount;
        }

        // Loop through all the publications
        for ($i = 0; $i < $publicationCount; $i++) {
            // URL - optional
            $url = $post->get('url_' . $i, null, 'string');
            if (strlen($url) > 0) {
                $url = trim($url);
                if (!preg_match('/^http(s)?:\/\/.+$/', $url)) {
                    $url = 'http://' . $url;
                }
                $post->set('url_' . $i, $url);
            }
        }
    }

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
        // Address - required
        $address = $post->get('address', null, 'string');
        if (empty($address) == true) {
            $errors['address'] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
        } else if (strlen($address) > 50) {
            $errors['address'] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
        }
        // ZIP - optional
        $zip = $post->get('zip', null, 'string');
        if (empty($zip) == true) {
            $errors['zip'] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
        } else if (!preg_match('/^\d{5}$/i', $zip)) {
            $errors['zip'] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
        }
        // City - required
        $city = $post->get('city', null, 'string');
        if (empty($city) == true) {
            $errors['city'] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
        } else if (strlen($city) > 50) {
            $errors['city'] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
        }

        return $errors;
    }

    public static function validateApplicationFormPt2($maxPublicationCount = 1) {
        // Array for the error messages
        $errors = array();

        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        // Publication count - required
        $publicationCount = $post->get('publication_count', 0, 'integer');
        if ($publicationCount == 0) {
            $errors['publication_count'] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
        }

        // Sanity check for publication count
        if ($publicationCount > $maxPublicationCount) {
            $publicationCount = $maxPublicationCount;
        }

        // Loop through all the publications
        for ($i = 0; $i < $publicationCount; $i++) {
            // Title - required
            $title = $post->get('title_' . $i, null, 'string');
            if (empty($title) == true) {
                $errors['title_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            } else if (strlen($title) > 200) {
                $errors['title_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Subtitle - optional
            $subtitle = $post->get('subtitle_' . $i, null, 'string');
            if (strlen($subtitle) > 200) {
                $errors['subtitle_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
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
            if (strlen($issuedFromNumber) > 100) {
                $errors['issued_from_number_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            }
            // Frequency - required
            $frequency = $post->get('frequency_' . $i, null, 'string');
            if (empty($frequency) == true) {
                $errors['frequency_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            } else if (strlen($frequency) > 1) {
                $errors['frequency_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (!self::validateFrequency($frequency)) {
                $errors['frequency_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
            }
            // Frequency other - optional
            $frequencyOther = $post->get('frequency_other_' . $i, null, 'string');
            if (strlen($frequencyOther) > 50) {
                $errors['frequency_other_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (strcmp('z', $frequency) == 0 && empty($frequencyOther) == true) {
                $errors['frequency_other_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            } else if (strcmp('', $frequency) == 0 && strlen($frequencyOther) > 0) {
                unset($errors['frequency_' . $i]);
                $post->set('frequency_' . $i, 'z');
            }
            // Language - required
            $language = $post->get('language_' . $i, null, 'string');
            if (empty($language) == true) {
                $errors['language_' . $i] = "PLG_ISSNREGISTRY_FORMS_REQUIRED_FIELD_EMPTY";
            } else if (strlen($language) > 50) {
                $errors['language_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_TOO_LONG";
            } else if (!self::validateLanguage($language)) {
                $errors['language_' . $i] = "PLG_ISSNREGISTRY_FORMS_FIELD_INVALID";
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
                $errors['medium_' . $i] = "PLG_ISSNREGISTRY_FORMS_MEDIUM_OTHER_FIELD_EMPTY";
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

    public static function saveApplicationToDb($maxPublicationsCount) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        // Get Form object
        $publisher = $post->get('publisher', null, 'string');
        $contactPerson = $post->get('contact_person', null, 'string');
        $email = $post->get('email', null, 'string');
        $phone = $post->get('phone', null, 'string');
        $address = $post->get('address', null, 'string');
        $zip = $post->get('zip', null, 'string');
        $city = $post->get('city', null, 'string');
        $publicationCount = $post->get('publication_count', 0, 'integer');
        $formCreated = JFactory::getDate();
        $langCode = JFactory::getLanguage()->getTag();

        // Sanity check for publication count
        if ($publicationCount > $maxPublicationsCount) {
            // If true, the value is tampered => exit
            return 0;
        }

        // Variable for form id
        $formId = 0;
        // Get database connection
        $db = JFactory::getDbo();
        try {
            // Start transaction
            $db->transactionStart();

            // Insert columns
            $columns = array('publisher', 'contact_person', 'email', 'phone', 'address', 'zip', 'city', 'publication_count', 'lang_code', 'created', 'created_by');
            // Insert values
            $values = array($db->quote($publisher), $db->quote($contactPerson), $db->quote($email), $db->quote($phone), $db->quote($address), $db->quote($zip), $db->quote($city), $db->quote($publicationCount), $db->quote($langCode), $db->quote($formCreated->toSql()), $db->quote('WWW'));
            // Create a new query object.
            $query = $db->getQuery(true);
            // Prepare the insert query
            $query->insert($db->quoteName('#__issn_registry_form'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
            // Set the query using our newly populated query object and execute it
            $db->setQuery($query);
            $db->execute();
            $formId = $db->insertid();

            // Check that operation succeeded
            if ($formId == 0) {
                // If operation failed, do rollback
                $db->transactionRollback();
                return 0;
            }

            // Create archive entry
            array_push($columns, 'form_id');
            array_push($values, $formId);
            // Create a new query object.
            $query = $db->getQuery(true);
            // Prepare the insert query
            $query->insert($db->quoteName('#__issn_registry_form_archive'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
            // Set the query using our newly populated query object and execute it
            $db->setQuery($query);
            $db->execute();

            // Check that operation succeeded
            if ($db->insertid() == 0) {
                // If operation failed, do rollback
                $db->transactionRollback();
                return 0;
            }

            // Loop through the publications
            for ($i = 0; $i < $publicationCount; $i++) {
                // Get publication data from form
                $title = $post->get('title_' . $i, null, 'string');
                $subtitle = $post->get('subtitle_' . $i, null, 'string');
                $placeOfPublication = $post->get('place_of_publication_' . $i, null, 'string');
                $printer = $post->get('printer_' . $i, null, 'string');
                $issuedFromYear = $post->get('issued_from_year_' . $i, null, 'string');
                $issuedFromNumber = $post->get('issued_from_number_' . $i, null, 'string');
                $frequency = $post->get('frequency_' . $i, null, 'string');
                $frequencyOther = $post->get('frequency_other_' . $i, null, 'string');
                $language = $post->get('language_' . $i, null, 'string');
                $publicationType = $post->get('publication_type_' . $i, null, 'string');
                $publicationTypeOther = $post->get('publication_type_other_' . $i, null, 'string');
                $medium = $post->get('medium_' . $i, null, 'string');
                $mediumOther = $post->get('medium_other_' . $i, null, 'string');
                $url = $post->get('url_' . $i, null, 'string');
                $previousTitle = $post->get('previous_title_' . $i, null, 'string');
                $previousIssn = $post->get('previous_issn_' . $i, null, 'string');
                $previousTitleLastIssue = $post->get('previous_title_last_issue_' . $i, '', 'string');
                $mainSeriesTitle = $post->get('main_series_title_' . $i, null, 'string');
                $mainSeriesIssn = $post->get('main_series_issn_' . $i, null, 'string');
                $subseriesTitle = $post->get('subseries_title_' . $i, null, 'string');
                $subseriesIssn = $post->get('subseries_issn_' . $i, null, 'string');
                $anotherMediumTitle = $post->get('another_medium_title_' . $i, null, 'string');
                $anotherMediumIssn = $post->get('another_medium_issn_' . $i, null, 'string');
                $additionalInfo = $post->get('additional_info_' . $i, null, 'string');
                $publicationCreated = JFactory::getDate();
                // Generate JSON
                $previous = self::toJson($previousTitle, $previousIssn, $previousTitleLastIssue);
                $mainSeries = self::toJson($mainSeriesTitle, $mainSeriesIssn);
                $subseries = self::toJson($subseriesTitle, $subseriesIssn);
                $anotherMedium = self::toJson($anotherMediumTitle, $anotherMediumIssn);

                // Insert columns
                $pubColumns = array(
                    'title', 'subtitle', 'place_of_publication', 'printer', 'issued_from_year',
                    'issued_from_number', 'frequency', 'frequency_other',
                    'language', 'publication_type', 'publication_type_other',
                    'medium', 'medium_other', 'url', 'previous', 'main_series',
                    'subseries', 'another_medium', 'additional_info', 'form_id',
                    'created', 'created_by');
                // Insert values
                $pubValues = array(
                    $db->quote($title), $db->quote($subtitle), $db->quote($placeOfPublication), $db->quote($printer), $db->quote($issuedFromYear),
                    $db->quote($issuedFromNumber), $db->quote($frequency), $db->quote($frequencyOther),
                    $db->quote($language), $db->quote($publicationType), $db->quote($publicationTypeOther),
                    $db->quote($medium), $db->quote($mediumOther), $db->quote($url), $db->quote($previous), $db->quote($mainSeries),
                    $db->quote($subseries), $db->quote($anotherMedium), $db->quote($additionalInfo), $db->quote($formId),
                    $db->quote($publicationCreated->toSql()), $db->quote('WWW'));

                // Create a new query object.
                $query = $db->getQuery(true);
                // Prepare the insert query
                $query->insert($db->quoteName('#__issn_registry_publication'))
                        ->columns($db->quoteName($pubColumns))
                        ->values(implode(',', $pubValues));
                // Set the query using our newly populated query object and execute it
                $db->setQuery($query);
                $db->execute();
                // Get publication id
                $publicationId = $db->insertid();

                // Check that operation succeeded
                if ($publicationId == 0) {
                    // If operation failed, do rollback
                    $db->transactionRollback();
                    return 0;
                }

                // Create archive entry
                array_push($pubColumns, 'publication_id');
                array_push($pubValues, $publicationId);
                // Create a new query object.
                $query = $db->getQuery(true);
                // Prepare the insert query
                $query->insert($db->quoteName('#__issn_registry_publication_archive'))
                        ->columns($db->quoteName($pubColumns))
                        ->values(implode(',', $pubValues));
                // Set the query using our newly populated query object and execute it
                $db->setQuery($query);
                $db->execute();

                // Check that operation succeeded
                if ($db->insertid() == 0) {
                    // If operation failed, do rollback
                    $db->transactionRollback();
                    return 0;
                }
            }
            // Commit transaction
            $db->transactionCommit();
        } catch (Exception $e) {
            // Catch any database errors
            $db->transactionRollback();
            return 0;
        }
        return $formId;
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

    public static function getLanguageList() {
        $languages = array(
            'FIN', 'SWE', 'ENG', 'SMI', 'SPA', 'FRE', 'RUS', 'GER', 'MUL'
        );
        return $languages;
    }

    private static function validateLanguage($language) {
        $languages = self::getLanguageList();
        if (!in_array($language, $languages)) {
            return false;
        }
        return true;
    }

    public static function getFrequencyList() {
        $frequencies = array(
            'a', 'f', 'q', 'b', 'm', 'w', 'd', 'k', '#', 'z'
        );
        return $frequencies;
    }

    private static function validateFrequency($frequency) {
        $frequencies = self::getFrequencyList();
        if (!in_array($frequency, $frequencies)) {
            return false;
        }
        return true;
    }

    public static function notifyAdmin($recipient, $formId) {
        // Get site's email address
        $config = JFactory::getConfig();
        $from = $config->get('mailfrom');

        // If $recipient is empty, send mail to site admin
        if (empty($recipient)) {
            $recipient = $from;
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

    private static function toJson($title, $issn, $lastIssue = null) {
        $json = '{"title":["' . $title . '"],"issn":["' . $issn . '"]';
        $json .= isset($lastIssue) ? ',"last_issue":["' . $lastIssue . '"]' : '';
        $json .= '}';
        return $json;
    }

}

?>