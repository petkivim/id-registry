<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_donation
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/helpers/marc21tools.php';

/**
 * Publications helper.
 *
 * @since  1.6
 */
class PublicationHelper extends JHelperContent {

    public static function toMarc($publication) {
        $marc = "";
        if (strcmp($publication->publication_format, 'PRINT_ELECTRONICAL') == 0) {
            $marc = self::process($publication, "PRINT");
            $marc .= self::process($publication, "ELECTRONICAL");
        } else {
            $marc = self::process($publication, $publication->publication_format);
        }
        return $marc;
    }

    private static function process($publication, $format) {
        $record = new MARCRecord();
        // Set leader
        $record->setLeader(self::getLeader());
        // Add 007 if electronical publication
        if (self::isElectronical($format)) {
            $record->addControlField(new ControlField('007', self::getField007()));
        }
        // Add 008
        $record->addControlField(new ControlField('008', self::getField008($publication->language, $format)));
        // Add 040
        $record->addDataField(self::getField040());
        // Add 336
        $record->addDataField(self::getField336());
        // Add 337
        $record->addDataField(self::getField337($format));
        // Add 338
        $record->addDataField(self::getField338($format));
        // Add 594
        $record->addDataField(self::getField594(1));
        $record->addDataField(self::getField594(2));

        // TODO: Change "getField" calls so that $record is passed as a reference
        // and functions don't need to return any value
        
        // Create serializer
        $serializer = new Marc21RecordSerializer();
        // Serialize record
        return $serializer->serialize($record);
    }

    private static function getLeader() {
        return "00000nam_a22000008i_4500";
    }

    private static function getField007() {
        return "cr|||_||||||||";
    }

    private static function getField008($language, $format) {
        $date = & JFactory::getDate();
        $date = JHtml::date($date, 'Ymd');
        // 0-5
        $field = $date;
        // 6
        $field .= "s";
        // 7-10
        $field .= $publication->year;
        // 11-14
        $field .= "____";
        // 15-17
        $field .= "fi ";
        // 18-22
        $field .= "||||_";
        // 23 - publication format
        if (strcmp($format, 'ELECTRONICAL') == 0) {
            $field .= "o";
        } else {
            $field .= "_";
        }
        // 24-34
        $field .= "_____|000|";
        // 35-37
        $field .= strtolower($language);
        // 38-39
        $field .= "|_";
        // Return value
        return $field;
    }

    private static function getField040() {
        $datafield = new DataField('040', '_', '_');
        $datafield->addSubfield(new Subfield('a', 'FI-NL'));
        $datafield->addSubfield(new Subfield('b', 'fin'));
        $datafield->addSubfield(new Subfield('e', 'rda'));
        return $datafield;
    }

    private static function getField100() {
        $datafield = new DataField('040', '_', '_');
        $datafield->addSubfield(new Subfield('a', 'FI-NL'));
        $datafield->addSubfield(new Subfield('b', 'fin'));
        $datafield->addSubfield(new Subfield('e', 'rda'));
        return $datafield;
    }

    private static function getField336() {
        $datafield = new DataField('336', '_', '_');
        $datafield->addSubfield(new Subfield('a', 'Teksti'));
        $datafield->addSubfield(new Subfield('b', 'txt'));
        $datafield->addSubfield(new Subfield('2', 'rdacontent'));
        return $datafield;
    }

    private static function getField337($format) {
        $datafield = new DataField('337', '_', '_');
        if (self::isElectronical($format)) {
            $datafield->addSubfield(new Subfield('a', utf8_encode(JText::_('COM_ISBNREGISTRY_MARC_337_A_1'))));
            $datafield->addSubfield(new Subfield('b', 'c'));
        } else {
            $datafield->addSubfield(new Subfield('a', utf8_encode(JText::_('COM_ISBNREGISTRY_MARC_337_A_2'))));
            $datafield->addSubfield(new Subfield('b', 'n'));
        }
        $datafield->addSubfield(new Subfield('2', 'rdamedia'));
        return $datafield;
    }

    private static function getField338($format) {
        $datafield = new DataField('338', '_', '_');
        if (self::isElectronical($format)) {
            $datafield->addSubfield(new Subfield('a', 'verkkoaineisto'));
            $datafield->addSubfield(new Subfield('b', 'cr'));
        } else {
            $datafield->addSubfield(new Subfield('a', 'nide'));
            $datafield->addSubfield(new Subfield('b', 'nc'));
        }
        $datafield->addSubfield(new Subfield('2', 'rdacarrier'));
        return $datafield;
    }

    private static function getField594($selection) {
        $datafield = new DataField('594', '_', '_');
        switch ($selection) {
            case 1:
                $datafield->addSubfield(new Subfield('a', 'ENNAKKOTIETO KANSALLISKIRJASTO.'));
                break;
            case 2:
                $datafield->addSubfield(new Subfield('a', 'EI VASTAANOTETTU'));
                break;
        }
        $datafield->addSubfield(new Subfield('5', 'FENNI'));
        return $datafield;
    }

    private static function isElectronical($format) {
        if (strcmp($format, 'ELECTRONICAL') == 0) {
            return true;
        }
        return false;
    }

}

?>