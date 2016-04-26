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
    private static $formStates = array(
        '1' => 'NOT_HANDLED',
        '3' => 'NOT_NOTIFIED',
        '8' => 'COMPLETED',
        '9' => 'REJECTED'
    );

    public static function getLanguage($language) {
        if (!array_key_exists($language, self::$languages)) {
            return self::$languages['FIN'];
        }
        return self::$languages[$language];
    }

    public static function getFormState($state) {
        if (!array_key_exists($state, self::$formStates)) {
            return self::$formStates['1'];
        }
        return self::$formStates[$state];
    }

    public static function convertDate($source) {
        $date = new DateTime($source);
        return $date->format('Y-m-d H:i:s');
    }

    public static function readImportForm1($file) {
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
            if (!array_key_exists('free ', $ranges[$data[0]])) {
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
            'is_active' => false,
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

}

?>