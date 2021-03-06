<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivim�ki
 * @copyright	Copyright (C) 2015 Petteri Kivim�ki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Publishers View
 *
 * @since  1.0.0
 */
class IsbnregistryViewIsmnranges extends JViewLegacy {

    /**
     * Display the ISBN Ranges view
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
		PublishersHelper::addSubmenu('ismnranges');
		
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
        JToolBarHelper::title(JText::_('COM_ISBNREGISTRY_ISMN_RANGES'));
        JToolBarHelper::addNew('ismnrange.add');
        JToolBarHelper::editList('ismnrange.edit');
        JToolBarHelper::deleteList('', 'ismnranges.delete');
    }

}
