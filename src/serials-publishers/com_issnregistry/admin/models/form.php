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
 * Message type Model
 *
 * @since  1.0.0
 */
class IssnregistryModelForm extends JModelAdmin {

    /**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $type    The table name. Optional.
     * @param   string  $prefix  The class prefix. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return  JTable  A JTable object
     *
     * @since   1.6
     */
    public function getTable($type = 'Form', $prefix = 'IssnregistryTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array    $data      Data for the form.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed    A JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true) {
        // Get the form.
        $form = $this->loadForm(
                'com_issnregistry.form', 'form', array(
            'control' => 'jform', 'load_data' => $loadData
                )
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed  The data for the form.
     *
     * @since   1.6
     */
    protected function loadFormData() {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState(
                'com_issnregistry.edit.form.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Returns the number of forms that are linked to the given publisher.
     * @param int $publisherId id of the publisher
     * @return int number of forms
     */
    public function getFormsCountByPublisherId($publisherId) {
        // Get db access
        $table = $this->getTable();
        // Get publisher
        return $table->getFormsCountByPublisherId($publisherId);
    }

    /**
     * Sets publisher created attribute to false.
     * @param int $formId id of the form to be updated
     * @return boolean true on success; false on failure
     */
    public function removePublisherCreated($formId) {
        // Get db access
        $table = $this->getTable();
        // Set "publisher_created" to false
        return $table->removePublisherCreated($formId);
    }

    /**
     * Increases the publication count of the form identified by the given form id
     * by one.
     * @param int $formId form id
     * @param int $oldCount current publication count that's increased by one
     * @return boolean true on success; false on failure
     */
    public function increasePublicationCount($formId, $oldCount) {
        // Get db access
        $table = $this->getTable();
        // Increase count
        return $table->increasePublicationCount($formId, $oldCount);
    }

    /**
     * Decreases the publication count of the form identified by the given form id
     * by one.
     * @param int $formId form id
     * @param int $oldCount current publication count that's decreased by one
     * @return boolean true on success; false on failure
     */
    public function decreasePublicationCount($formId, $oldCount) {
        // Get db access
        $table = $this->getTable();
        // Decrease count
        return $table->decreasePublicationCount($formId, $oldCount);
    }

    /**
     * Increases the publication with ISSN count of the form identified by 
     * the given form id by one.
     * @param int $formId form id
     * @param int $oldCount current publication with ISSN count that's 
     * increased by one
     * @return boolean true on success; false on failure
     */
    public function increasePublicationWithIssnCount($formId, $oldCount) {
        // Get db access
        $table = $this->getTable();
        // Increase count
        return $table->increasePublicationWithIssnCount($formId, $oldCount);
    }

    /**
     * Decreases the publication with ISSN count of the form identified by 
     * the given form id by one.
     * @param int $formId form id
     * @param int $oldCount current publication with ISSN count that's 
     * decreased by one
     * @return boolean true on success; false on failure
     */
    public function decreasePublicationWithIssnCount($formId, $oldCount) {
        // Get db access
        $table = $this->getTable();
        // Decrease count
        return $table->decreasePublicationWithIssnCount($formId, $oldCount);
    }

    /**
     * Adds a new publisher to the database using the publisher information
     * that is included in the given form. The created publisher is set
     * as the publisher of the form and the reference is updated to all the
     * publications related to the form.
     * @param object $form form object
     * @return boolean true on success, false on failure
     */
    public function addPublisher($form) {
        // Get db access
        $table = $this->getTable();
        // Start transaction
        $table->transactionStart();

        // Create array for publisher
        $publisher = array(
            'official_name' => $form->publisher,
            'contact_person' =>  empty($form->contact_person) ? '' : $this->toJson($form->contact_person, $form->email),
            'email' => empty($form->contact_person) ? $form->email : '',
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
            $this->setError(JText::_('COM_ISSNREGISTRY_FORM_CREATE_PUBLISHER_FAILED'), 'error');
            if ($publisherModel->getError()) {
                $this->setError($publisherModel->getError(), 'error');
            }
            $table->transactionRollback();
            return false;
        }
        // Get newly created publisher from db - we need the id
        $publisherFromDb = $publisherModel->getByFormId($form->id);

        // Update form to db
        if (!$table->addCreatedPublisher($form->id, $publisherFromDb->id)) {
            $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_FORM_UPDATING_PUBLISHER_ID_FAILED'), 'error');
            $table->transactionRollback();
            return false;
        }
        // Get publication model
        $publicationModel = JModelLegacy::getInstance('publication', 'IssnregistryModel');
        // Update publisher id to all the publications
        $updateCount = $publicationModel->updatePublisherId($form->id, $publisherFromDb->id);
        // Check that all the publications were updated
        if ($updateCount != $form->publication_count) {
            $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_UPDATE_PUBLISHER_ID_TO_PUBLICATIONS_FAILED'), 'error');
            $table->transactionRollback();
            return false;
        }
        // Commit transaction
        $table->transactionCommit();
        // Return true
        return true;
    }

    /**
     * Creates a new publication and links it to the given form. Only publication
     * title, form id and publisher id are added. Also form's publication
     * counter is updated
     * @param object $form form owning the publication
     * @return boolean true on success, false on failure
     */
    public function addPublication($form) {
        // Get db access
        $table = $this->getTable();
        // Start transaction
        $table->transactionStart();

        // Array for publication
        $publication = array(
            'title' => JText::_('COM_ISSNREGISTRY_PUBLICATION_TITLE_NEW'),
            'publisher_id' => $form->publisher_id,
            'form_id' => $form->id
        );

        // Load publication model
        $publicationModel = JModelLegacy::getInstance('publication', 'IssnregistryModel');
        // Save publication to db
        if (!$publicationModel->save($publication)) {
            $this->setError(JText::_('COM_ISSNREGISTRY_FORM_CREATE_PUBLICATION_FAILED'), 'error');
            if ($publicationModel->getError()) {
                $this->setError($publicationModel->getError(), 'error');
            }
            $table->transactionRollback();
            return false;
        }

        // Increase form's publication counter
        if (!$this->increasePublicationCount($form->id, $form->publication_count)) {
            $this->setError(JText::_('COM_ISSNREGISTRY_FORM_UPDATE_PUBLICATION_COUNT_FAILED'), 'error');
            $table->transactionRollback();
            return false;
        }
        // Commit transaction
        $table->transactionCommit();
        // Return true
        return true;
    }

    /**
     * Sets the status of the form identified by the given form id as
     * "COMPLETED". The current status must be "NOT_NOTIFIED", otherwise
     * updating the status fails.
     * @param int $formId id of the form
     * @return boolean true on success, false on failure
     */
    public function setStatusCompleted($formId) {
        // Get db access
        $table = $this->getTable();
        // Decrease count
        return $table->setStatusCompleted($formId);
    }

    private function toJson($contactPerson, $email) {
        return '{"name":["' . $contactPerson . '"],"email":["' . $email . '"]}';
    }

}
