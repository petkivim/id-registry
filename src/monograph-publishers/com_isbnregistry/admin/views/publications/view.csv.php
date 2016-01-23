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
 * Publications View
 *
 * @since  1.0.0
 */
class IsbnregistryViewPublications extends JViewLegacy {

    /**
     * Display the Publications CSV
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    function display($tpl = null) {
        // Get data from the model
        $this->items = $this->get('Items');

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
        $csv = PublicationHelper::toCSVArray($list);

        // Set document properties
        $document = JFactory::getDocument();
        $document->setMimeEncoding('text/csv; charset="UTF-8"');
        JResponse::setHeader('Content-disposition', 'attachment; filename="publications.csv"', true);

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
