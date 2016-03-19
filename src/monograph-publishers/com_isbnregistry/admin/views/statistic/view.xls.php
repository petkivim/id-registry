<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Statistic View
 *
 * @since  1.0.0
 */
class IsbnregistryViewStatistic extends JViewLegacy {

    /**
     * Return statistics XLS file
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    function display($tpl = null) {
        $this->item = $this->get('Item');

        // Get parameters
        $jinput = JFactory::getApplication()->input;
        $begin = $jinput->get('begin', null, 'string');
        $end = $jinput->get('end', null, 'string');

        // Get publisher model
        $publisherModel = JModelLegacy::getInstance('publisher', 'IsbnregistryModel');
        // Get list of publishers
        $list = $publisherModel->getPublishersAndIsbnIdentifiers();
        // Add publications helper file
        require_once JPATH_COMPONENT . '/helpers/publishers.php';
        // Convert list to CSV array
        $data = PublishersHelper::toCSVArray($list);

        // Set document properties
        $document = JFactory::getDocument();
        $document->setMimeEncoding('application/vnd.ms-excel; charset="UTF-8"');
        JResponse::setHeader('Content-disposition', 'attachment; filename="statistics.' . $begin . '-' . $end . '.xml"', true);

        // Get Excel helper
        require_once JPATH_COMPONENT . '/helpers/php-export-data.class.php';
        // Create new Excled worksheet
        $excel = new ExportDataExcel('browser');
        $excel->filename = "statistics." . $begin . "-" . $end . ".xml";
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
