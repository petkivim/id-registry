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
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // Get publication id
        $id = $this->input->getInt('id');
        // Redirect to raw format
        $this->setRedirect('index.php?option=com_issnregistry&view=publication&id=' . $id . '&layout=edit&format=raw');
        $this->redirect();
    }

    public function getIssn() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        try {
            // Get publication id
            $id = $this->input->getInt('id');
            // Load issn range model
            $issnRangeModel = JModelLegacy::getInstance('issnrange', 'IssnregistryModel');
            // Get new ISSN
            $result = $issnRangeModel->getIssn($id);
            // Check result, created issn is returned on success, an empty
            // string on failure
            if (empty($result)) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ISSN_RANGE_GET_ISSN_FAILED'), 'error');
                if ($issnRangeModel->getError()) {
                    JFactory::getApplication()->enqueueMessage($issnRangeModel->getError(), 'error');
                }
            } else {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ISSN_RANGE_GET_ISSN_SUCCESS'));
            }
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ISSN_RANGE_GET_ISSN_FAILED'), 'error');
        }

        // Redirect back to edit view
        $this->setRedirect('index.php?option=com_issnregistry&view=publication&id=' . $id . '&layout=edit');
        $this->redirect();
    }

    public function deleteIssn() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        try {
            // Get publication id
            $id = $this->input->getInt('id');
            // Get publication 
            $publication = $this->getModel()->getItem($id);
            // Load issn range model
            $issnRangeModel = JModelLegacy::getInstance('issnrange', 'IssnregistryModel');
            // Delete ISSN
            $result = $issnRangeModel->deleteIssn($publication->issn);
            // Check result
            if (!$result) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ISSN_RANGE_DELETE_ISSN_FAILED'), 'error');
                if ($issnRangeModel->getError()) {
                    JFactory::getApplication()->enqueueMessage($issnRangeModel->getError(), 'error');
                }
            } else {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ISSN_RANGE_DELETE_ISSN_SUCCESS'));
            }
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ISSN_RANGE_DELETE_ISSN_FAILED'), 'error');
        }

        // Redirect back to edit view
        $this->setRedirect('index.php?option=com_issnregistry&view=publication&id=' . $id . '&layout=edit');
        $this->redirect();
    }

}
