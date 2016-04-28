<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * This class offers static helper methods for importing data.
 */
class ImportHelper {

    private static $languages = array(
        'FIN' => 'fi-FI',
        'ENG' => 'en-GB',
        'SWE' => 'sv-SE'
    );
    private static $publicationLanguages = array(
        'SUOMI' => 'FIN',
        'FINSKA' => 'FIN',
        'FINNISH' => 'FIN',
        'RUOTSI' => 'SWE',
        'SVENSKA' => 'SWE',
        'SWEDISH' => 'SWE',
        'ENGLANTI' => 'ENG',
        'ENGELSKA' => 'ENG',
        'ENGLISH' => 'ENG'
    );
    private static $formStates = array(
        '1' => 'NOT_HANDLED',
        '3' => 'NOT_NOTIFIED',
        '8' => 'COMPLETED',
        '9' => 'REJECTED'
    );
    private static $publicationTypes = array(
        '1' => 'JOURNAL',
        '2' => 'NEWSLETTER',
        '3' => 'STAFF_MAGAZINE',
        '4' => 'MEMBERSHIP_BASED_MAGAZINE',
        '5' => 'CARTOON',
        '6' => 'NEWSPAPER',
        '7' => 'FREE_PAPER',
        '8' => 'MONOGRAPHY_SERIES',
        '9' => 'OTHER_SERIAL'
    );
    private static $mediums = array(
        '1' => 'PRINTED',
        '2' => 'ONLINE',
        '3' => 'CDROM',
        '4' => 'OTHER'
    );
    private static $statuses = array(
        '1' => 'NO_PREPUBLICATION_RECORD',
        '2' => 'ISSN_FROZEN',
        '3' => 'WAITING_FOR_CONTROL_COPY',
        '4' => 'COMPLETED'
    );

    public static function getLanguage($language) {
        if (!array_key_exists($language, self::$languages)) {
            return self::$languages['FIN'];
        }
        return self::$languages[$language];
    }

    public static function getPublicationLanguage($language) {
        if (!array_key_exists($language, self::$publicationLanguages)) {
            return '';
        }
        return self::$publicationLanguages[$language];
    }

    public static function getFormState($state) {
        if (!array_key_exists($state, self::$formStates)) {
            return self::$formStates['1'];
        }
        return self::$formStates[$state];
    }

    public static function getPublicationType($type) {
        if (!array_key_exists($type, self::$publicationTypes)) {
            return self::$publicationTypes['9'];
        }
        return self::$publicationTypes[$type];
    }

    public static function getMedium($medium) {
        if (!array_key_exists($medium, self::$mediums)) {
            return self::$mediums['4'];
        }
        return self::$mediums[$medium];
    }

    public static function getStatus($status) {
        if (!array_key_exists($status, self::$statuses)) {
            return self::$statuses['1'];
        }
        return self::$statuses[$status];
    }

    public static function convertDate($source) {
        $date = new DateTime($source);
        return $date->format('Y-m-d H:i:s');
    }

    public static function readImportForm1($file, &$publisherLanguage, &$firstPublisherForm) {
        // Open file that contains forms data
        $fp = fopen($file, 'r');

        // Array for results
        $forms = array();
        // Line counter
        $i = 0;
        // Loop through the file. One form per line.
        while (!feof($fp)) {
            // Increase counter
            $i++;
            // Get line
            $line = fgets($fp, 2048);
            // Split by "\t"
            $data = str_getcsv($line, "\t");
            // Skip headers and empty lines
            if ($i == 1 || strlen(trim($data[0])) == 0) {
                continue;
            }
            // Create a new form
            $form = self::getForm($data);
            // Set publisher language
            $publisherLanguage[$form['publisher_id']] = $form['lang_code'];
            // Check if the fist form with current publisher has already
            // been set
            if (!array_key_exists($form['publisher_id'], $firstPublisherForm)) {
                // Set publisher id - form id pair that tells the id of the
                // form where the publisher id was used for the very first
                // time
                $firstPublisherForm[$form['publisher_id']] = $data[0];
                // Set publisher_created to true
                $form['publisher_created'] = true;
            }
            // Add new form to the forms array. Use old id as key.
            $forms[$data[0]] = $form;
        }
        // Close file
        fclose($fp);
        // Return forms
        return $forms;
    }

    private static function getForm($data) {
        // Create a new form
        $form = array(
            'id' => 0,
            'publisher' => trim($data[1]),
            'contact_person' => trim($data[2]),
            'email' => trim($data[3]),
            'phone' => trim($data[4]),
            'address' => trim($data[5]),
            'zip' => trim($data[6]),
            'city' => trim($data[7]),
            'publication_count' => $data[8],
            'publication_count_issn' => $data[9],
            'publisher_id' => $data[10],
            'publisher_created' => false,
            'lang_code' => self::getLanguage($data[11]),
            'status' => self::getFormState($data[12]),
            'id_old' => $data[0],
            'created' => self::convertDate($data[13]),
            'created_by' => (preg_match('/^[\d\.]+$/i', $data[14]) ? 'WWW' : $data[14])
        );
        return $form;
    }

    public static function readImportPublication1($file) {
        // Open file that contains publications data
        $fp = fopen($file, 'r');

        // Array for results
        $publications = array();
        // Line counter
        $i = 0;
        // Loop through the file. One publication per line.
        while (!feof($fp)) {
            // Increase counter
            $i++;
            // Get line
            $line = fgets($fp, 2048);
            // Split by "\t"
            $data = str_getcsv($line, "\t");
            // Skip headers and empty lines
            if ($i == 1 || strlen(trim($data[0])) == 0) {
                continue;
            }
            // Create a new publication
            $publication = self::getPublication($data);
            // Add new publisher to the publications array. Use old id as key.
            $publications[$data[0]] = $publication;
        }
        // Close file
        fclose($fp);
        // Return publications
        return $publications;
    }

    private static function getPublication($data) {
        // Variable for additional info
        $additionalInfo = $data[23];
        // Check that year is OK
        $yearOk = preg_match('/^[\d]{4}$/i', trim($data[5]));
        // Get language
        $language = strtoupper(trim($data[8]));
        // Validate language
        $languageOk = self::validateLanguage($language);
        if (!$languageOk) {
            // Language is not OK, try to fix the most common errors
            $language = self::getPublicationLanguage($language);
            // Revalidate
            $languageOk = self::validateLanguage($language);
            // Check if the publication is multilingual
            if (!$languageOk && self::isMultiLingual($language)) {
                $language = 'MUL';
                $additionalInfo .= empty($additionalInfo) ? '' : ' ';
                $additionalInfo .= 'Lang: ' . $data[8];
            } else {
                $additionalInfo .= empty($additionalInfo) ? '' : ' ';
                $additionalInfo .= 'Lang: ' . $data[8];
            }
        }
        // Create a new publication
        $publication = array(
            'id' => 0,
            'title' => trim($data[1]),
            'issn' => $data[2],
            'place_of_publication' => $data[3],
            'printer' => $data[4],
            'issued_from_year' => ($yearOk ? $data[5] : ''),
            'issued_from_number' => ($yearOk ? $data[6] : $data[5] . ', ' . $data[6]),
            'frequency' => 'z',
            'frequency_other' => $data[7],
            'language' => $language,
            'publication_type' => self::getPublicationType($data[9]),
            'publication_type_other' => $data[10],
            'medium' => self::getMedium($data[11]),
            'medium_other' => $data[12],
            'url' => (strlen(trim($data[13])) > 0 && !preg_match('/^http(s)?:\/\//', $data[13]) ? 'http://' . $data[13] : $data[13]),
            'previous' => self::getPrevious($data),
            'main_series' => self::getTitleIssn($data[17], $data[18]),
            'subseries' => self::getTitleIssn($data[19], $data[20]),
            'another_medium' => self::getTitleIssn($data[21], $data[22]),
            'additional_info' => $additionalInfo,
            'status' => self::getStatus($data[24]),
            'form_id' => $data[25],
            'publisher_id' => $data[26],
            'id_old' => $data[0],
            'created' => self::convertDate($data[27]),
            'created_by' => $data[28],
            'modified' => self::convertDate($data[29]),
            'modified_by' => $data[30]
        );
        return $publication;
    }

    private static function getPrevious($data) {
        $previous = array(
            'title' => array(trim($data[14])),
            'issn' => array(trim($data[15])),
            'last_issue' => array(trim($data[16]))
        );
        return json_encode($previous, JSON_UNESCAPED_UNICODE);
    }

    private static function getTitleIssn($title, $issn) {
        $arr = array(
            'title' => array(trim($title)),
            'issn' => array(trim($issn))
        );
        return json_encode($arr, JSON_UNESCAPED_UNICODE);
    }

    private static function getLanguageList() {
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

    private static function isMultiLingual($langCode) {
        return preg_match('(ja|tai| |,)', strtolower($langCode));
    }

    public static function readImportPublisher1($file, $publisherLanguage, $firstPublisherForm) {
        // Open file that contains publishers data
        $fp = fopen($file, 'r');

        // Array for results
        $publishers = array();
        // Line counter
        $i = 0;
        // Loop through the file. One publisher per line.
        while (!feof($fp)) {
            // Increase counter
            $i++;
            // Get line
            $line = fgets($fp, 2048);
            // Split by "\t"
            $data = str_getcsv($line, "\t");
            // Skip headers and empty lines
            if ($i == 1 || strlen(trim($data[0])) == 0) {
                continue;
            }
            // Create a new publisher
            $publisher = self::getPublisher($data, $publisherLanguage, $firstPublisherForm);
            // Add new publisher to the publishers array. Use old id as key.
            $publishers[$data[0]] = $publisher;
        }
        // Close file
        fclose($fp);
        // Return publishers
        return $publishers;
    }

    public static function readImportPublisher2($file, &$publishers) {
        // Open file that contains publishers data
        $fp = fopen($file, 'r');

        // Line counter
        $i = 0;
        // Loop through the file. One publisher per line.
        while (!feof($fp)) {
            // Increase counter
            $i++;
            // Get line
            $line = fgets($fp, 2048);
            // Split by "\t"
            $data = str_getcsv($line, "\t");
            // Skip headers and empty lines
            if ($i == 1 || strlen(trim($data[0])) == 0) {
                continue;
            }
            // Check that a corresponding publisher exists
            if (!array_key_exists($data[0], $publishers)) {
                continue;
            }
            // Check if publisher already has a contact person
            if (empty($publishers[$data[0]]['contact_person'])) {
                $contact = array(
                    'name' => array(trim($data[1])),
                    'email' => array(trim($data[2]))
                );
                $publishers[$data[0]]['contact_person'] = json_encode($contact, JSON_UNESCAPED_UNICODE);
            } else {
                // Decode string to JSON array
                $json = json_decode($publishers[$data[0]]['contact_person'], true);
                if (!empty($json)) {
                    // Add new values to array
                    array_push($json['name'], trim($data[1]));
                    array_push($json['email'], trim($data[2]));
                    // Encode array back to JSON string
                    $publishers[$data[0]]['contact_person'] = json_encode($json, JSON_UNESCAPED_UNICODE);
                }
            }
        }
        // Close file
        fclose($fp);
    }

    private static function getPublisher($data, $publisherLanguage, $firstPublisherForm) {
        $contact = '';
        if (!empty($data[2])) {
            $contact = '{"name":["' . $data[2] . '"],"email":["' . $data[3] . '"]}';
        }
        // Create a new publisher
        $publisher = array(
            'id' => 0,
            'official_name' => trim($data[1]),
            'contact_person' => $contact,
            'email' => (empty($data[2]) ? $data[3] : ''),
            'phone' => $data[4],
            'address' => $data[5],
            'zip' => $data[6],
            'city' => $data[7],
            'lang_code' => (array_key_exists($data[0], $publisherLanguage) ? $publisherLanguage[$data[0]] : self::getLanguage('')),
            'additional_info' => $data[8],
            'form_id' => (array_key_exists($data[0], $firstPublisherForm) ? $firstPublisherForm[$data[0]] : 0),
            'id_old' => $data[0],
            'created' => self::convertDate($data[9]),
            'created_by' => $data[10],
            'modified' => self::convertDate($data[11]),
            'modified_by' => $data[12]
        );
        return $publisher;
    }

    public static function readImportRange1($file) {
        // Open file that contains identifier range data
        $fp = fopen($file, 'r');

        // Array for results
        $ranges = array();
        // Line counter
        $i = 0;
        // Loop through the file. One identifier range per line.
        while (!feof($fp)) {
            // Increase counter
            $i++;
            // Get line
            $line = fgets($fp, 2048);
            // Split by "\t"
            $data = str_getcsv($line, "\t");
            // Skip headers and empty lines
            if ($i == 1 || strlen(trim($data[0])) == 0) {
                continue;
            }
            if (!array_key_exists($data[0], $ranges)) {
                $ranges[$data[0]] = array();
            }
            if (!array_key_exists('free', $ranges[$data[0]])) {
                $ranges[$data[0]]['free'] = 0;
            }
            if (!array_key_exists('taken', $ranges[$data[0]])) {
                $ranges[$data[0]]['taken'] = 0;
            }
            $key = 'free';
            if (strcmp($data[1], '2') == 0) {
                $key = 'taken';
            }
            $ranges[$data[0]][$key] = $data[2];
        }
        // Close file
        fclose($fp);
        // Return ranges
        return $ranges;
    }

    public static function readImportRange2($file, $identifiers, $rangesFreeTaken) {
        // Open file that contains identifier range data
        $fp = fopen($file, 'r');

        // Array for results
        $ranges = array();
        // Line counter
        $i = 0;
        // Loop through the file. One identifier range per line.
        while (!feof($fp)) {
            // Increase counter
            $i++;
            // Get line
            $line = fgets($fp, 2048);
            // Split by "\t"
            $data = str_getcsv($line, "\t");
            // Skip headers and empty lines
            if ($i == 1 || strlen(trim($data[0])) == 0) {
                continue;
            }

            // Get new range object
            $range = self::getRange($data, $identifiers, $rangesFreeTaken);
            // Add new identifier range to the identifier ranges array. Use 
            // class id as key.
            $ranges[$data[0]] = $range;
        }
        // Close file
        fclose($fp);
        // Return ranges
        return $ranges;
    }

    private static function getRange($data, $identifiers, $rangesFreeTaken) {
        // Create a new identifier range
        $range = array(
            'id' => 0,
            'block' => $data[1],
            'range_begin' => $data[2],
            'range_end' => $data[3],
            'free' => $rangesFreeTaken[$data[0]]['free'],
            'taken' => $rangesFreeTaken[$data[0]]['taken'],
            'next' => self::getRangeNextPointer($data, $identifiers, $rangesFreeTaken),
            'is_active' => (($rangesFreeTaken[$data[0]]['free'] > 0 && $rangesFreeTaken[$data[0]]['taken'] > 0) ? true : false),
            'is_closed' => ($rangesFreeTaken[$data[0]]['free'] == 0 ? true : false),
            'id_old' => $data[0],
            'created' => self::convertDate($data[5]),
            'created_by' => $data[4]
        );
        return $range;
    }

    private static function getRangeNextPointer($data, $identifiers, $rangesFreeTaken) {
        // Get range's next pointer value
        $next = $identifiers[$data[0]] ['next'];
        // If value is 0, all the identifiers are used and the range is closed
        if ($rangesFreeTaken[$data[0]]['free'] ==
                0) {
            // Next pointer is empty
            $next = '';
        }
        // Return result
        return $next;
    }

    public static function readImportIdentifier1($file) {
        // Open file that contains identifiers data
        $fp = fopen($file, 'r');

        // Array for results
        $identifiers = array();
        // Variables for processing
        $previousClass = '';
        $previousValue = '';

        // Line counter
        $i = 0;
        // Loop through the file. One identifier per line.
        while (!feof($fp)) {
            // Increase counter
            $i++;
            // Get line
            $line = fgets($fp, 2048);
            // Split by "\t"
            $data = str_getcsv($line, "\t");
            // Skip headers and empty lines
            if ($i == 1 || strlen(trim($data[0])) == 0) {
                continue;
            }

            // Results are groupped by identifier range id
            if (!array_key_exists($data[1], $identifiers)) {
                // Variable for next pointer value (identifier)
                $identifiers[$data[1]]['next'] = substr($data[0], 5);
            } else {
                // Check if previous and current values are different
                if (strcmp($previousValue, $data[3]) != 0) {
                    if (strcmp($previousValue, '0') == 0) {
                        // This is a change: 0 => 2
                        // Reset next pointer
                        $identifiers[$data[1]]['next'] = '';
                    } else if (strcmp($data[3], '0') == 0) {
                        // This is a change: 2 => 0
                        // Set next pointer. We don't know yet if this is the
                        // final value.
                        $identifiers[$data[1]]['next'] = substr($data[0], 5);
                    }
                }
            }
            // Update previous values
            $previousClass = $data[1];
            $previousValue = $data[3];
        }
        // Close file
        fclose($fp);

        // Return identifiers
        return $identifiers;
    }

    public static function readImportIdentifier12($file) {
        // Open file that contains identifiers data
        $fp = fopen($file, 'r');

        // Array for results
        $identifiers = array();

        // Line counter
        $i = 0;
        // Loop through the file. One identifier per line.
        while (!feof($fp)) {
            // Increase counter
            $i++;
            // Get line
            $line = fgets($fp, 2048);
            // Split by "\t"
            $data = str_getcsv($line, "\t");
            // Skip headers and empty lines
            if ($i == 1 || strlen(trim($data[0])) == 0) {
                continue;
            }

            // Create new identifier if range id is not 0 and 
            // status is 2
            if ($data[2] != 0 && $data[3] == 2) {
                // Get new range object
                $issn = self::getIdentifierUsed($data);
                // Add new identifier range to the identifier ranges array. Use 
                // class id as key.
                $identifiers[$data[0]] = $issn;
            }
        }
        // Close file
        fclose($fp);

        // Return identifiers
        return $identifiers;
    }

    private static function getIdentifierUsed($data) {
        // Create a new identifier
        $issn = array(
            'id' => 0,
            'issn' => $data[0],
            'publication_id' => $data[2],
            'issn_range_id' => $data[1],
            'created' => self::convertDate($data[5]),
            'created_by' => $data[4]
        );
        return $issn;
    }

}

?>