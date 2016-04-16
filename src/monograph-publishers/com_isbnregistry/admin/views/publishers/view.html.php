<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Publishers View
 *
 * @since  1.0.0
 */
class IsbnregistryViewPublishers extends JViewLegacy {

    /**
     * Display the Publishers view
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

        // Get filter no identifier
        $filterNoIdentifier = $this->state->get('filter.no_identifier');
        // Add publishers helper file
        require_once JPATH_COMPONENT . '/helpers/publishers.php';
        // Add sidebar
        if ($filterNoIdentifier == 1) {
            PublishersHelper::addSubmenu('publishers_applications');
            // Set an empty array as variable value
            $this->ismn_publisher_ids = array();
        } else {
            PublishersHelper::addSubmenu('publishers_registry');
            // Load publisher ismn range model
            $model = JModelLegacy::getInstance('publisherismnrange', 'IsbnregistryModel');
            // Load message types
            $ids = $model->getIsmnPublisherIds();
            // Pass results to the layout
            $this->ismn_publisher_ids = $ids;
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
        JToolBarHelper::addNew('publisher.add');
        JToolBarHelper::editList('publisher.edit');
        JToolBarHelper::deleteList('', 'publishers.delete');

        // Get the value of no_identifier filter
        $state = $this->state->get('filter.no_identifier');
        // Check that we're showing publisher register
        if ($state == 5) {
            // Set page title
            JToolBarHelper::title(JText::_('COM_ISBNREGISTRY_PUBLISHERS_REGISTRY'));
            // Get component parameters
            $params = JComponentHelper::getParams('com_isbnregistry');

            // Get the id of the publisher that represents author publishers
            $authorPublisherId = $params->get('author_publisher_id_isbn', 0);

            // Get statistics file format
            $format = $params->get('statistics_format', 'XLS');

            JToolBarHelper::custom('publishers.get' . $format, 'pie', 'pie', JText::_('COM_ISBNREGISTRY_PUBLISHERS_BUTTON_GET_CSV'), false, false);

            $toolbar = JToolBar::getInstance('toolbar');
            $layout = new JLayoutFile('joomla.toolbar.popup');

            // Render the popup button
            $dhtml = $layout->render(array('name' => 'statistics', 'doTask' => '', 'text' => JText::_('COM_ISBNREGISTRY_PUBLISHER_BUTTON_STATISTICS'), 'class' => 'icon-pie'));
            $toolbar->appendButton('Custom', $dhtml);

            // Check that author publisher has been defined
            if ($authorPublisherId != 0) {
                JToolBarHelper::custom('publishers.toAuthorPublisher', 'user', 'user', JText::_('COM_ISBNREGISTRY_PUBLISHERS_BUTTON_TO_AUTHOR_PUBLISHER'), false, false);
            }
        } else if ($state == 1) {
            JToolBarHelper::title(JText::_('COM_ISBNREGISTRY_PUBLISHERS_APPLICATION'));
        } else {
            JToolBarHelper::title(JText::_('COM_ISBNREGISTRY_PUBLISHERS'));
        }
        JToolBarHelper::preferences('com_isbnregistry');
    }

}
