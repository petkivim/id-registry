<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Form View
 *
 * @since  1.0.0
 */
class IssnregistryViewForm extends JViewLegacy {

    protected $form = null;
    protected $item = null;
    protected $state = null;

    /**
     * Display the Form view
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
            $title = JText::_('COM_ISSNREGISTRY_FORM_NEW');
        } else {
            $title = JText::_('COM_ISSNREGISTRY_FORM_EDIT');
            $title .= ' : ' . $this->item->publisher;
        }

        JToolBarHelper::title($title, 'form');
        JToolbarHelper::apply('form.apply');
        JToolBarHelper::save('form.save');
        JToolbarHelper::save2new('form.save2new');
        if (!$isNew) {
            // Add custom button for sending a message
            $toolbar = JToolBar::getInstance('toolbar');
            $layout = new JLayoutFile('joomla.toolbar.popup');

            // Load message model
            $messageModel = JModelLegacy::getInstance('message', 'IssnregistryModel');
            // Get number of messages related to this form
            $msgCount = $messageModel->getMessageCountByFormId($this->item->id);
            // Add send message button if all the publications have ISSN and no
            // messages have been sent yet
            if ($this->item->publication_count == $this->item->publication_count_issn && $msgCount == 0) {
                // Render the popup button
                $dhtml = $layout->render(array('name' => 'generate-message', 'text' => JText::_('COM_ISSNREGISTRY_FORM_BUTTON_SEND_MESSAGE'), 'class' => 'icon-envelope'));
                $toolbar->appendButton('Custom', $dhtml);
            }

            // Render the popup button
            $dhtml = $layout->render(array('name' => 'print', 'text' => JText::_('COM_ISSNREGISTRY_FORM_BUTTON_PRINT'), 'class' => 'icon-print'));
            $toolbar->appendButton('Custom', $dhtml);
        }
        if (!$isNew && !$this->item->publisher_created && $this->item->publisher_id == 0) {
            JToolBarHelper::custom('form.createPublisher', 'new', 'new', JText::_('COM_ISSNREGISTRY_FORM_BUTTON_CREATE_PUBLISHER'), false, false);
        }
        JToolBarHelper::cancel(
                'form.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE'
        );
    }

}
