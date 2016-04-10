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

    public static function readImportIdentifierRange1($file, $identifiers, $ismn = false) {
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
            if ($i == 1 || strlen($data[0]) == 0) {
                continue;
            }
            // Create a new identifier range
            if ($ismn && strcmp($data[2], 'M') == 0) {
                $range = self::getIsmnRange($data, $identifiers);
            } else if (!$ismn && strcmp($data[2], 'M') != 0) {
                $range = self::getIsbnRange($data, $identifiers);
            }
            if (isset($range)) {
                // Add new identifier range to the identifier ranges array. Use 
                // class id as key.
                $ranges[$data[0]] = $range;
            }
            // Destroy range object
            unset($range);
        }
        // Close file
        fclose($fp);
        // Return ranges
        return $ranges;
    }

    private static function getIsbnRange($data, $identifiers) {
        // Get canceled count
        $canceled = sizeof($identifiers[$data[0]]['canceled']);
        // Create a new identifier range
        $range = array(
            'id' => 0,
            'prefix' => $data[1],
            'lang_group' => $data[2],
            'category' => $data[3],
            'range_begin' => str_pad($data[4], $data[3], "0", STR_PAD_LEFT),
            'range_end' => str_pad($data[5], $data[3], "0", STR_PAD_LEFT),
            'free' => $data[7] - $canceled,
            'taken' => $data[8] + $canceled,
            'canceled' => $canceled,
            'next' => self::getRangeNextPointer($data, $identifiers),
            'is_active' => (($data[7] - $canceled > 0 || $canceled > 0) ? true : false),
            'is_closed' => (($data[7] - $canceled == 0 && $canceled == 0) ? true : false)
        );
        return $range;
    }

    private static function getIsmnRange($data, $identifiers) {
        // Get canceled count
        $canceled = sizeof($identifiers[$data[0]]['canceled']);
        // Create a new identifier range
        $range = array(
            'id' => 0,
            'prefix' => $data[1] . '-0',
            'category' => $data[3],
            'range_begin' => str_pad($data[4], $data[3], "0", STR_PAD_LEFT),
            'range_end' => str_pad($data[5], $data[3], "0", STR_PAD_LEFT),
            'free' => $data[7] - $canceled,
            'taken' => $data[8] + $canceled,
            'canceled' => $canceled,
            'next' => self::getRangeNextPointer($data, $identifiers),
            'is_active' => (($data[7] - $canceled > 0 || $canceled > 0) ? true : false),
            'is_closed' => (($data[7] - $canceled == 0 && $canceled == 0) ? true : false)
        );
        return $range;
    }

    private static function getRangeNextPointer($data, $identifiers) {
        // Get range's next pointer value
        $next = $identifiers[$data[0]]['next_identifier'];
        // If value is 0, all the identifiers are used and the range is closed
        if ($next == 0) {
            // Next pointer is range_end + 1
            $next = $data[5] + 1;
        }
        // Add padding
        $next = str_pad($next, $data[3], "0", STR_PAD_LEFT);
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
        $addCanceled = false;
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
            if ($i == 1 || strlen($data[0]) == 0) {
                continue;
            }

            // Results are groupped by identifier range id
            if (!array_key_exists($data[1], $identifiers)) {
                // Create array for canceled identifier ids
                $identifiers[$data[1]]['canceled'] = array();
                // Variable for next pointer value (identifier id)
                $identifiers[$data[1]]['next'] = 0;
                // Variable for next pointer value (identifier)
                $identifiers[$data[1]]['next_identifier'] = 0;
                // True if first identifier is unused
                $addCanceled = strcmp($data[3], '0') == 0 ? true : false;
            } else {
                // Check if previous and curren values are different
                if (strcmp($previousValue, $data[3]) != 0) {
                    if (strcmp($previousValue, '0') == 0) {
                        // This is a change: 0 => 1
                        // Reset next pointer
                        $identifiers[$data[1]]['next'] = 0;
                        $identifiers[$data[1]]['next_identifier'] = 0;
                        // Next identifiers should not be marked as canceled
                        $addCanceled = false;
                    } else if (strcmp($data[3], '0') == 0) {
                        // This is a change: 1 => 0
                        // Set next pointer. We don't know yet if this is the
                        // final value.
                        $identifiers[$data[1]]['next'] = $data[0];
                        $identifiers[$data[1]]['next_identifier'] = $data[2];
                        // Next identifiers should be marked as canceled. If
                        // we've found the correct next pointer value, next
                        // identifiers are unused, not canceled. This will be
                        // fixed later.
                        $addCanceled = true;
                    }
                }
            }
            // Check if this identifier should be added to canceled identifiers
            // list
            if ($addCanceled) {
                // Get canceled identifiers array
                $temp = $identifiers[$data[1]]['canceled'];
                // Add identifier to array
                array_push($temp, $data[0]);
                // Update array to results
                $identifiers[$data[1]]['canceled'] = $temp;
            }
            // Update previous values
            $previousClass = $data[1];
            $previousValue = $data[3];
        }
        // Close file
        fclose($fp);

        // Loop through results and update canceled identifiers arrays. 
        // Unused indentifiers that are not canceled must be removed.
        foreach ($identifiers as $key => $value) {
            // If next pointer is greater than zero canceled identifiers must
            // be checked
            if ($value['next'] > 0) {
                // Loop through canceled identifiers of the current range
                foreach ($value['canceled'] as $index => $id) {
                    // If canceled identifier is greater than next pointer
                    // it must be removed
                    if ($id >= $value['next']) {
                        // Remove identifier from results
                        unset($identifiers[$key]['canceled'][$index]);
                    }
                }
            }
        }
        // Return identifiers
        return $identifiers;
    }

    public static function readImportIdentifier2($file, $identifiers, $ismn, $canceled) {
        // Open file that contains identifier range data
        $fp = fopen($file, 'r');

        // Array for results - publisher ranges or canceled publisher ranges
        $results = array();
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
            if ($i == 1 || strlen($data[0]) == 0) {
                continue;
            }

            // Check if we're handling ISMN or ISBN
            if ($ismn && strcmp($data[3], 'M') == 0) {
                // Canceled or used publisher ranges?
                if ($canceled && in_array($data[0], $identifiers[$data[2]]['canceled'])) {
                    $range = self::getCanceledIdentifierRange($data);
                } else if (!$canceled && $data[8] != 0) {
                    if ($identifiers[$data[2]]['next'] == 0 || $data[0] < $identifiers[$data[2]]['next']) {
                        $range = self::getUsedIdentifierRange($data, true);
                    }
                }
            } else if (!$ismn && strcmp($data[3], 'M') != 0) {
                // Canceled or used publisher ranges?
                if ($canceled && in_array($data[0], $identifiers[$data[2]]['canceled'])) {
                    $range = self::getCanceledIdentifierRange($data);
                } else if (!$canceled && $data[8] != 0) {
                    if ($identifiers[$data[2]]['next'] == 0 || $data[0] < $identifiers[$data[2]]['next']) {
                        $range = self::getUsedIdentifierRange($data, false);
                    }
                }
            }
            if (isset($range)) {
                // Add new identifier range to the identifier ranges array. Use 
                // class id as key.
                $results[$data[0]] = $range;
            }
            // Destroy range object
            unset($range);
        }
        // Close file
        fclose($fp);
        // Return results
        return $results;
    }

    private static function getCanceledIdentifierRange($data) {
        // Create a new identifier range
        $range = array(
            'id' => 0,
            'identifier' => $data[6],
            'category' => $data[5],
            'range_id' => $data[2]
        );
        return $range;
    }

    private static function getUsedIdentifierRange($data, $ismn = false) {
        $category = $ismn ? 8 - $data[5] : 6 - $data[5];
        $rangeIdLabel = $ismn ? 'ismn_range_id' : 'isbn_range_id';
        $rangeBegin = str_pad('', $category, "0", STR_PAD_LEFT);
        $rangeEnd = str_pad('', $category, "9", STR_PAD_LEFT);
        $next = strlen($data[9]) < $category ? str_pad($data[9], $category, "0", STR_PAD_LEFT) : $data[9];
        $totalCount = $rangeEnd - $rangeBegin + 1;
        // Create a new identifier range
        $range = array(
            'id' => 0,
            'publisher_identifier' => $data[6],
            'publisher_id' => $data[1],
            $rangeIdLabel => $data[2],
            'category' => $category,
            'range_begin' => $rangeBegin,
            'range_end' => $rangeEnd,
            'free' => $totalCount - $next,
            'taken' => $next - $rangeBegin,
            'canceled' => 0,
            'next' => $next,
            'is_active' => false,
            'is_closed' => ($totalCount == $next ? true : false)
        );
        return $range;
    }

}

?>