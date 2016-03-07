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
     * Display the Publishers view
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

        // Get filter no identifier
        $filterNoIdentifier = $this->state->get('filter.no_identifier');
        // Add publishers helper file
        require_once JPATH_COMPONENT . '/helpers/publishers.php';
        // Add sidebar
        if ($filterNoIdentifier == 1) {
            PublishersHelper::addSubmenu('publishers_applications');
        } else if ($filterNoIdentifier == 5) {
            PublishersHelper::addSubmenu('publishers_registry');
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
        JToolBarHelper::title(JText::_('COM_ISBNREGISTRY_PUBLISHERS'));
        JToolBarHelper::addNew('publisher.add');
        JToolBarHelper::editList('publisher.edit');
        JToolBarHelper::deleteList('', 'publishers.delete');

        // Get component parameters
        $params = JComponentHelper::getParams('com_isbnregistry');
        // Get PIID file format
        $format = $params->get('piid_format', 'XLS');

        JToolBarHelper::custom('publishers.get' . $format, 'pie', 'pie', JText::_('COM_ISBNREGISTRY_PUBLISHERS_BUTTON_GET_CSV'), false, false);

        JToolBarHelper::preferences('com_isbnregistry');
    }

}
