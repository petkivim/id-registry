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
 * Form Archive Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @since       1.0.0
 */
class IssnregistryControllerFormarchive extends JControllerForm {

    public function getRecord() {
        // Check for request forgeries
        JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));

        // Get request parameters
        $formId = $this->input->getInt('formId');
        // Get model
        $model = $this->getModel();
        // Get srchive object
        $formArchive = $model->getByFormId($formId);
        // Return edit view
        $this->setRedirect('index.php?option=com_issnregistry&view=formarchive&id=' . $formArchive->id . '&layout=preview&tmpl=component');
        $this->redirect();
    }

}
