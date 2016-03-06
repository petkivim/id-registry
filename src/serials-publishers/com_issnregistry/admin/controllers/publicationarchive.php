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
 * Publication Archive Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @since       1.0.0
 */
class IssnregistryControllerPublicationarchive extends JControllerForm {

    public function getRecord() {
        // Check for request forgeries
        JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));

        // Get request parameters
        $publicationId = $this->input->getInt('publicationId');
        // Get model
        $model = $this->getModel();
        // Get srchive object
        $publicationArchive = $model->getByPublicationId($publicationId);
        // Return edit view
        $this->setRedirect('index.php?option=com_issnregistry&view=publicationarchive&id=' . $publicationArchive->id . '&layout=preview&tmpl=component');
        $this->redirect();
    }

}
