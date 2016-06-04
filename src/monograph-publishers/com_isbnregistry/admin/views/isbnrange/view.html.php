<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * ISBN Range View
 *
 * @since  1.0.0
 */
class IsbnregistryViewIsbnrange extends JViewLegacy {

    protected $form = null;

	protected $item = null;

	protected $state = null;

    /**
     * Display the Publisher view
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
            $title = JText::_('COM_ISBNREGISTRY_ISBN_RANGE_NEW');
        } else {
            $title = JText::_('COM_ISBNREGISTRY_ISBN_RANGE_EDIT');
        }

        JToolBarHelper::title($title, 'isbnrange');
        JToolbarHelper::apply('isbnrange.apply');
        JToolBarHelper::cancel(
                'isbnrange.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE'
        );
    }

}
