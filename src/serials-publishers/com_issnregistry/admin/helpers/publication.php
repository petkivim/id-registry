<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
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

    public static function previewMarc($publication, $form, $publisher) {
        // Create serializer
        $serializer = new Marc21PreviewSerializer();
        // Generate MARC
        return self::toMarc($publication, $serializer, $form, $publisher);
    }

    public static function rawMarc($publication, $form, $publisher) {
        // Create serializer
        $serializer = new Marc21RecordSerializer();
        // Generate MARC
        return self::toMarc($publication, $serializer, $form, $publisher);
    }

    private static function toMarc($publication, $serializer, $form, $publisher) {
        // Create MARC record
        return self::process($publication, $publication->medium, $serializer, $form, $publisher);
    }

    private static function process($publication, $format, $serializer, $form, $publisher) {
        $record = new MARCRecord();
        // Set leader
        $record->setLeader(self::getLeader());
        // Add control fields
        // Add 007
        $record->addControlField(new ControlField('007', self::getField007($format)));
        // Add 008
        $record->addControlField(new ControlField('008', self::getField008($publication->language, $format, $publication->issued_from_year, $publication->frequency)));

        // Add data fields
        // Add 022
        self::addField022($record, $publication);
        // Add 040
        self::addField040($record);
        // Add 041
        self::addField041($record, $publication);
        // Add 222
        self::addField222($record, $publication, $format);
        // Add 245
        self::addField245($record, $publication, $publisher);
        // Add 263
        self::addField263($record, $publication);
        // Add 264 * 2
        self::addField264a($record, $publication, $form, $publisher);
        self::addField264b($record, $publication);
        // Add 310
        self::addField310($record, $publication);
        // Add 336
        self::addField336($record);
        // Add 337
        self::addField337($record, $format);
        // Add 338
        self::addField338($record, $format);
        // Add 362
        self::addField362($record, $publication);
        // Add 538
        self::addField538($record, $format);
        // Add 594
        self::addField594($record);
        // Add 710
        self::addField710($record, $form, $publisher);
        // Add 760
        self::addField760($record, $publication);
        // Add 762
        self::addField762($record, $publication);
        // Add 776
        self::addField776($record, $publication, $format);
        // Add 776
        self::addField780($record, $publication);
        // Add 856
        self::addField856($record, $publication, $format);
        // Add 935
        self::addField935($record);
        // Serialize record
        return $serializer->serialize($record);
    }

    private static function getLeader() {
        return "00000nas a22000008i 4500";
    }

    private static function getField007($format) {
        if (self::isElectronical($format)) {
            return "cr||||||||||||";
        } else {
            return "ta||||||||||||";
        }
    }

    private static function getField008($language, $format, $year, $frequency) {
        $date = JFactory::getDate();
        $date = JHtml::date($date, 'ymd');
        // 0-5
        $field = $date;
        // 6
        $field .= "c";
        // 7-10
        $field .= empty($year) ? '||||' : $year;
        // 11-14
        $field .= "9999";
        // 15-17
        $field .= "fi ";
        // 18
        $field .= empty($frequency) ? '_' : $frequency;
        // 19-20
        $field .= "| ";
        // 21
        $field .= "|";
        // 22
        $field .= "|";
        // 23 - publication format
        if (self::isElectronical($format)) {
            $field .= "o";
        } else {
            $field .= "_";
        }
        // 24-34
        $field .= "_____0|||b0";
        // 35-37
        $field .= empty($language) ? '|||' : strtolower($language);
        // 38-39
        $field .= "|_";
        // Return value
        return $field;
    }

    private static function addField022($record, $publication) {
        $datafield = new DataField('022', '0', ' ');
        $datafield->addSubfield(new Subfield('a', $publication->issn));
        $datafield->addSubfield(new Subfield('2', 'a'));
        $record->addDataField($datafield);
    }

    private static function addField040($record) {
        $datafield = new DataField('040', ' ', ' ');
        $datafield->addSubfield(new Subfield('a', 'FI-NL'));
        $datafield->addSubfield(new Subfield('b', 'fin'));
        $datafield->addSubfield(new Subfield('e', 'rda'));
        $record->addDataField($datafield);
    }

    private static function addField041($record, $publication) {
        if (!empty($publication->language)) {
            $datafield = new DataField('041', '0', ' ');
            $datafield->addSubfield(new Subfield('a', strtolower($publication->language)));
            $record->addDataField($datafield);
        }
    }

    private static function addField222($record, $publication, $format) {
        $datafield = new DataField('222', ' ', '0');
        $datafield->addSubfield(new Subfield('a', $publication->title));
        if (self::isElectronical($format)) {
            $datafield->addSubfield(new Subfield('b', '(Verkkoaineisto)'));
        } else {
            $datafield->addSubfield(new Subfield('b', '(Painettu)'));
        }
        $record->addDataField($datafield);
    }

    private static function addField245($record, $publication, $publisher) {
        $datafield = new DataField('245', '0', '0');
        $title = $publication->title;
        if (empty($publication->subtitle) && empty($publisher)) {
            $title .= '.';
        } else if (!empty($publication->subtitle)) {
            $title .= ' :';
        } else if (!empty($publisher)) {
            $title .= ' /';
        }
        $datafield->addSubfield(new Subfield('a', $title));
        if (!empty($publication->subtitle)) {
            $datafield->addSubfield(new Subfield('b', $publication->subtitle . (empty($publisher) ? '.' : ' /')));
        }
        if (!empty($publisher)) {
            $datafield->addSubfield(new Subfield('c', $publisher->official_name . '.'));
        }
        $record->addDataField($datafield);
    }

    private static function addField263($record, $publication) {
        if (!empty($publication->issued_from_year)) {
            $datafield = new DataField('263', ' ', ' ');
            $datafield->addSubfield(new Subfield('a', $publication->issued_from_year));
            $record->addDataField($datafield);
        }
    }

    private static function addField264a($record, $publication, $form, $publisher) {
        $datafield = new DataField('264', ' ', '1');
        $datafield->addSubfield(new Subfield('a', $publication->place_of_publication . ' :'));
        $name = !empty($form->publisher) ? $form->publisher : $publisher->official_name;
        $datafield->addSubfield(new Subfield('b', $name . ','));
        $datafield->addSubfield(new Subfield('c', $publication->issued_from_year . '-'));
        $record->addDataField($datafield);
    }

    private static function addField264b($record, $publication) {
        if (!empty($publication->printer)) {
            $datafield = new DataField('264', ' ', '3');
            $datafield->addSubfield(new Subfield('b', $publication->printer));
            $record->addDataField($datafield);
        }
    }

    private static function addField310($record, $publication) {
        $datafield = new DataField('310', ' ', ' ');
        if (strcmp($publication->frequency, 'z') == 0 && !empty($publication->frequency_other)) {
            $datafield->addSubfield(new Subfield('a', $publication->frequency_other));
        } else {
            $datafield->addSubfield(new Subfield('a', JText::_('COM_ISSNREGISTRY_PUBLICATION_FIELD_FREQUENCY_' . strtoupper($publication->frequency))));
        }
        $record->addDataField($datafield);
    }

    private static function addField336($record) {
        $datafield = new DataField('336', ' ', ' ');
        $datafield->addSubfield(new Subfield('a', 'Teksti'));
        $datafield->addSubfield(new Subfield('b', 'txt'));
        $datafield->addSubfield(new Subfield('2', 'rdacontent'));
        $record->addDataField($datafield);
    }

    private static function addField337($record, $format) {
        $datafield = new DataField('337', ' ', ' ');
        if (self::isElectronical($format)) {
            $datafield->addSubfield(new Subfield('a', JText::_('COM_ISSNREGISTRY_MARC_337_A_1')));
            $datafield->addSubfield(new Subfield('b', 'c'));
        } else {
            $datafield->addSubfield(new Subfield('a', JText::_('COM_ISSNREGISTRY_MARC_337_A_2')));
            $datafield->addSubfield(new Subfield('b', 'n'));
        }
        $datafield->addSubfield(new Subfield('2', 'rdamedia'));
        $record->addDataField($datafield);
    }

    private static function addField338($record, $format) {
        $datafield = new DataField('338', ' ', ' ');
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

    private static function addField362($record, $publication) {
        $datafield = new DataField('362', '0', ' ');
        $datafield->addSubfield(new Subfield('a', $publication->issued_from_number . '-'));
        $record->addDataField($datafield);
    }

    private static function addField538($record, $format) {
        if (self::isElectronical($format)) {
            $datafield = new DataField('538', ' ', ' ');
            $datafield->addSubfield(new Subfield('a', 'Internet-yhteys.'));
            $datafield->addSubfield(new Subfield('9', 'FENNI<KEEP>'));
            $record->addDataField($datafield);
        }
    }

    private static function addField594($record) {
        $datafield = new DataField('594', ' ', ' ');
        $datafield->addSubfield(new Subfield('a', 'ENNAKKOTIETO KANSALLISKIRJASTO.'));
        $datafield->addSubfield(new Subfield('5', 'FENNI'));
        $record->addDataField($datafield);
    }

    private static function addField710($record, $form, $publisher) {
        $datafield = new DataField('710', '2', ' ');
        $name = !empty($form->publisher) ? $form->publisher : $publisher->official_name;
        $datafield->addSubfield(new Subfield('a', $name . '.'));
        $record->addDataField($datafield);
    }

    private static function addField760($record, $publication) {
        // Get JSON object
        $json = json_decode($publication->main_series);
        // Check that JSON is not null
        if ($json) {
            for ($i = 0; $i < sizeof($json->{'title'}); $i++) {
                // Skip empty fields
                if (empty($json->{'title'}[$i]) && empty($json->{'issn'}[$i])) {
                    continue;
                }
                $datafield = new DataField('760', '0', '#');
                $datafield->addSubfield(new Subfield('t', $json->{'title'}[$i]));
                $datafield->addSubfield(new Subfield('x', $json->{'issn'}[$i]));
                $datafield->addSubfield(new Subfield('9', 'FENNI<KEEP>'));
                $record->addDataField($datafield);
            }
        }
    }

    private static function addField762($record, $publication) {
        // Get JSON object
        $json = json_decode($publication->subseries);
        // Check that JSON is not null
        if ($json) {
            for ($i = 0; $i < sizeof($json->{'title'}); $i++) {
                // Skip empty fields
                if (empty($json->{'title'}[$i]) && empty($json->{'issn'}[$i])) {
                    continue;
                }
                $datafield = new DataField('762', '0', ' ');
                $datafield->addSubfield(new Subfield('t', $json->{'title'}[$i]));
                $datafield->addSubfield(new Subfield('x', $json->{'issn'}[$i]));
                $datafield->addSubfield(new Subfield('9', 'FENNI<KEEP>'));
                $record->addDataField($datafield);
            }
        }
    }

    private static function addField776($record, $publication, $format) {
        // Get JSON object
        $json = json_decode($publication->another_medium);
        // Check that JSON is not null
        if ($json) {
            for ($i = 0; $i < sizeof($json->{'title'}); $i++) {
                $datafield = new DataField('776', '0', ' ');
                // Skip empty fields
                if (empty($json->{'title'}[$i]) && empty($json->{'issn'}[$i])) {
                    continue;
                }
                $datafield->addSubfield(new Subfield('t', $json->{'title'}[$i]));
                if (!self::isElectronical($format)) {
                    $datafield->addSubfield(new Subfield('c', JText::_('COM_ISSNREGISTRY_MARC_776_B_PRINT')));
                } else {
                    $datafield->addSubfield(new Subfield('c', JText::_('COM_ISSNREGISTRY_MARC_776_B_ELECTRONICAL')));
                }
                $datafield->addSubfield(new Subfield('x', $json->{'issn'}[$i]));
                $datafield->addSubfield(new Subfield('9', 'FENNI<KEEP>'));
                $record->addDataField($datafield);
            }
        }
    }

    private static function addField780($record, $publication) {
        // Get JSON object
        $json = json_decode($publication->previous);
        // Check that JSON is not null
        if ($json) {
            for ($i = 0; $i < sizeof($json->{'title'}); $i++) {
                // Skip empty fields
                if (empty($json->{'title'}[$i]) && empty($json->{'issn'}[$i])) {
                    continue;
                }
                $datafield = new DataField('780', '0', '0');
                $datafield->addSubfield(new Subfield('t', $json->{'title'}[$i]));
                $datafield->addSubfield(new Subfield('c', '()'));
                $datafield->addSubfield(new Subfield('x', $json->{'issn'}[$i]));
                $datafield->addSubfield(new Subfield('9', 'FENNI<KEEP>'));
                $record->addDataField($datafield);
            }
        }
    }

    private static function addField856($record, $publication, $format) {
        if (self::isElectronical($format) && !empty($publication->url)) {
            $datafield = new DataField('856', '4', '0');
            $datafield->addSubfield(new Subfield('u', $publication->url));
            $datafield->addSubfield(new Subfield('y', JText::_('COM_ISSNREGISTRY_MARC_856_Y')));
            $record->addDataField($datafield);
        }
    }

    private static function addField935($record) {
        $date = JFactory::getDate();
        $date = JHtml::date($date, 'Y');
        $datafield = new DataField('935', ' ', ' ');
        $datafield->addSubfield(new Subfield('a', $date));
        $datafield->addSubfield(new Subfield('5', 'FENNI'));
        $record->addDataField($datafield);
    }

    private static function isElectronical($format) {
        if (strcmp($format, 'PRINTED') != 0) {
            return true;
        }
        return false;
    }

}

?>