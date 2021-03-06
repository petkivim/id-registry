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
 * Messages View
 *
 * @since  1.0.0
 */
class IssnregistryViewMessages extends JViewLegacy {

    /**
     * Display the Messages view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    function display($tpl = null) {
        // Get data from the model
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }

        // Add menu helper file
        require_once JPATH_COMPONENT . '/helpers/menu.php';
        // Add sidebar
        MenuHelper::addSubmenu('messages');

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
        JToolBarHelper::title(JText::_('COM_ISSNREGISTRY_MESSAGES'));
        //JToolBarHelper::addNew('message.add');
        JToolBarHelper::deleteList('', 'messages.delete');

        // Has user rights to access preferences?
        if (JFactory::getUser()->authorise('core.admin', 'com_issnregistry')) {
            JToolBarHelper::preferences('com_issnregistry');
        }
    }

}
