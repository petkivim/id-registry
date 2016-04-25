<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Group Messages View
 *
 * @since  1.0.0
 */
class IsbnregistryViewGroupmessages extends JViewLegacy {

    /**
     * Display the Message types view
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

        // Add publishers helper file
        require_once JPATH_COMPONENT . '/helpers/publishers.php';
        // Add sidebar
        PublishersHelper::addSubmenu('groupmessages');

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
        JToolBarHelper::title(JText::_('COM_ISBNREGISTRY_GROUP_MESSAGES'));
        JToolBarHelper::addNew('groupmessage.add');
        JToolBarHelper::editList('groupmessage.edit', 'COM_ISBNREGISTRY_GROUP_MESSAGE_EDIT');
        JToolBarHelper::deleteList('', 'groupmessages.delete');

        // Get current user
        $user = JFactory::getUser();
        // Is it a super user?
        $isroot = $user->authorise('core.admin');
        // Only super users can access preferences
        if ($isroot) {
            JToolBarHelper::preferences('com_isbnregistry');
        }
    }

}
