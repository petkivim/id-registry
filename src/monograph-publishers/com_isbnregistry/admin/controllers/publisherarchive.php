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
 * Publisher Archive Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @since       1.0.0
 */
class IsbnregistryControllerPublisherarchive extends JControllerForm {

    public function getRecord() {
        // Check for request forgeries
        JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));

        // Get request parameters
        $publisherId = JRequest::getVar("publisherId", null, "get", "int");
        // Get model
        $model = $this->getModel();
        // Get srchive object
        $publisherArchive = $model->getByPublisherId($publisherId);
        // Return edit view
        //return parent::edit($publisherArchive->id);
        $this->setRedirect('index.php?option=com_isbnregistry&view=publisherarchive&id=' . $publisherArchive->id . '&layout=preview&tmpl=component');
        $this->redirect();
    }

}
