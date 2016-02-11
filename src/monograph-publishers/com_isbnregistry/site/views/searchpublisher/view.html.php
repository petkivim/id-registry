<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Search Publisher View
 *
 * @since  1.0.0
 */
class IsbnRegistryViewSearchPublisher extends JViewLegacy {

    protected $items;
    protected $pagination;

    /**
     * Display the Search Publisher view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    function display($tpl = null) {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');

            return false;
        }

        if ($this->getLayout() == 'info') {
            // Include component models
            JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_isbnregistry/models');
            // Include component tables
            JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_isbnregistry/tables');
            // Get publisher id URL parameter
            $publisherId = JFactory::getApplication()->input->getInt('publisherId', 0);
            // Check publisher id
            if ($publisherId != 0) {
                // Load publisher isbn range model
                $publisherIsbnRangeModel = JModelLegacy::getInstance('publisherisbnrange', 'IsbnregistryModel');
                // Get ISBN identifiers
                $isbns = $publisherIsbnRangeModel->getPublisherIdentifiers($publisherId);
                // Load publisher ismn range model
                $publisherIsmnRangeModel = JModelLegacy::getInstance('publisherismnrange', 'IsbnregistryModel');
                // Get ISMN identifiers
                $ismns = $publisherIsmnRangeModel->getPublisherIdentifiers($publisherId);
                // Check that publisher has ISBNs or ISMNs, if not, publisher
                // data won't be returned
                if (!empty($isbns) || !empty($ismns)) {
                    // Load publisher model
                    $publisherModel = JModelLegacy::getInstance('publisher', 'IsbnregistryModel');
                    // Load publisher
                    $publisher = $publisherModel->getPublisherById($publisherId);
                    // Pass $publisher to the layout
                    $this->assignRef('publisher', $publisher);
                    // Pass $isbns to the layout
                    $this->assignRef('isbns', $isbns);
                    // Pass $ismns to the layout
                    $this->assignRef('ismns', $ismns);
                }
            }
        }

        // Display the view
        parent::display($tpl);
    }

}
