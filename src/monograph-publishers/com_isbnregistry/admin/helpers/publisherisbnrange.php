<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Publisher isbn range helper.
 *
 * @since  1.6
 */
class PublishersisbnrangeHelper extends JHelperContent {

    /**
     * Counts the check digit for ISBN-10 (without dashes and without check digit)
     *
     * @param string $isbn ISBN
     *
     * @return integer Resulting check digit
     */
    public static function countIsbn10CheckDigit($isbn) {
        $sum_of_digits = 0;

        for ($i = 0; $i < strlen($isbn); $i++) {
            $sum_of_digits += $isbn{$i} * ($i + 1);
        }

        $check_digit = $sum_of_digits % 11;
        $check_digit = ( $check_digit == 10 ? 'X' : $check_digit);
        return $check_digit;
    }

    /**
     * Counts the check digit for ISBN-13 (without dashes and without check digit)
     *
     * @param string $isbn ISBN
     *
     * @return integer Resulting check digit
     */
    public static function countIsbn13CheckDigit($isbn) {
        $sum_of_digits = 0;

        for ($i = 0; $i < strlen($isbn); $i++) {
            if ($i % 2 == 0) {
                $sum_of_digits += $isbn{$i};
            } else {
                $sum_of_digits += $isbn{$i} * 3;
            }
        }
        $check_digit = (10 - ($sum_of_digits % 10)) % 10;
        return $check_digit;
    }

    /**
     * Counts the check digit for ISBN-10 and ISBN-13 (without dashes)
     *
     * @param string $isbn ISBN
     *
     * @return boolean|integer Resulting check digit or false for invalid string
     */
    public static function countIsbnCheckDigit($isbn) {
        if (preg_match('{^([0-9]{9})[0-9xX]{0,1}$}', $isbn, $matches)) {
            return self::countIsbn10CheckDigit($matches[1]);
        } elseif (preg_match('{^([0-9]{12})[0-9]{0,1}$}', $isbn, $matches)) {
            return self::countIsbn13CheckDigit($matches[1]);
        } else {
            return false;
        }
    }

    /**
     * Convert ISBN-10 (without dashes) to ISBN-13
     *
     * @param string $isbn ISBN
     *
     * @return boolean|string Resulting ISBN or false for invalid string
     */
    public static function isbn10To13($isbn) {
        if (!preg_match('{^([0-9]{9})[0-9xX]$}', $isbn, $matches)) {
            return false;
        }

        $isbn = '978' . $matches[1];
        $check_digit = self::countIsbn13CheckDigit($isbn);

        return $isbn . $check_digit;
    }

    /**
     * Convert ISBN-13 (without dashes) to ISBN-10
     * Only ISBN-13 starting with '978' can be converted to ISBN-10
     *
     * @param string $isbn ISBN
     *
     * @return boolean|string Resulting ISBN or false for invalid string
     */
    public static function isbn13To10($isbn) {
        if (!preg_match('{^978([0-9]{9})[0-9]$}', $isbn, $matches)) {
            return false;
        }

        $isbn = $matches[1];
        $check_digit = self::countIsbn10CheckDigit($isbn);

        return $isbn . $check_digit;
    }

    /**
     * Check the consistency of the ISBN-10 using the built-in checksum
     *
     * @param string $isbn ISBN
     *
     * @return boolean True foe valid ISBN, false for invalid ISBN
     */
    public static function validateIsbn10($isbn) {
        $calculated_check_digit = self::countIsbnCheckDigit($isbn);
        return ( ($calculated_check_digit == $isbn{9}) ? true : false);
    }

    /**
     * Check the consistency of the ISBN-13 using the built-in checksum
     *
     * @param string $isbn ISBN
     *
     * @return boolean True foe valid ISBN, false for invalid ISBN
     */
    public static function validateIsbn13($isbn) {
        $calculated_check_digit = self::countIsbnCheckDigit($isbn);
        return ( ($calculated_check_digit == $isbn{12}) ? true : false);
    }

    /**
     * Check the consistency of the ISBN using the built-in checksum
     *
     * @param string $isbn ISBN
     *
     * @return boolean True foe valid ISBN, false for invalid ISBN
     */
    public static function validate($isbn) {
        if (preg_match('{^[0-9]{9}[0-9xX]$}', $isbn, $matches)) {
            return self::validateIsbn10($isbn);
        } elseif (preg_match('{^[0-9]{13}$}', $isbn, $matches)) {
            return self::validateIsbn13($isbn);
        } else {
            return false;
        }
    }

}
