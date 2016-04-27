<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Statistic Model
 *
 * @since  1.0.0
 */
class IssnregistryModelStatistic extends JModelAdmin {

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $type    The table name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  JTable  A JTable object
     *
     * @since   1.6
     */
    public function getTable($type = 'Statistic', $prefix = 'IssnregistryTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed    A JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm(
                'com_issnregistry.statistic', 'statistic', array(
            'control' => 'jform', 'load_data' => $loadData
                )
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since   1.6
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState(
                'com_issnregistry.edit.statistic.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getStats($type, $begin, $end) {
        $results = array();
        if (strcmp($type, 'ISSN') == 0) {
            $results = $this->getIssnStats($begin, $end);
        } else if (strcmp($type, 'PUBLISHERS') == 0) {
            $results = $this->getPublishersStats($begin, $end);
        } else if (strcmp($type, 'PUBLICATIONS') == 0) {
            $results = $this->getPublicationsStats($begin, $end);
        } else if (strcmp($type, 'FORMS') == 0) {
            $results = $this->getFormsStats($begin, $end);
        }

        return $results;
    }

    private function getPublishersStats($begin, $end) {
        // Get publisher model
        $publisherModel = JModelLegacy::getInstance('publisher', 'IssnregistryModel');

        // Get created publishers
        $created = $publisherModel->getCreatedPublisherCountByDates($begin, $end);
        // Get modified publishers
        $modified = $publisherModel->getModifiedPublisherCountByDates($begin, $end);

        // Create array for results
        $results = array();
        // Add array title
        array_push($results, array(JText::_('COM_ISSNREGISTRY_STATISTICS_PUBLISHERS_TABLE1')));
        // Add empty row
        array_push($results, array());
        // Create headers
        $row1 = array(
            JText::_('COM_ISSNREGISTRY_STATISTICS_PUBLISHERS_TABLE1_R1_C1'),
            JText::_('COM_ISSNREGISTRY_STATISTICS_PUBLISHERS_TABLE1_R1_C2')
        );
        array_push($results, $row1);
        // Add results
        foreach ($created as $item) {
            $row = array(
                $item->year . '-' . $item->month,
                $item->count
            );
            array_push($results, $row);
        }
        // Add empty row
        array_push($results, array());
        // Add array title
        array_push($results, array(JText::_('COM_ISSNREGISTRY_STATISTICS_PUBLISHERS_TABLE2')));
        // Add empty row
        array_push($results, array());

        // Create headers
        $row1 = array(
            JText::_('COM_ISSNREGISTRY_STATISTICS_PUBLISHERS_TABLE2_R1_C1'),
            JText::_('COM_ISSNREGISTRY_STATISTICS_PUBLISHERS_TABLE2_R1_C2')
        );
        array_push($results, $row1);
        // Add results
        foreach ($modified as $item) {
            $row = array(
                $item->year . '-' . $item->month,
                $item->count
            );
            array_push($results, $row);
        }
        return $results;
    }

    private function getPublicationsStats($begin, $end) {
        // Get begin and end year and month
        $beginYear = $begin->format('Y');
        // No leading zeros, if leading zeros are needed, use 'm' 
        $beginMonth = $begin->format('n');
        $endYear = $end->format('Y');
        // No leading zeros, if leading zeros are needed, use 'm' 
        $endMonth = $end->format('n');

        // Get array with year(s) and months
        $yearMonthArray = $this->getYearMonthArray($beginYear, $beginMonth, $endYear, $endMonth);
        // Array for results
        $results = array();
        // Get header
        array_push($results, $this->getHeader($yearMonthArray));
        // Add array title
        array_push($results, array(JText::_('COM_ISSNREGISTRY_STATISTICS_PUBLICATIONS_TABLE1')));

        // Get publication model
        $publicationModel = JModelLegacy::getInstance('publication', 'IssnregistryModel');
        // Get list of publications
        $noPrepublicationRecord = $publicationModel->getPublicationsCountByStatusAndDates($begin, $end, 'NO_PREPUBLICATION_RECORD');
        // Row
        $row = array('', JText::_('COM_ISSNREGISTRY_STATISTICS_MODIFIED_PUBLISHERS_R1_C2'));
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $noPrepublicationRecord);
        // Add data to results
        array_push($results, $row);

        // Get list of publications
        $issnFrozen = $publicationModel->getPublicationsCountByStatusAndDates($begin, $end, 'ISSN_FROZEN');
        // Row
        $row = array('', JText::_('COM_ISSNREGISTRY_STATISTICS_MODIFIED_PUBLISHERS_R2_C2'));
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $issnFrozen);
        // Add data to results
        array_push($results, $row);

        // Get list of publications
        $waitingForControlCopy = $publicationModel->getPublicationsCountByStatusAndDates($begin, $end, 'WAITING_FOR_CONTROL_COPY');
        // Row
        $row = array('', JText::_('COM_ISSNREGISTRY_STATISTICS_MODIFIED_PUBLISHERS_R3_C2'));
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $waitingForControlCopy);
        // Add data to results
        array_push($results, $row);

        // Get list of publications
        $completed = $publicationModel->getPublicationsCountByStatusAndDates($begin, $end, 'COMPLETED');
        // Row
        $row = array('', JText::_('COM_ISSNREGISTRY_STATISTICS_MODIFIED_PUBLISHERS_R4_C2'));
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $completed);
        // Add data to results
        array_push($results, $row);

        return $results;
    }

    private function getFormsStats($begin, $end) {
        // Get form model
        $formModel = JModelLegacy::getInstance('form', 'IssnregistryModel');

        // Get new forms count
        $created = $formModel->getCreatedFormCountByDates($begin, $end);

        // Create array for results
        $results = array();
        // Add array title
        array_push($results, array(JText::_('COM_ISSNREGISTRY_STATISTICS_FORMS_TABLE1')));
        // Add empty row
        array_push($results, array());
        // Create headers
        $row1 = array(
            JText::_('COM_ISSNREGISTRY_STATISTICS_FORMS_TABLE1_R1_C1'),
            JText::_('COM_ISSNREGISTRY_STATISTICS_FORMS_TABLE1_R1_C2')
        );
        array_push($results, $row1);
        // Add results
        foreach ($created as $item) {
            $row = array(
                $item->year . '-' . $item->month,
                $item->count
            );
            array_push($results, $row);
        }

        return $results;
    }

    private function getIssnStats($begin, $end) {
        // Get ISSN range model
        $issnRangeModel = JModelLegacy::getInstance('issnrange', 'IssnregistryModel');
        // Get ranges
        $ranges = $issnRangeModel->getRanges();
        // Create array for results
        $results = array();
        // Add array title
        array_push($results, array(JText::_('COM_ISSNREGISTRY_STATISTICS_ISSN_TABLE1')));
        // Add empty row
        array_push($results, array());
        // Create headers
        $row1 = array(
            JText::_('COM_ISSNREGISTRY_STATISTICS_ISSN_TABLE1_R1_C1'),
            JText::_('COM_ISSNREGISTRY_STATISTICS_ISSN_TABLE1_R1_C2'),
            JText::_('COM_ISSNREGISTRY_STATISTICS_ISSN_TABLE1_R1_C3'),
            JText::_('COM_ISSNREGISTRY_STATISTICS_ISSN_TABLE1_R1_C4')
        );
        array_push($results, $row1);
        // Add results
        foreach ($ranges as $range) {
            $free = $range->free + $range->canceled;
            $taken = $range->taken - $range->canceled;
            $row = array(
                $range->block,
                $taken,
                $free,
                ($free + $taken)
            );
            array_push($results, $row);
        }
        // Add empty row
        array_push($results, array());
        // Add array title
        array_push($results, array(JText::_('COM_ISSNREGISTRY_STATISTICS_ISSN_TABLE2')));
        // Add empty row
        array_push($results, array());
        // Get created ISSN count
        $createdIssnCount = $issnRangeModel->getCreatedIssnCountByDates($begin, $end);
        // Create headers
        $row1 = array(
            JText::_('COM_ISSNREGISTRY_STATISTICS_ISSN_TABLE2_R1_C1'),
            JText::_('COM_ISSNREGISTRY_STATISTICS_ISSN_TABLE2_R1_C2'),
            JText::_('COM_ISSNREGISTRY_STATISTICS_ISSN_TABLE2_R1_C3'),
        );
        array_push($results, $row1);
        // Add results
        foreach ($createdIssnCount as $item) {
            $row = array(
                $item->year . '-' . $item->month,
                $item->block,
                $item->count
            );
            array_push($results, $row);
        }
        return $results;
    }

    private function getHeader($yearMonthArray) {
        // Create array and leave two empty columns
        $header = array('', '');
        // Go through the given array
        foreach ($yearMonthArray as $year => $months) {
            foreach ($months as $month) {
                array_push($header, $year . '-' . $month);
            }
        }
        return $header;
    }

    private function getYearMonthArray($beginYear, $beginMonth, $endYear, $endMonth) {
        // Create array and leave two empty columns
        $results = array();
        while ($beginYear <= $endYear) {
            // Add year to results if it doesn't exist yet
            if (!array_key_exists($beginYear, $results)) {
                $results[$beginYear] = array();
            }
            // Add month to results
            array_push($results[$beginYear], $beginMonth);

            // Update counters
            if ($beginMonth == 12) {
                $beginMonth = 1;
                $beginYear++;
            } else {
                $beginMonth++;
            }
            // Have we reached the end?
            if ($beginYear == $endYear && $beginMonth > $endMonth) {
                break;
            }
        }
        return $results;
    }

    private function toAssocArray($data) {
        $results = array();
        foreach ($data as $row) {
            if (!array_key_exists($row->year, $results)) {
                $results[$row->year] = array();
            }
            $results[$row->year][$row->month] = $row->count;
        }
        return $results;
    }

    private function addResultsRow(&$resultsRow, $yearMonthArray, $data) {
        // Get associative array with data
        $assocArray = $this->toAssocArray($data);
        // Go through the given year & month array
        foreach ($yearMonthArray as $year => $months) {
            if (!array_key_exists($year, $assocArray)) {
                $assocArray[$year] = array();
            }
            foreach ($months as $month) {
                if (!array_key_exists($month, $assocArray[$year])) {
                    array_push($resultsRow, 0);
                } else {
                    array_push($resultsRow, $assocArray[$year][$month]);
                }
            }
        }
    }

}
