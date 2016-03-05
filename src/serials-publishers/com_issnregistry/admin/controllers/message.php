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
 * Message Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @since       1.0.0
 */
class IssnregistryControllerMessage extends JControllerForm {

    public function resend($key = null, $urlVar = null) {
        // Save data
        $return = parent::save(0, $urlVar);
        // Set redirect
        $this->setRedirect(JRoute::_('index.php?option=com_issnregistry&view=messages', false));
        // Update response message based on save function's result
        if ($return) {
            $this->setMessage(JText::_('COM_ISSNREGISTRY_FORM_MESSAGE_SENT'), 'message');
        } else {
            $this->setMessage(JText::_('COM_ISSNREGISTRY_FORM_MESSAGE_SENT_FAILED'), 'error');
        }
        return $return;
    }

    public function send($key = null, $urlVar = null) {
        // Save data
        $return = parent::save($key, $urlVar);
        // Set redirect
        $this->setRedirect(JRoute::_('index.php?option=com_issnregistry&view=message&layout=send_result&tmpl=component', false));
        // Update response message based on save function's result
        if ($return) {
            $this->setMessage(JText::_('COM_ISSNREGISTRY_FORM_MESSAGE_SENT'), 'message');
        } else {
            $this->setMessage(JText::_('COM_ISSNREGISTRY_FORM_MESSAGE_SENT_FAILED'), 'error');
        }
        return $return;
    }

}
