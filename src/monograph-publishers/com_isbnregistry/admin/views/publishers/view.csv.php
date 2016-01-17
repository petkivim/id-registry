<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Publishers View
 *
 * @since  1.0.0
 */
class IsbnregistryViewPublishers extends JViewLegacy {

    /**
     * Return publishers CSV file
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    function display($tpl = null) {
        $this->item = $this->get('Item');

        // Get publisher model
        $publisherModel = JModelLegacy::getInstance('publisher', 'IsbnregistryModel');
        // Get list of publishers
        $list = $publisherModel->getPublishersAndIsbnIdentifiers();
        // Add publications helper file
        require_once JPATH_COMPONENT . '/helpers/publishers.php';
        // Convert list to CSV array
        $csv = PublishersHelper::toCSVArray($list);

        // Set document properties
        $document = JFactory::getDocument();
        $document->setMimeEncoding('text/csv; charset="UTF-8"');
        JResponse::setHeader('Content-disposition', 'attachment; filename="publishers.csv"', true);

        // Write to output
        $out = fopen('php://output', 'w');
        // Loop through CSV data and write each row to output
        foreach ($csv as $row) {
            // Columns are tab separated
            fputcsv($out, $row, "\t");
        }
        // Close output
        fclose($out);
    }

}
