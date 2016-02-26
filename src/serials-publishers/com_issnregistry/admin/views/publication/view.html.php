<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivim�ki
 * @copyright	Copyright (C) 2016 Petteri Kivim�ki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Publication View
 *
 * @since  1.0.0
 */
class IssnregistryViewPublication extends JViewLegacy {

    protected $form = null;
    protected $item = null;
    protected $state = null;

    /**
     * Display the Publication view
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
            $title = JText::_('COM_ISSNREGISTRY_PUBLICATION_NEW');
        } else {
            $title = JText::_('COM_ISSNREGISTRY_PUBLICATION_EDIT');
            $title .= ' : ' . $this->item->title;
        }

        JToolBarHelper::title($title, 'publication');
        JToolbarHelper::apply('publication.apply');
        JToolBarHelper::save('publication.save');
        JToolbarHelper::save2new('publication.save2new');

        if (!$isNew) {
            // Add custom button for generating MARC record
            $toolbar = JToolBar::getInstance('toolbar');
            $layout = new JLayoutFile('joomla.toolbar.popup');

            // Render the popup button
            $dhtml = $layout->render(array('name' => 'generate-marc', 'text' => JText::_('COM_ISSNREGISTRY_PUBLICATION_BUTTON_PREVIEW_MARC'), 'class' => 'icon-book'));
            $toolbar->appendButton('Custom', $dhtml);

            JToolBarHelper::custom('publication.download', 'download', 'download', JText::_('COM_ISSNREGISTRY_PUBLICATION_BUTTON_DOWNLOAD_MARC'), false, false);

            // Render print button
            $dhtml = $layout->render(array('name' => 'print', 'text' => JText::_('COM_ISSNREGISTRY_PUBLICATION_BUTTON_PRINT'), 'class' => 'icon-print'));
            $toolbar->appendButton('Custom', $dhtml);
        }

        JToolBarHelper::cancel(
                'publication.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE'
        );
    }

}
