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
 * Publisher View
 *
 * @since  1.0.0
 */
class IsbnregistryViewPublisher extends JViewLegacy {

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

        if ($this->getLayout() == 'print') {
            // Get publisher identifiers for ISBNs
            $publisherIsbnRangeModel = JModelLegacy::getInstance('publisherisbnrange', 'IsbnregistryModel');
            $isbns = $publisherIsbnRangeModel->getPublisherIdentifiers($this->item->id);
            $this->assignRef('isbns', $isbns);
            // Get publisher identifiers for ISMNs
            $publisherIsmnRangeModel = JModelLegacy::getInstance('publisherismnrange', 'IsbnregistryModel');
            $ismns = $publisherIsmnRangeModel->getPublisherIdentifiers($this->item->id);
            $this->assignRef('ismns', $ismns);
        }

        // Get identifie URL parameter
        $identifier = JFactory::getApplication()->input->getString('identifier', '');
        // If parameter exists and its value is true, new publisher identifier
        // has been created
        if (strcmp($identifier, 'true') == 0) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_PUBLISHER_GET_IDENTIFIER_RANGE_SUCCESS'));
        }
        
        // Check if publisher has quitted
        if($this->item->has_quitted) {
            // Raise a warning if publisher has quitted
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISBNREGISTRY_PUBLISHER_WARNING_HAS_QUITTED'), 'Warning');
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
            $title = JText::_('COM_ISBNREGISTRY_PUBLISHER_NEW');
        } else {
            $title = JText::_('COM_ISBNREGISTRY_PUBLISHER_EDIT');
            $title .= ' : ' . $this->item->official_name;
        }

        JToolBarHelper::title($title, 'publisher');
        JToolbarHelper::apply('publisher.apply');
        JToolBarHelper::save('publisher.save');
        JToolbarHelper::save2new('publisher.save2new');
        if (!$isNew) {
            // Add custom button for sending a message
            $toolbar = JToolBar::getInstance('toolbar');
            $layout = new JLayoutFile('joomla.toolbar.popup');

            // Render the popup button
            $dhtml = $layout->render(array('name' => 'generate-message', 'doTask' => '', 'text' => JText::_('COM_ISBNREGISTRY_PUBLISHER_BUTTON_SEND_MESSAGE'), 'class' => 'icon-envelope'));
            $toolbar->appendButton('Custom', $dhtml);

            // Render the popup button
            $dhtml = $layout->render(array('name' => 'print', 'doTask' => '', 'text' => JText::_('COM_ISBNREGISTRY_PUBLISHER_BUTTON_PRINT'), 'class' => 'icon-print'));
            $toolbar->appendButton('Custom', $dhtml);
        }
        JToolBarHelper::cancel(
                'publisher.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE'
        );
    }

}
