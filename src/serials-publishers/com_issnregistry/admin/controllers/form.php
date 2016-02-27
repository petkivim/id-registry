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
            // Create array for publisher
            $publisher = array(
                'official_name' => $form->publisher,
                'contact_person' => $form->contact_person,
                'email' => $form->email,
                'phone' => $form->phone,
                'address' => $form->address,
                'zip' => $form->zip,
                'city' => $form->city,
                'lang_code' => $form->lang_code,
                'form_id' => $form->id
            );

            // Load publisher model
            $publisherModel = JModelLegacy::getInstance('publisher', 'IssnregistryModel');
            // Save publisher to db
            if (!$publisherModel->save($publisher)) {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_FORM_CREATE_PUBLISHER_FAILED'), 'error');
                if ($publisherModel->getError()) {
                    JFactory::getApplication()->enqueueMessage($publisherModel->getError(), 'error');
                }
            } else {
                JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_FORM_CREATE_PUBLISHER_SUCCESS'));
                // Get newly created publisher from db - we need the id
                $publisherFromDb = $publisherModel->getByFormId($form->id);
                // Update form values
                $form->publisher_created = true;
                $form->publisher_id = $publisherFromDb->id;
                // Update form to db
                if (!$this->getModel()->save(JArrayHelper::fromObject($form))) {
                    JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_ERROR_FORM_UPDATING_PUBLISHER_ID_FAILED'), 'warning');
                } else {
                    // Get publication model
                    $publicationModel = JModelLegacy::getInstance('publication', 'IssnregistryModel');
                    // Update publisher id to all the publications
                    // The result is not checked, because the publisher is not changed
                    // every time which is why the result may be zero even if
                    // there's no error
                    $publicationModel->updatePublisherId($form->id, $form->publisher_id);
                }
            }
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_ISSNREGISTRY_FORM_CREATE_PUBLISHER_FAILED'), 'error');
        }

        // Redirect back to edit view
        $this->setRedirect('index.php?option=com_issnregistry&view=form&id=' . $id . '&layout=edit');
        $this->redirect();
    }

}
