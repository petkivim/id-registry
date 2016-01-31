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
 * Group Message View
 *
 * @since  1.0.0
 */
class IsbnregistryViewGroupmessage extends JViewLegacy {

    protected $form = null;
    protected $item = null;
    protected $state = null;

    /**
     * Display the Group message view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    public function display($tpl = null) {
        // Get the Data
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }


        // Set the toolbar
        $this->addToolBar();
        // Add jQuery
        JHtml::_('jquery.framework');
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
        $input = JFactory::getApplication()->input;

        // Hide Joomla Administrator Main menu
        $input->set('hidemainmenu', true);

        $isNew = ($this->item->id == 0);

        if ($isNew) {
            $title = JText::_('COM_ISBNREGISTRY_GROUP_MESSAGE_NEW');
        } else {
            $title = JText::_('COM_ISBNREGISTRY_GROUP_MESSAGE_EDIT');
        }

        JToolBarHelper::title($title, 'groupmessage');
		if ($isNew) {
			JToolbarHelper::save('groupmessage.save', 'COM_ISBNREGISTRY_MESSAGE_BUTTON_SEND');
		}
        JToolBarHelper::cancel(
                'groupmessage.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE'
        );
    }

}
