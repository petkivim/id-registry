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
     * Display the Publications XLS view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    function display($tpl = null) {
        // Get data from the model
        $this->items = $this->get('Items');
        // Get publication model
        $publicationModel = JModelLegacy::getInstance('publication', 'IsbnregistryModel');
        // Get list of publications
        $list = $publicationModel->getPublicationsWithIsbnIdentifiers();
        // Add publications helper file
        require_once JPATH_COMPONENT . '/helpers/publication.php';
        // Convert list to CSV array
        $data = PublicationHelper::toCSVArray($list);

        // Set document properties
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/vnd.ms-excel; charset="UTF-8"');
        JResponse::setHeader('Content-disposition', 'attachment; filename="publications.xml"', true);

        // Get Excel helper
        require_once JPATH_COMPONENT . '/helpers/php-export-data.class.php';
        // Create new Excled worksheet
        $excel = new ExportDataExcel('browser');
        $excel->filename = "publications.xml";
        $excel->initialize();
        // Loop through data array
        foreach ($data as $row) {
            // Add rows
            $excel->addRow($row);
        }
        // Finalize
        $excel->finalize();
    }

}
