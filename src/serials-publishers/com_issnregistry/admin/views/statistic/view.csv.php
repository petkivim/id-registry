<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
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
class IssnregistryViewStatistic extends JViewLegacy {

    /**
     * Return statistics CSV file
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    function display($tpl = null) {
        $this->item = $this->get('Item');

        // Get parameters
        $jinput = JFactory::getApplication()->input;
        $type = $jinput->get('type', null, 'string');
        $begin = $jinput->get('begin', null, 'string');
        $end = $jinput->get('end', null, 'string');

        // Convert date strings to JDate objects
        $beginDate = new JDate($begin);
        $endDate = new JDate($end . ' 23:59:59');
        // Get statistic model
        $statisticModel = JModelLegacy::getInstance('statistic', 'IssnregistryModel');
        // Get statistics
        $csv = $statisticModel->getStats($type, $beginDate, $endDate);

        // Set document properties
        $document = JFactory::getDocument();
        $document->setMimeEncoding('text/csv; charset="UTF-8"');
        JResponse::setHeader('Content-disposition', 'attachment; filename="statistics.csv"', true);

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
