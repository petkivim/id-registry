<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * ISSN range helper.
 *
 * @since  1.6
 */
class IssnrangeHelper extends JHelperContent {

    /**
     * Counts the check digit for ISSN (without check digit).
     *
     * @param string $issn ISSN without check digit
     *
     * @return integer|string Resulting check digit
     */
    public static function countIssnCheckDigit($issn) {
        $sum_of_digits = 0;
        // Remove the dash
        $issn = str_replace('-', '', $issn);
        
        for ($i = 0; $i < strlen($issn); $i++) {
            $sum_of_digits += $issn{$i} * (8 - $i);
        }

        $check_digit = (11 - ($sum_of_digits % 11)) % 11;
        $check_digit = ( $check_digit == 10 ? 'X' : $check_digit);
        return $check_digit;
    }

    /**
     * Check the consistency of the ISSN using the built-in checksum
     *
     * @param string $issn ISSN
     *
     * @return boolean True for valid ISSN, false for invalid ISSN
     */
    public static function validateIssn($issn) {
        $issn = str_replace('-', '', $issn);
        $calculated_check_digit = self::countIssnCheckDigit(substr($issn,0,7));
        return ( ($calculated_check_digit == $issn{7}) ? true : false);
    }

}
