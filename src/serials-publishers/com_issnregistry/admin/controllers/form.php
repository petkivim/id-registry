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

    public function createPublisher() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        try {
            // Get form id
            $id = $this->input->getInt('id');
            // Get form 
            $form = $this->getModel()->getItem($id);

            // Check that publisher has not been created already and that
            // no publisher is linked to this form already
            if ($form->publisher_created || $form->publisher_id > 0) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ERROR_FORM_PUBLISHER_ALREADY_CREATED'), 'warning');
                // Redirect back to edit view
                $this->setRedirect('index.php?option=com_issnregistry&view=form&id=' . $id . '&layout=edit');
                $this->redirect();
            }
            // Create publisher
            if (!$this->getModel()->addPublisher($form)) {
                JFactory::getApplication()->enqueueMessage($this->getModel()->getError(), 'error');
            } else {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_FORM_CREATE_PUBLISHER_SUCCESS'));
            }
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_FORM_CREATE_PUBLISHER_FAILED'), 'error');
        }

        // Redirect back to edit view
        $this->setRedirect('index.php?option=com_issnregistry&view=form&id=' . $id . '&layout=edit');
        $this->redirect();
    }

    public function createPublication() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        try {
            // Get form id
            $id = $this->input->getInt('formId');
            // Get form 
            $form = $this->getModel()->getItem($id);
            // Check that we have a form
            if ($form->id == 0) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_FORM_NOT_FOUND'), 'error');
                // Redirect back to edit view
                $this->setRedirect('index.php?option=com_issnregistry&view=forms');
                $this->redirect();
            }

            // Save publication to db
            if (!$this->getModel()->addPublication($form)) {
                JFactory::getApplication()->enqueueMessage($this->getModel()->getError(), 'error');
            } else {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_FORM_CREATE_PUBLICATION_SUCCESS'));
            }
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_FORM_CREATE_PUBLICATION_FAILED'), 'error');
        }
        // Redirect back to edit view
        $this->setRedirect('index.php?option=com_issnregistry&view=form&id=' . $id . '&layout=edit');
        $this->redirect();
    }

}
