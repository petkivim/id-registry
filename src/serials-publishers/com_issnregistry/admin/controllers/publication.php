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
 * Publisher Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @since       1.0.0
 */
class IssnregistryControllerPublication extends JControllerForm {

    public function download() {
        // Get publication id
        $id = $this->input->getInt('id');
        // Redirect to raw format
        $this->setRedirect('index.php?option=com_issnregistry&view=publication&id=' . $id . '&layout=edit&format=raw');
        $this->redirect();
    }

}
