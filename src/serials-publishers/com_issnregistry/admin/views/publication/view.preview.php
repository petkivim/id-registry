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
        // Generate MARC record
        $marc = PublicationHelper::previewMarc($this->item);


        // Set document properties
        $document = JFactory::getDocument();
        $document->setMimeEncoding('text/plain');

        echo $marc;
    }

}
