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

    public static function previewMarc($publication) {
        // Create serializer
        $serializer = new Marc21PreviewSerializer();
        // Generate MARC
        return self::toMarc($publication, $serializer, "\n");
    }

    public static function rawMarc($publication) {
        // Create serializer
        $serializer = new Marc21RecordSerializer();
        // Generate MARC
        return self::toMarc($publication, $serializer);
    }

    private static function toMarc($publication, $serializer, $spacer = '') {
        // Create variable for MARC record(s)
        $marc = "";
        if (strcmp($publication->publication_format, 'PRINT_ELECTRONICAL') == 0) {
            $marc = self::process($publication, "PRINT", $serializer);
            if (!empty($spacer)) {
                $marc .= $spacer;
            }
            $marc .= self::process($publication, "ELECTRONICAL", $serializer);
        } else {
            $marc = self::process($publication, $publication->publication_format, $serializer);
        }
        return $marc;
    }

    private static function process($publication, $format, $serializer) {
        $record = new MARCRecord();
        // Set leader
        $record->setLeader(self::getLeader());
        // Add control fields
        // Add 007 if electronical publication
        if (self::isElectronical($format)) {
            $record->addControlField(new ControlField('007', self::getField007()));
        }
        // Add 008
        $record->addControlField(new ControlField('008', self::getField008($publication->language, $format, $publication->year)));

        // Add data fields
        // Add 020
        self::addField020($record, $publication, $format);
        // Add 024
        self::addField024($record, $publication, $format);
        // Add 040
        self::addField040($record);
        // Add 041
        self::addField041($record, $publication);
        // Add 100
        self::addField100($record, $publication);
        // Add 245
        self::addField245($record, $publication);
        // Add 250
        self::addField250($record, $publication);
        // Add 255
        self::addField255($record, $publication);
        // Add 263
        self::addField263($record, $publication);
        // Add 264 * 2
        self::addField264a($record, $publication);
        self::addField264b($record, $publication, $format);
        // Add 336
        self::addField336($record, $publication);
        // Add 337
        self::addField337($record, $format);
        // Add 338
        self::addField338($record, $format);
        // Add 490
        self::addField490($record, $publication);
        // Add 502
        self::addField502($record, $publication);
        // Add 530
        self::addField530($record, $publication, $format);
        // Add 538
        self::addField538($record, $format);
        // Add 594
        self::addField594($record, 1);
        self::addField594($record, 2);
        // Add 700
        self::addField700($record, $publication);

        // Serialize record
        return $serializer->serialize($record);
    }

    private static function getLeader() {
        return "00000nam_a22000008i_4500";
    }

    private static function getField007() {
        return "cr|||_||||||||";
    }

    private static function getField008($language, $format, $year) {
        $date = & JFactory::getDate();
        $date = JHtml::date($date, 'Ymd');
        // 0-5
        $field = $date;
        // 6
        $field .= "s";
        // 7-10
        $field .= empty($year) ? '||||' : $year;
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
        $field .= empty($language) ? '|||' : strtolower($language);
        // 38-39
        $field .= "|_";
        // Return value
        return $field;
    }

    private static function addField020($record, $publication, $format) {
        if (!self::isMusic($publication->publication_type)) {
            $datafield = new DataField('020', '_', '_');
            if (!self::isElectronical($format)) {
                $datafield->addSubfield(new Subfield('a', $publication->publication_identifier_print));
                $type = self::getType($publication->type);
                if (!empty($type)) {
                    $datafield->addSubfield(new Subfield('q', $type));
                }
            } else {
                $datafield->addSubfield(new Subfield('a', $publication->publication_identifier_electronical));
                $fileFormat = self::getFileFormat($publication->fileformat);
                if (!empty($fileFormat)) {
                    $datafield->addSubfield(new Subfield('q', $fileFormat));
                }
            }
            $record->addDataField($datafield);
        }
    }

    private static function addField024($record, $publication, $format) {
        if (self::isMusic($publication->publication_type)) {
            $datafield = new DataField('024', '2', '_');
            if (!self::isElectronical($format)) {
                $datafield->addSubfield(new Subfield('a', $publication->publication_identifier_print));
                $type = self::getType($publication->type);
                if (!empty($type)) {
                    $datafield->addSubfield(new Subfield('q', $type));
                }
            } else {
                $datafield->addSubfield(new Subfield('a', $publication->publication_identifier_electronical));
                $fileFormat = self::getFileFormat($publication->fileformat);
                if (!empty($fileFormat)) {
                    $datafield->addSubfield(new Subfield('q', $fileFormat));
                }
            }
            $record->addDataField($datafield);
        }
    }

    private static function addField040($record) {
        $datafield = new DataField('040', '_', '_');
        $datafield->addSubfield(new Subfield('a', 'FI-NL'));
        $datafield->addSubfield(new Subfield('b', 'fin'));
        $datafield->addSubfield(new Subfield('e', 'rda'));
        $record->addDataField($datafield);
    }

    private static function addField041($record, $publication) {
        if (!empty($publication->language)) {
            $datafield = new DataField('041', '0', '_');
            $datafield->addSubfield(new Subfield('a', strtolower($publication->language)));
            $record->addDataField($datafield);
        }
    }

    private static function addField100($record, $publication) {
        if (self::contains($publication->role_1, 'AUTHOR')) {
            $datafield = new DataField('100', '1', '_');
            $datafield->addSubfield(new Subfield('a', $publication->last_name_1 . ', ' . $publication->first_name_1 . ','));
            $datafield->addSubfield(new Subfield('g', 'ENNAKKOTIETO.'));
            $record->addDataField($datafield);
        }
    }

    private static function addField245($record, $publication) {
        $datafield = new DataField('245', '1', '0');
        if (!self::contains($publication->role_1, 'AUTHOR')) {
            $datafield->setInd1('0');
            $datafield->setInd2('0');
        }
        $datafield->addSubfield(new Subfield('a', $publication->title . (empty($publication->subtitle) ? '.' : ' :')));
        if (!empty($publication->subtitle)) {
            $datafield->addSubfield(new Subfield('b', $publication->subtitle . '.'));
        }
        $record->addDataField($datafield);
    }

    private static function addField250($record, $publication) {
        if (!empty($publication->edition)) {
            $datafield = new DataField('250', '_', '_');
            $datafield->addSubfield(new Subfield('a', $publication->edition . '. p.'));
            $record->addDataField($datafield);
        }
    }

    private static function addField255($record, $publication) {
        if (self::isMap($publication->publication_type) && !empty($publication->map_scale)) {
            $datafield = new DataField('255', '0', '_');
            $datafield->addSubfield(new Subfield('a', $publication->map_scale));
            $record->addDataField($datafield);
        }
    }

    private static function addField263($record, $publication) {
        if (!empty($publication->year)) {
            $datafield = new DataField('263', '_', '_');
            $time = $publication->year;
            $time .= empty($publication->month) ? 'KK' : $publication->month;
            $datafield->addSubfield(new Subfield('a', $time));
            $record->addDataField($datafield);
        }
    }

    private static function addField264a($record, $publication) {
        $datafield = new DataField('264', '_', '1');
        if (self::isDissertation($publication->publication_type)) {
            $datafield->addSubfield(new Subfield('a', $publication->locality . ' :'));
        } else {
            $datafield->addSubfield(new Subfield('a', $publication->city . ' :'));
        }
        $datafield->addSubfield(new Subfield('b', $publication->official_name . ','));
        $datafield->addSubfield(new Subfield('c', $publication->year . '.'));
        $record->addDataField($datafield);
    }

    private static function addField264b($record, $publication, $format) {
        if (!self::isElectronical($format)) {
            $datafield = new DataField('264', '_', '3');
            $datafield->addSubfield(new Subfield('a', $publication->printing_house_city . ' :'));
            $datafield->addSubfield(new Subfield('b', $publication->printing_house));
            $record->addDataField($datafield);
        }
    }

    private static function addField336($record, $publication) {
        $datafield = new DataField('336', '_', '_');

        if (self::isMusic($publication->publication_type)) {
            $datafield->addSubfield(new Subfield('a', 'Nuottikirjoitus'));
            $datafield->addSubfield(new Subfield('b', 'ntm'));
        } else if (self::isMap($publication->publication_type)) {
            $datafield->addSubfield(new Subfield('a', 'Kartografinen kuva'));
            $datafield->addSubfield(new Subfield('b', 'cri'));
        } else {
            $datafield->addSubfield(new Subfield('a', 'Teksti'));
            $datafield->addSubfield(new Subfield('b', 'txt'));
        }
        $datafield->addSubfield(new Subfield('2', 'rdacontent'));
        $record->addDataField($datafield);
    }

    private static function addField337($record, $format) {
        $datafield = new DataField('337', '_', '_');
        if (self::isElectronical($format)) {
            $datafield->addSubfield(new Subfield('a', JText::_('COM_ISBNREGISTRY_MARC_337_A_1')));
            $datafield->addSubfield(new Subfield('b', 'c'));
        } else {
            $datafield->addSubfield(new Subfield('a', JText::_('COM_ISBNREGISTRY_MARC_337_A_2')));
            $datafield->addSubfield(new Subfield('b', 'n'));
        }
        $datafield->addSubfield(new Subfield('2', 'rdamedia'));
        $record->addDataField($datafield);
    }

    private static function addField338($record, $format) {
        $datafield = new DataField('338', '_', '_');
        if (self::isElectronical($format)) {
            $datafield->addSubfield(new Subfield('a', 'verkkoaineisto'));
            $datafield->addSubfield(new Subfield('b', 'cr'));
        } else {
            $datafield->addSubfield(new Subfield('a', 'nide'));
            $datafield->addSubfield(new Subfield('b', 'nc'));
        }
        $datafield->addSubfield(new Subfield('2', 'rdacarrier'));
        $record->addDataField($datafield);
    }

    private static function addField490($record, $publication) {
        if (!empty($publication->series)) {
            $datafield = new DataField('490', '0', '_');
            $a = $publication->series;
            $x = $publication->issn;
            $v = $publication->volume;
            if (empty($x) && !empty($v)) {
                $a .= ' ;';
            } else if (!empty($x) && !empty($v)) {
                $a .= ',';
                $x .= ' ;';
            } else if (!empty($x) && empty($v)) {
                $a .= ',';
            }
            $datafield->addSubfield(new Subfield('a', $a));
            if (!empty($x)) {
                $datafield->addSubfield(new Subfield('x', $x));
            }
            if (!empty($v)) {
                $datafield->addSubfield(new Subfield('v', $v));
            }
            $record->addDataField($datafield);
        }
    }

    private static function addField502($record, $publication) {
        if (self::isDissertation($publication->publication_type)) {
            $datafield = new DataField('502', '_', '_');
            $datafield->addSubfield(new Subfield('a', 'Diss. : '));
            $datafield->addSubfield(new Subfield('c', $publication->official_name . '.'));
            $datafield->addSubfield(new Subfield('9', 'FENNI<KEEP>'));
            $record->addDataField($datafield);
        }
    }

    private static function addField530($record, $publication, $format) {
        if (strcmp($publication->publication_format, 'PRINT_ELECTRONICAL') == 0) {
            $datafield = new DataField('530', '_', '_');
            if (!self::isElectronical($format)) {
                $a = JText::_('COM_ISBNREGISTRY_MARC_530_A_PRINT') . ' ' . $publication->publication_identifier_type . ' ';
                $a .= $publication->publication_identifier_electronical;
                $fileFormat = self::getFileFormat($publication->fileformat);
                if (!empty($fileFormat)) {
                    $a .= ' (' . $fileFormat . ')';
                }
                $datafield->addSubfield(new Subfield('a', $a));
            } else {
                $a = JText::_('COM_ISBNREGISTRY_MARC_530_A_ELECTRONICAL') . ' ' . $publication->publication_identifier_type . ' ';
                $a .= $publication->publication_identifier_print;
                $type = self::getType($publication->type);
                if (!empty($type)) {
                    $a .= ' (' . $type . ')';
                }
                $datafield->addSubfield(new Subfield('a', $a));
            }
            $datafield->addSubfield(new Subfield('9', 'FENNI<KEEP>'));
            $record->addDataField($datafield);
        }
    }

    private static function addField538($record, $format) {
        if (self::isElectronical($format)) {
            $datafield = new DataField('538', '_', '_');
            $datafield->addSubfield(new Subfield('a', 'Internet-yhteys.'));
            $datafield->addSubfield(new Subfield('9', 'FENNI<KEEP>'));
            $record->addDataField($datafield);
        }
    }

    private static function addField594($record, $selection) {
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
        $record->addDataField($datafield);
    }

    private static function addField700($record, $publication) {
        if (!self::contains($publication->role_1, 'AUTHOR')) {
            $datafield = new DataField('700', '1', '_');
            $datafield->addSubfield(new Subfield('a', $publication->last_name_1 . ', ' . $publication->first_name_1 . ','));
            $datafield->addSubfield(new Subfield('g', 'ENNAKKOTIETO.'));
            $record->addDataField($datafield);
        }
    }

    private static function isElectronical($format) {
        if (strcmp($format, 'ELECTRONICAL') == 0) {
            return true;
        }
        return false;
    }

    private static function isDissertation($publicationType) {
        if (strcmp($publicationType, 'DISSERTATION') == 0) {
            return true;
        }
        return false;
    }

    private static function isMusic($publicationType) {
        if (strcmp($publicationType, 'SHEET_MUSIC') == 0) {
            return true;
        }
        return false;
    }

    private static function isMap($publicationType) {
        if (strcmp($publicationType, 'MAP') == 0) {
            return true;
        }
        return false;
    }

    private static function contains($haystack, $needle) {
        if (strpos($haystack, $needle) !== false) {
            return true;
        }
        return false;
    }

    private static function endsWithChar($haystack, $needle) {
        return ($needle[strlen($needle) - 1] === $haystack);
    }

    private static function getType($type) {
        if (empty($type)) {
            return '';
        }
        if (self::contains($type, 'PAPERBACK')) {
            return 'nidottu';
        } else if (self::contains($type, 'HARDBACK')) {
            return 'sidottu';
        } else if (self::contains($type, 'SPIRAL_BINDING')) {
            return 'rengaskirja';
        }
        return '';
    }

    private static function getFileFormat($format) {
        if (empty($format)) {
            return '';
        }
        if (self::contains($format, 'PDF')) {
            return 'PDF';
        } else if (self::contains($format, 'EPUB')) {
            return 'EPUB';
        } else if (self::contains($format, 'CD_ROM')) {
            return 'CD-ROM';
        }
        return '';
    }

    /**
     * Creates an array for generating a CSV file. Adds required headers and
     * publications data.
     * @param array $publications publications to be added to the CSV file
     * @return array headers and publications array
     */
    public static function toCSVArray($publications) {
        // Array for results
        $list = array();
        // Add publications helper file
        require_once JPATH_COMPONENT . '/helpers/publishers.php';
        // CSV headers
        $headers = PublishersHelper::getPIIDHeaders();
        // Add headers
        array_push($list, $headers);
        // Loop through the publications
        foreach ($publications as $publication) {
            array_push($list, self::publicationToArray($publication));
        }
        // Return results
        return $list;
    }

    private static function publicationToArray($publication) {

        $publisherArr = array(
            'A',
            'A',
            empty($publication->publication_identifier_print) ? $publication->publication_identifier_electronical : $publication->publication_identifier_print,
            $publication->official_name,
            'FI',
            $publication->address,
            $publication->zip . ' ' . $publication->city,
            '',
            '',
            $publication->contact_person,
            // Add on space after phone that Excel considers it as a string
            $publication->phone . ' ',
            '',
            $publication->email,
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            $publication->www,
            '',
            ''
        );
        return $publisherArr;
    }

}

?>