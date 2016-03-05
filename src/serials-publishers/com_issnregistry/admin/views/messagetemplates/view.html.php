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
 * Message templates View
 *
 * @since  1.0.0
 */
class IssnregistryViewMessagetemplates extends JViewLegacy {

    /**
     * Display the Message templates view
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
        MenuHelper::addSubmenu('messagetemplates');

        // Load message type model
        $model = JModelLegacy::getInstance('messagetype', 'IssnregistryModel');
        // Load message types
        $types = $model->getMessageTypesHash();
        // Pass $types to the layout
        $this->assignRef('types', $types);

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
        JToolBarHelper::title(JText::_('COM_ISSNREGISTRY_MESSAGE_TEMPLATES'));
        JToolBarHelper::addNew('messagetemplate.add');
        JToolBarHelper::editList('messagetemplate.edit');
        JToolBarHelper::deleteList('', 'messagetemplates.delete');
        JToolBarHelper::preferences('com_issnregistry');
    }

}
