<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
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

    public static function getLanguage($language) {
        return self::$languages[$language];
    }

    public static function convertDate($source) {
        $date = new DateTime($source);
        return $date->format('Y-m-d H:i:s');
    }

    public static function readImportPublisher1($file) {
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
            if ($i == 1 || strlen($data[0]) == 0) {
                continue;
            }
            // Create a new publisher
            $publisher = self::getPublisher($data);
            // Add new publisher to the publishers array. Use old id as key.
            $publishers[$data[0]] = $publisher;
        }
        // Close file
        fclose($fp);
        // Return publishers
        return $publishers;
    }

    private static function getPublisher($data) {
        // Create a new publisher
        $publisher = array(
            'id' => 0,
            'official_name' => '',
            'other_names' => '',
            'previous_names' => '',
            'lang_code' => self::getLanguage($data[1]),
            'additional_info' => $data[2],
            'year_quitted' => $data[3],
            'has_quitted' => (strcmp($data[4], 'D') == 0 ? true : false),
            'created' => self::convertDate($data[5]),
            'created_by' => $data[6],
            'modified' => self::convertDate($data[7]),
            'modified_by' => $data[8],
            'address' => $data[9],
            'zip' => $data[10],
            'city' => $data[11],
            'phone' => $data[12],
            'email' => $data[13],
            'contact_person' => $data[14],
            'www' => '',
            'question_7' => '',
            'active_identifier_isbn' => '',
            'active_identifier_ismn' => ''
        );
        return $publisher;
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
            if ($i == 1 || strlen($data[0]) == 0) {
                continue;
            }
            // Check that there's a matching publisher
            if (array_key_exists($data[0], $publishers)) {
                // Set first name
                $publishers[$data[0]]['official_name'] = $data[1];
                // Set www address
                $publishers[$data[0]]['www'] = (strlen(trim($data[2])) > 0 && !preg_match('/^http(s)?:\/\//', $data[2]) ? 'http://' . $data[2] : $data[2]);
            }
        }
        // Close file
        fclose($fp);
    }

    public static function readImportPublisher3($file, &$publishers) {
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
            if ($i == 1 || strlen($data[0]) == 0) {
                continue;
            }
            // Check that there's a matching publisher
            if (array_key_exists($data[0], $publishers)) {
                if (strcmp($data[2], 'C') == 0) {
                    $publishers[$data[0]]['official_name'] .= empty($publishers[$data[0]]['official_name']) ? $data[1] : ' ' . $data[1];
                } else if (strcmp($data[2], 'M') == 0) {
                    $publishers[$data[0]]['other_names'] .= empty($publishers[$data[0]]['other_names']) ? $data[1] : ', ' . $data[1];
                } else if (strcmp($data[2], 'A') == 0) {
                    if (empty($publishers[$data[0]]['previous_names'])) {
                        // Create new array for names
                        $previousNames = array();
                        // Add new value
                        $previousNames['name'] = array($data[1]);
                        // Encode array to JSON string
                        $publishers[$data[0]]['previous_names'] = json_encode($previousNames, JSON_UNESCAPED_UNICODE);
                    } else {
                        // Decode string to JSON array
                        $json = json_decode($publishers[$data[0]]['previous_names'], true);
                        if (!empty($json)) {
                            // Add new value to array
                            array_push($json['name'], $data[1]);
                            // Encode array back to JSON string
                            $publishers[$data[0]]['previous_names'] = json_encode($json, JSON_UNESCAPED_UNICODE);
                        }
                    }
                }
            }
        }
        // Close file
        fclose($fp);
    }

    public static function readImportPublisher4($file, &$publishers) {
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
            if ($i == 1 || strlen($data[0]) == 0) {
                continue;
            }
            // Check that there's a matching publisher
            if (array_key_exists($data[0], $publishers)) {
                $publishers[$data[0]]['question_7'] .= empty($publishers[$data[0]]['question_7']) ? $data[1] : ',' . $data[1];
            }
        }
        // Close file
        fclose($fp);
    }

}
?>