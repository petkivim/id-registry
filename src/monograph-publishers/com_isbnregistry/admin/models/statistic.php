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
 * Statistic Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelStatistic extends JModelAdmin {

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
    public function getTable($type = 'Statistic', $prefix = 'IsbnregistryTable', $config = array()) {
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
                'com_isbnregistry.statistic', 'statistic', array(
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
                'com_isbnregistry.edit.statistic.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getStats($type, $begin, $end) {
        $results = array();
        if (strcmp($type, 'MONTHLY') == 0) {
            $results = $this->getMonthlyStats($begin, $end);
        } else if (strcmp($type, 'PROGRESS_ISBN') == 0) {
            $results = $this->getProgressIsbn();
        } else if (strcmp($type, 'PROGRESS_ISMN') == 0) {
            $results = $this->getProgressIsmn();
        } else if (strcmp($type, 'PUBLISHERS') == 0) {
            $results = $this->getPublishersStats();
        } else if (strcmp($type, 'PUBLICATIONS') == 0) {
            $results = $this->getPublicationsStats();
        }
        return $results;
    }

    private function getPublishersStats() {
        // Get publisher model
        $publisherModel = JModelLegacy::getInstance('publisher', 'IsbnregistryModel');
        // Get list of publishers
        $list = $publisherModel->getPublishersAndIsbnIdentifiers();
        // Add publications helper file
        require_once JPATH_COMPONENT . '/helpers/publishers.php';
        // Convert list to CSV array
        return PublishersHelper::toCSVArray($list);
    }

    private function getPublicationsStats() {
        // Get component parameters
        $params = JComponentHelper::getParams('com_isbnregistry');
        // Get the id of the publisher that represents author publishers
        $authorPublisherId = $params->get('author_publisher_id_isbn', 0);
        // Get publication model
        $publicationModel = JModelLegacy::getInstance('publication', 'IsbnregistryModel');
        // Get list of publications
        $list = $publicationModel->getPublicationsWithIsbnIdentifiers($authorPublisherId);
        // Add publications helper file
        require_once JPATH_COMPONENT . '/helpers/publication.php';
        // Convert list to CSV array
        return PublicationHelper::toCSVArray($list);
    }

    private function getProgressIsbn() {
        // Get ISBN range model
        $isbnRangeModel = JModelLegacy::getInstance('isbnrange', 'IsbnregistryModel');
        // Get ranges
        $ranges = $isbnRangeModel->getRanges();
        // Create array for results
        $results = array();
        // Create headers
        $row1 = array(
            JText::_('COM_ISBNREGISTRY_STATISTICS_PROGRESS_ISBN_PREFIX'),
            JText::_('COM_ISBNREGISTRY_STATISTICS_PROGRESS_ISBN_LANG_GROUP'),
            JText::_('COM_ISBNREGISTRY_STATISTICS_PROGRESS_ISBN_BEGIN'),
            JText::_('COM_ISBNREGISTRY_STATISTICS_PROGRESS_ISBN_END'),
            JText::_('COM_ISBNREGISTRY_STATISTICS_PROGRESS_ISBN_FREE'),
            JText::_('COM_ISBNREGISTRY_STATISTICS_PROGRESS_ISBN_TAKEN')
        );
        array_push($results, $row1);
        // Add results
        foreach ($ranges as $range) {
            $row = array(
                $range->prefix > 0 ? $range->prefix : '',
                $range->lang_group,
                $range->range_begin . ' ',
                $range->range_end . ' ',
                ($range->free + $range->canceled),
                ($range->taken - $range->canceled)
            );
            array_push($results, $row);
        }
        return $results;
    }

    private function getProgressIsmn() {
        // Get ISBN range model
        $ismnRangeModel = JModelLegacy::getInstance('ismnrange', 'IsbnregistryModel');
        // Get ranges
        $ranges = $ismnRangeModel->getRanges();
        // Create array for results
        $results = array();
        // Create headers
        $row1 = array(
            JText::_('COM_ISBNREGISTRY_STATISTICS_PROGRESS_ISBN_PREFIX'),
            JText::_('COM_ISBNREGISTRY_STATISTICS_PROGRESS_ISBN_BEGIN'),
            JText::_('COM_ISBNREGISTRY_STATISTICS_PROGRESS_ISBN_END'),
            JText::_('COM_ISBNREGISTRY_STATISTICS_PROGRESS_ISBN_FREE'),
            JText::_('COM_ISBNREGISTRY_STATISTICS_PROGRESS_ISBN_TAKEN')
        );
        array_push($results, $row1);
        // Add results
        foreach ($ranges as $range) {
            $row = array(
                $range->prefix,
                $range->range_begin . ' ',
                $range->range_end . ' ',
                ($range->free + $range->canceled),
                ($range->taken - $range->canceled)
            );
            array_push($results, $row);
        }
        return $results;
    }

    private function getMonthlyStats($begin, $end) {
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

        // Get component parameters
        $params = JComponentHelper::getParams('com_isbnregistry');
        // Get the id of the publisher that represents author publishers
        $authorPublisherId = $params->get('author_publisher_id_isbn', 0);
        // Get the id of the publisher that represents the state
        $statePublisherId = $params->get('state_publisher_id_isbn', 0);
        // Get the id of the publisher that represents university of Helsinki
        $universityPublisherId = $params->get('university_publisher_id_isbn', 0);
        // Create exlude publisher ids array
        $excludePublisherIds = array($authorPublisherId, $statePublisherId, $universityPublisherId);

        // Get sent messages count
        $messageModel = JModelLegacy::getInstance('message', 'IsbnregistryModel');
        $sentMessages = $messageModel->getMessageCountByDates($begin, $end);
        $this->addSentMessages($results, $yearMonthArray, $sentMessages);
        array_push($results, array());

        // Get new ISBN publisher count
        $publisherModel = JModelLegacy::getInstance('publisher', 'IsbnregistryModel');
        $newIsbnPublishers = $publisherModel->getCreatedPublisherCountByDates($begin, $end, false);
        $this->addNewPublishers($results, $yearMonthArray, $newIsbnPublishers, false);

        // Get new ISMN publisher count
        $newIsmnPublishers = $publisherModel->getCreatedPublisherCountByDates($begin, $end, true);
        $this->addNewPublishers($results, $yearMonthArray, $newIsmnPublishers, true);

        // Get self registered publisher count
        $selfRegisteredPublishers = $publisherModel->getSelfRegisteredPublisherCountByDates($begin, $end);
        $this->addSelfRegisteredPublishers($results, $yearMonthArray, $selfRegisteredPublishers);
        array_push($results, array());

        // Get created ISBN count
        $publishserIsbnRangeModel = JModelLegacy::getInstance('publisherisbnrange', 'IsbnregistryModel');
        $createdIsbnsAuthorPublisher = $publishserIsbnRangeModel->getCreatedIdentifierCountByDates($begin, $end, $authorPublisherId);
        $createdIsbnsStatePublisher = $publishserIsbnRangeModel->getCreatedIdentifierCountByDates($begin, $end, $statePublisherId);
        $createdIsbnsUniversityPublisher = $publishserIsbnRangeModel->getCreatedIdentifierCountByDates($begin, $end, $universityPublisherId);
        $createdIsbnsCategoryOnePublishers = $publishserIsbnRangeModel->getCreatedIdentifierCountByDates($begin, $end, 0, 1, $excludePublisherIds);
        $this->addCreatedISBNUniversityPublisher($results, $yearMonthArray, $createdIsbnsUniversityPublisher);
        $this->addCreatedISBNStatePublisher($results, $yearMonthArray, $createdIsbnsStatePublisher);
        $this->addCreatedISBNCategoryOnePublisher($results, $yearMonthArray, $createdIsbnsCategoryOnePublishers);
        $this->addCreatedISBNAuthorPublisher($results, $yearMonthArray, $createdIsbnsAuthorPublisher);
        array_push($results, array());

        // Get created ISMN count
        $publishserIsmnRangeModel = JModelLegacy::getInstance('publisherismnrange', 'IsbnregistryModel');
        $createdIsmnsAuthorPublisher = $publishserIsmnRangeModel->getCreatedIdentifierCountByDates($begin, $end, $authorPublisherId);
        $createdIsmnsCategoryOnePublishers = $publishserIsmnRangeModel->getCreatedIdentifierCountByDates($begin, $end, 0, 1, $excludePublisherIds);
        $this->addCreatedISMNAuthorPublisher($results, $yearMonthArray, $createdIsmnsAuthorPublisher);
        $this->addCreatedISMNCategoryOnePublisher($results, $yearMonthArray, $createdIsmnsCategoryOnePublishers);
        array_push($results, array());

        // Get modified publisher count       
        $modifiedPublishers = $publisherModel->getModifiedPublisherCountByDates($begin, $end);
        $this->addModifiedPublishers($results, $yearMonthArray, $modifiedPublishers);

        // Return results
        return $results;
    }

    private function getHeader($yearMonthArray) {
        // Create array and leave two empty columns
        $header = array('', '');
        // Go through the given array
        foreach ($yearMonthArray as $year => $months) {
            foreach ($months as $month) {
                array_push($header, $month . ' / ' . $year);
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

    private function addSentMessages(&$results, $yearMonthArray, $sentMessages) {
        // First row
        $row1 = array(JText::_('COM_ISBNREGISTRY_STATISTICS_SENT_MESSAGES_R1_C1'));
        array_push($results, $row1);
        // Second row
        $row2 = array('', JText::_('COM_ISBNREGISTRY_STATISTICS_SENT_MESSAGES_R2_C2'));
        // Add data
        $this->addResultsRow($row2, $yearMonthArray, $sentMessages);
        // Add data to results
        array_push($results, $row2);
    }

    private function addNewPublishers(&$results, $yearMonthArray, $newPublishers, $ismn = false) {
        // Row
        $row = array(JText::_('COM_ISBNREGISTRY_STATISTICS_NEW_' . ($ismn ? 'ISMN' : 'ISBN') . '_PUBLISHERS_R1_C1'), '');
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $newPublishers);
        // Add data to results
        array_push($results, $row);
    }

    private function addSelfRegisteredPublishers(&$results, $yearMonthArray, $selfRegisteredPublishers) {
        // Row
        $row = array(JText::_('COM_ISBNREGISTRY_STATISTICS_SELF_REGISTERED_PUBLISHERS'), '');
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $selfRegisteredPublishers);
        // Add data to results
        array_push($results, $row);
    }

    private function addCreatedISBNUniversityPublisher(&$results, $yearMonthArray, $createdIdentifiers) {
        // Title ow
        $titleRow = array(JText::_('COM_ISBNREGISTRY_STATISTICS_CREATED_ISBN_IDENTIFIERS_R1_C1'), '');
        // Add title row
        array_push($results, $titleRow);
        // Row
        $row = array('', JText::_('COM_ISBNREGISTRY_STATISTICS_CREATED_ISBN_UNIVERSITY_PUBLISHER_R2_C2'));
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $createdIdentifiers);
        // Add data to results
        array_push($results, $row);
    }

    private function addCreatedISBNStatePublisher(&$results, $yearMonthArray, $createdIdentifiers) {
        // Row
        $row = array('', JText::_('COM_ISBNREGISTRY_STATISTICS_CREATED_ISBN_STATE_PUBLISHER_R3_C2'));
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $createdIdentifiers);
        // Add data to results
        array_push($results, $row);
    }

    private function addCreatedISBNCategoryOnePublisher(&$results, $yearMonthArray, $createdIdentifiers) {
        // Row
        $row = array('', JText::_('COM_ISBNREGISTRY_STATISTICS_CREATED_ISBN_CATEGORY_ONE_PUBLISHER_R4_C2'));
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $createdIdentifiers);
        // Add data to results
        array_push($results, $row);
    }

    private function addCreatedISBNAuthorPublisher(&$results, $yearMonthArray, $createdIdentifiers) {
        // Row
        $row = array('', JText::_('COM_ISBNREGISTRY_STATISTICS_CREATED_ISBN_AUTHOR_PUBLISHER_R5_C2'));
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $createdIdentifiers);
        // Add data to results
        array_push($results, $row);
    }

    private function addCreatedISMNAuthorPublisher(&$results, $yearMonthArray, $createdIdentifiers) {
        // Title ow
        $titleRow = array(JText::_('COM_ISBNREGISTRY_STATISTICS_CREATED_ISMN_IDENTIFIERS_R1_C1'), '');
        // Add title row
        array_push($results, $titleRow);
        // Row
        $row = array('', JText::_('COM_ISBNREGISTRY_STATISTICS_CREATED_ISMN_AUTHOR_PUBLISHER_R2_C2'));
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $createdIdentifiers);
        // Add data to results
        array_push($results, $row);
    }

    private function addCreatedISMNCategoryOnePublisher(&$results, $yearMonthArray, $createdIdentifiers) {
        // Row
        $row = array('', JText::_('COM_ISBNREGISTRY_STATISTICS_CREATED_ISMN_CATEGORY_ONE_PUBLISHER_R3_C2'));
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $createdIdentifiers);
        // Add data to results
        array_push($results, $row);
    }

    private function addModifiedPublishers(&$results, $yearMonthArray, $modifiedPublishers) {
        // Row
        $row = array(JText::_('COM_ISBNREGISTRY_STATISTICS_MODIFIED_PUBLISHERS_R1_C1'), '');
        // Add data
        $this->addResultsRow($row, $yearMonthArray, $modifiedPublishers);
        // Add data to results
        array_push($results, $row);
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
