<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivim�ki
 * @copyright	Copyright (C) 2016 Petteri Kivim�ki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Forms View
 *
 * @since  1.0.0
 */
class IssnregistryViewForms extends JViewLegacy {

    /**
     * Display the Forms view
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
        // Add menu helper file
        require_once JPATH_COMPONENT . '/helpers/menu.php';
        // Add sidebar
        if ($filterStatus == 2) {
            MenuHelper::addSubmenu('forms_not_notified');
        } else if ($filterStatus == 3) {
            MenuHelper::addSubmenu('forms_completed');
        } else if ($filterStatus == 4) {
            MenuHelper::addSubmenu('forms_rejected');
        } else {
            MenuHelper::addSubmenu('forms_not_handled');
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
        JToolBarHelper::title(JText::_('COM_ISSNREGISTRY_FORMS'));
        JToolBarHelper::addNew('form.add');
        JToolBarHelper::editList('form.edit');
        JToolBarHelper::deleteList('', 'forms.delete');
    }

}
