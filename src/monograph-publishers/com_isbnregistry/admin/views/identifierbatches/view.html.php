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
 * Identifier batches View
 *
 * @since  1.0.0
 */
class IsbnregistryViewIdentifierbatches extends JViewLegacy {

    /**
     * Display the Identifier batches view
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

        // Load publication model
        $publicationModel = JModelLegacy::getInstance('publication', 'IsbnregistryModel');
        // Load publications
        $publications = $publicationModel->getPublicationsArray();
        // Pass $publications to the layout
        $this->publications = $publications;

        // Get publisher id URL parameter
        $publisherId = JFactory::getApplication()->input->getInt('publisherId');
        // Load message model
        $messageModel = JModelLegacy::getInstance('message', 'IsbnregistryModel');
        // Get messages related to this publication
        $ids = $messageModel->getBatchIdsAndMessageIdsByPublisher($publisherId);
        // Pass $ids to the layout
        $this->messages = $ids;
        // Set publisher id
        $this->publisher_id = $publisherId;

        // Get component parameters
        $params = JComponentHelper::getParams('com_isbnregistry');
        // Set identifiers attachment limit
        $this->attachmentLimit = $params->get('identifiers_attachment_limit', 0);
        
        // Render the sidebar - pagination does not work without this!
        $this->sidebar = JHtmlSidebar::render();
        // Display the template
        parent::display($tpl);
    }

}
