<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * The MARCRecord class defines the basic structure of a MARC21 record. 
 * MARC record consists of leader, directory, control fields and data fields 
 * and they all have have their own characteristics. 
 */
class MARCRecord {

    protected $leader;
    protected $controlFields;
    protected $dataFields;

    public function __construct() {
        $this->leader = "";
        $this->controlFields = array();
        $this->dataFields = array();
    }

    public function setLeader($leader) {
        $this->leader = $leader;
    }

    public function getLeader() {
        return $this->leader;
    }

    public function addControlField($field) {
        array_push($this->controlFields, $field);
    }

    public function addDataField($field) {
        array_push($this->dataFields, $field);
    }

    public function getControlFields() {
        return $this->controlFields;
    }

    public function getDataFields() {
        return $this->dataFields;
    }

}

/**
 * The Field class is an abstract class that defines the basic
 * structure of a field that is an element of a Marc record. Marc record
 * contains three different kind of fields: control fields, data fields and
 * subfields. Control fields are all fixed length and they don't contain
 * indicators or subfields. Data fields instead are mixed length and they
 * consist of one or two indicators and one or more subfields. Control fields,
 * data fields and subfields are all extending this abstract class.
 */
abstract class Field {

    private $tag;
    private $data;

    public function __construct($tag, $data) {
        $this->tag = $tag;
        $this->data = $data;
    }

    public function getTag() {
        return $this->tag;
    }

    public function getData() {
        return $this->data;
    }

}

/**
 * The ControlField class extends the Field class. ControlField class represents 
 * a control field in a Marc record. Control fields contain control numbers 
 * and other kinds of control and coded information that are used in the 
 * processing of machine-readable bibliographic records. Specific data elements 
 * are positionally defined. Control fields are fixed length and they don't have 
 * indicators or subfields. Control fields' field code is less than 010. 
 * All the fields which field is 00X, are control fields.
 */
class ControlField extends Field {

    public function __construct($tag, $data) {
        parent::__construct($tag, $data);
    }

}

/**
 * The DataField class extends the Field class. DataField class represents a 
 * data field in a Marc record. Data fields contain all kind of data elements 
 * related to the record. Data fields are mixed length, they have one or 
 * two indicators and one or more subfields. The data elements that a data 
 * field is holding are placed in subfields. Subfields must be in a certain 
 * order and the order can vary with data fields.
 */
class DataField extends Field {

    private $ind1;
    private $ind2;
    private $subfields;

    public function __construct($tag, $ind1, $ind2) {
        parent::__construct($tag, '');
        $this->ind1 = $ind1;
        $this->ind2 = $ind2;
        $this->subfields = array();
    }

    public function getInd1() {
        return $this->ind1;
    }

    public function setInd1($value) {
        $this->ind1 = $value;
    }

    public function getInd2() {
        return $this->ind2;
    }

    public function setInd2($value) {
        $this->ind2 = $value;
    }

    public function getSubfields() {
        return $this->subfields;
    }

    public function addSubfield($subfield) {
        array_push($this->subfields, $subfield);
    }

    public function getSubields() {
        return $this->subfields;
    }

}

/**
 * The Subfield class extends the Field class. Subfield class represents a 
 * subfield of a data field in a Marc record. Data fields contain all kind of 
 * data elements relating to the record. The data elements that a data field 
 * is holding are placed in subfields. Each subfield holds a subfield code
 *  and the actual data.
 */
class Subfield extends Field {

    public function __construct($tag, $data) {
        parent::__construct($tag, $data);
    }

}

/**
 * Interface that defines a method for serializing Record objects.
 */
interface RecordSerializer {

    public function serialize($record);
}

/**
 * The Marc21RecordSerializer class implements  RecordSerializer interface 
 * and it can be used for representing Record in a Marc21 format.
 */
class Marc21RecordSerializer implements RecordSerializer {

    private $fieldTerminator;
    private $subfieldDelimiter;
    private $recordTerminator;

    public function __construct() {
        // Field terminator (\u001E, \x1e, chr(30))
        $this->fieldTerminator = "\x1e";
        // Subfield delimiter (\u001F, \x1f, chr(31))
        $this->subfieldDelimiter = "\x1f";
        // Record terminator (\u001D, \x1d, chr(29))
        $this->recordTerminator = "\x1d";
    }

    /**
     * Converts the given record to a Marc21 string.
     * @param MARCRecord $record MARC record to be serialized
     * @return String Record as a Marc21 String
     */
    public function serialize($record) {
        // Variable for record directory
        $directory = '';
        // Variable for record data
        $data = '';
        // Go through control fields
        foreach ($record->getControlFields() as $field) {
            // Field code
            $directory .= $field->getTag();
            // Field length + field terminator - 4 character positions
            $directory .= str_pad(strlen($field->getData()) + 1, 4, "0", STR_PAD_LEFT);
            // Starting character position - 5 positions
            $directory .= str_pad(strlen($data), 5, "0", STR_PAD_LEFT);
            // Add value and field terminator
            $data .= $field->getData() . $this->fieldTerminator;
        }
        // Go through all the data fields
        foreach ($record->getDataFields() as $field) {
            // Create buffer variable
            $buffer = '';
            // Add indicators
            $buffer .= $field->getInd1();
            $buffer .= $field->getInd2();
            foreach ($field->getSubfields() as $subfield) {
                // Add delimiter
                $buffer .= $this->subfieldDelimiter;
                // Add code
                $buffer .= $subfield->getTag();
                // Add data
                $buffer .= $subfield->getData();
            }
            // Add field terminator
            $buffer .= $this->fieldTerminator;
            // Update directory
            // Field code
            $directory .= $field->getTag();
            // Field length - 4 character positions
            $directory .= str_pad(mb_strlen(utf8_encode($buffer), 'UTF-8'), 4, "0", STR_PAD_LEFT);
            // Starting character position - 5 positions
            $directory .= str_pad(mb_strlen(utf8_encode($data), 'UTF-8'), 5, "0", STR_PAD_LEFT);
            // Add data
            $data .= $buffer;
        }
        // Add directory field terminator
        $directory .= $this->fieldTerminator;
        // Add record terminator
        $data .= $this->recordTerminator;

        // Generate MARC21 string
        // Add leader
        $marc21 = $record->getLeader();
        // Add directory
        $marc21 .= $directory;
        // Add data
        $marc21 .= $data;

        // Calculate base address
        $base = strlen($record->getLeader()) + strlen($directory);
        // Base address length - 5 character positions
        $base_str = str_pad($base, 5, "0", STR_PAD_LEFT);
        // Update base address
        $marc21 = substr_replace($marc21, $base_str, 12, 5);
        // Calculate record length
        $length = $base + mb_strlen(utf8_encode($data), 'UTF-8');
        // Record length - 5 character positions
        $length_str = str_pad($length, 5, "0", STR_PAD_LEFT);
        // Update record length
        $marc21 = substr_replace($marc21, $length_str, 0, 5);
        // Return Marc21 string
        return $marc21;
    }

}

/**
 * The Marc21PreviewSerializer class implements RecordSerializer interface 
 * and it can be used for previewing MARC records. It returns the records
 * in a human readable format.
 */
class Marc21PreviewSerializer implements RecordSerializer {

    /**
     * Converts the given record to a human readable format.
     * @param MARCRecord $record MARC record to be serialized
     * @return String Record in a human readable format
     */
    public function serialize($record) {
        // Add leader
        $marc = 'LDR    ' . $record->getLeader() . "\n";
        // Go through control fields
        foreach ($record->getControlFields() as $field) {
            // Field code
            $marc .= $field->getTag() . '    ';
            // Field data
            $marc .= $field->getData() . "\n";
        }
        // Go through all the data fields
        foreach ($record->getDataFields() as $field) {
            // Field code
            $marc .= $field->getTag() . ' ';
            // Add indicators
            $marc .= $field->getInd1();
            $marc .= $field->getInd2() . ' ';
            foreach ($field->getSubfields() as $subfield) {
                // Add code
                $marc .= '$' . $subfield->getTag() . ' ';
                // Add data
                $marc .= $subfield->getData() . ' ';
            }
            $marc .= "\n";
        }
        // Return result
        return $marc;
    }

}

?>