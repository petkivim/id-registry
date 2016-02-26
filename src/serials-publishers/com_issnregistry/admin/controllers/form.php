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
 * Message type Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @since       1.0.0
 */
class IssnregistryControllerForm extends JControllerForm {

    protected function postSaveHook($model, $validData) {
        $item = $model->getItem();
        $formId = $item->get('id');
        $publisherId = $item->get('publisher_id');
        // Get publication model
        $publicationModel = JModelLegacy::getInstance('publication', 'IssnregistryModel');
        // Update publisher id to all the publications
        // The result is not checked, because the publisher is not changed
        // every time which is why the result may be zero even if
        // there's no error
        $publicationModel->updatePublisherId($formId, $publisherId); 
    }

}
