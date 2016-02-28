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
 * Publication View
 *
 * @since  1.0.0
 */
class IssnregistryViewPublication extends JViewLegacy {

    protected $item = null;

    /**
     * Returns the publication in MARC21 format as an attachment.
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    public function display($tpl = null) {
        $this->item = $this->get('Item');

        // Add publications helper file
        require_once JPATH_COMPONENT . '/helpers/publication.php';
        // Load publisher model
        $publisherModel = JModelLegacy::getInstance('publisher', 'IssnregistryModel');
        // Load publisher
        $publisher = $publisherModel->getItem($this->item->publisher_id);
        // Load form model
        $formModel = JModelLegacy::getInstance('form', 'IssnregistryModel');
        // Load form
        $form = $formModel->getItem($this->item->form_id);
        // Generate MARC record
        $marc = PublicationHelper::rawMarc($this->item, $form, $publisher);


        // Set document properties
        $document = JFactory::getDocument();
        $document->setMimeEncoding('text/plain');

        JResponse::setHeader('Content-disposition', 'attachment; filename="marc.txt"', true);

        echo $marc;
    }

}
