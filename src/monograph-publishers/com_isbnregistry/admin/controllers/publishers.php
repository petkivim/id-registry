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
 * Publishers Controller
 *
 * @since  1.0.0
 */
class IsbnregistryControllerPublishers extends JControllerAdmin {

    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The model name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  object  The model.
     *
     * @since   1.6
     */
    public function getModel($name = 'Publisher', $prefix = 'IsbnregistryModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

    public function toAuthorPublisher() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // Get component parameters
        $params = JComponentHelper::getParams('com_isbnregistry');
        // Get the id of the publisher that represents author publishers
        $authorPublisherId = $params->get('author_publisher_id_isbn', 0);

        // Redirect to XML format
        $this->setRedirect('index.php?option=com_isbnregistry&view=publisher&layout=edit&id=' . $authorPublisherId);
        $this->redirect();
    }

}
