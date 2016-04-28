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
     * Display the Publications view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    function display($tpl = null) {
        // Get data from the model
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }
        // Get filter status
        $filterStatus = $this->state->get('filter.status');
        // Add publishers helper file
        require_once JPATH_COMPONENT . '/helpers/publishers.php';
        // Add sidebar
        if ($filterStatus == 1) {
            PublishersHelper::addSubmenu('publications_received');
        } else if ($filterStatus == 2) {
            PublishersHelper::addSubmenu('publications_on_process');
        } else if ($filterStatus == 4) {
            PublishersHelper::addSubmenu('publications_no_identifier_granted');
        } else {
            PublishersHelper::addSubmenu('publications_processed');
        }
        // Set the toolbar
        $this->addToolBar();
        // Render the sidebar
        $this->sidebar = JHtmlSidebar::render();
        // Display the template
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function addToolBar() {
        // Get filter status
        $filterStatus = $this->state->get('filter.status');
        
        if ($filterStatus == 1) {
            JToolBarHelper::title(JText::_('COM_ISBNREGISTRY_PUBLICATIONS_RECEIVED'));
            // Add new button if received view is showed
            JToolBarHelper::addNew('publication.add');
        } else if ($filterStatus == 2) {
            JToolBarHelper::title(JText::_('COM_ISBNREGISTRY_PUBLICATIONS_ON_PROCESS'));
        } else if ($filterStatus == 3) {
            JToolBarHelper::title(JText::_('COM_ISBNREGISTRY_PUBLICATIONS_PROCESSED'));
        } else if ($filterStatus == 4) {
            JToolBarHelper::title(JText::_('COM_ISBNREGISTRY_PUBLICATIONS_NO_IDENTIFIER_GRANTED'));
        } else {
            JToolBarHelper::title(JText::_('COM_ISBNREGISTRY_PUBLICATIONS'));
        }
        JToolBarHelper::editList('publication.edit');

        // Has user rights to access preferences?
        if (JFactory::getUser()->authorise('core.admin', 'com_isbnregistry')) {
            JToolBarHelper::preferences('com_isbnregistry');
        }
    }

}
