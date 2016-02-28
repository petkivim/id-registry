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
 * ISSN Range Model
 *
 * @since  1.0.0
 */
class IssnregistryModelIssnrange extends JModelAdmin {

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
    public function getTable($type = 'Issnrange', $prefix = 'IssnregistryTable', $config = array()) {
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
                'com_issnregistry.issnrange', 'issnrange', array(
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
                'com_issnregistry.edit.issnrange.data', array()
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Generates a new ISSN identifier from the active ISSN range and
     * assigns it to the given publication.
     * @param int $publicationId id of the publication to which the identifier is assigned
     * @return string returns an empty string if the operation fails; on success the generated
     * ISSN identifier string is returned
     */
    public function getIssn($publicationId) {
        // Get DAO for db access
        $table = $this->getTable();
        // Start transaction
        $table->transactionStart();
        // Get active ISSN range object
        $range = $table->getActiveRange();

        // Check that we have a result
        if ($range == null) {
            $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_ACTIVE_ISSN_RANGE_NOT_FOUND'));
            $table->transactionRollback();
            return '';
        }
        // Set range id
        $rangeId = $range->id;

        // Add ISSN range helper file
        require_once JPATH_COMPONENT . '/helpers/issnrange.php';

        // Get an instance of issn canceled model
        $issnCanceledModel = $this->getInstance('issncanceled', 'IssnregistryModel');
        // Try to get the smallest canceled issn object
        $canceledIssn = $issnCanceledModel->getIssn();
        // Check if a canceled issn was found
        if ($canceledIssn != null) {
            // Get the canceled issn value
            $issn = $canceledIssn->issn;
            // Validate issn
            if (!IssnrangeHelper::validateIssn($issn)) {
                $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_CANCELED_ISSN_INVALID'));
                $table->transactionRollback();
                return '';
            }
            // Try to delete the canceled issn from db
            if (!$issnCanceledModel->delete($canceledIssn->id)) {
                $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_CANCELED_ISSN_DELETE_FAILED'));
                $table->transactionRollback();
                return '';
            }
            // Set range id
            $rangeId = $canceledIssn->issn_range_id;
        } else {
            // Get the next available number
            $issn = $range->block . '-' . $range->next;
            // Is this the last value of the range
            if (strcmp($range->next, $range->range_end) == 0) {
                // This is the last value -> range becames inactive
                $range->is_active = false;
                // Range becomes closed
                $range->is_closed = true;
            }
            // Increase next pointer
            $range->next = substr($range->next, 0, 3) + 1;
            // Next pointer is a string, add left padding
            $range->next = str_pad($range->next, 3, "0", STR_PAD_LEFT);
            // Get next pointer check digit
            $rangeNextCheckDigit = IssnrangeHelper::countIssnCheckDigit($range->block . $range->next);
            // Set next pointer check digit
            $range->next .= $rangeNextCheckDigit;
            // Validate next pointer
            if (!IssnrangeHelper::validateIssn($range->block . $range->next)) {
                $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_ISSN_RANGE_UPDATE_INVALID_NEXT_POINTER'));
                $table->transactionRollback();
                return '';
            }

            // Decrease free numbers pointer 
            $range->free -= 1;
            // Increase used numbers pointer
            $range->taken += 1;
            // Update new values to database
            if (!$table->updateIncrease($range)) {
                $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_ISSN_RANGE_UPDATE_FAILED'));
                $table->transactionRollback();
                return '';
            }
        }

        // Get an instance of a publication model
        $publicationModel = $this->getInstance('publication', 'IssnregistryModel');
        // Update publisher's active identifier range info
        if (!$publicationModel->updateIssn($publicationId, $issn)) {
            $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_PUBLICATION_ISSN_UPDATE_FAILED'));
            $table->transactionRollback();
            return '';
        }
        // Get publication
        $publication = $publicationModel->getItem($publicationId);
        // Get an instance of a form model
        $formModel = $this->getInstance('form', 'IssnregistryModel');
        // Get form 
        $form = $formModel->getItem($publication->form_id);
        // Update form counters
        if (!$formModel->increasePublicationWithIssnCount($form->id, $form->publication_count_issn)) {
            $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_FORM_PUBLICATIONS_COUNT_ISSN_UPDATE_FAILED'));
            $table->transactionRollback();
            return '';
        }

        // Create ISSN used object
        $issnUsed = array();
        $issnUsed['issn'] = $issn;
        $issnUsed['publication_id'] = $publicationId;
        $issnUsed['issn_range_id'] = $rangeId;

        // Get an instance of issn used model
        $issnUsedModel = $this->getInstance('issnused', 'IssnregistryModel');
        // Add new issn used entry
        if ($issnUsedModel->addNew($issnUsed) == 0) {
            $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_ISSN_USED_ENTRY_CREATION_FAILED'));
            $table->transactionRollback();
            return '';
        }

        // Commit transaction
        $table->transactionCommit();
        // Return ISSN
        return $issn;
    }

    /**
     * Deletes the given ISSN number if and only if it is the last ISSN
     * number that was generated from its range.
     * @param string $issn ISSN number to be deleted
     * @return boolean true on success, false on failure
     */
    public function deleteIssn($issn) {
        // Add ISSN range helper file
        require_once JPATH_COMPONENT . '/helpers/issnrange.php';
        // Validate ISSN
        if (!IssnrangeHelper::validateIssn($issn)) {
            $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_ISSN_INVALID'));
            return false;
        }
        // Get db access
        $table = $this->getTable();
        // Start transaction
        $table->transactionStart();

        // Get an instance of ISSN used model
        $issnUsedModel = $this->getInstance('issnused', 'IssnregistryModel');
        // Get ISSN used object
        $issnUsed = $issnUsedModel->findByIssn($issn);
        // Check that we have a result
        if ($issnUsed == null) {
            $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_ISSN_NOT_FOUND'));
            $table->transactionRollback();
            return false;
        }
        // Get the last ISSN from the same range
        $lastIssn = $issnUsedModel->getLast($issnUsed->issn_range_id);
        // Check that ISSN numbers match
        if (strcmp($issn, $lastIssn) == 0) {
            // Try to decrease the ISSN range counter
            if (!$this->decreaseByOne($issnUsed->issn_range_id)) {
                $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_ISSN_DELETE_FAILED'));
                $table->transactionRollback();
                return false;
            }
        } else {
            // Add issn canceled entry
            // Get an instance of issn canceled model
            $issnCanceledModel = $this->getInstance('issncanceled', 'IssnregistryModel');
            // Create ISSN canceled object
            $issnCanceled = array();
            $issnCanceled['issn'] = $issn;
            $issnCanceled['issn_range_id'] = $issnUsed->issn_range_id;
            // Add new issn canceled entry
            if ($issnCanceledModel->addNew($issnCanceled) == 0) {
                $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_ISSN_CANCELED_ENTRY_CREATION_FAILED'));
                $table->transactionRollback();
                return false;
            }
        }
        // Try to delete the ISSN used entry
        if (!$issnUsedModel->deleteIssn($issnUsed->issn_range_id, $issn)) {
            $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_ISSN_USED_DELETE_FAILED'));
            $table->transactionRollback();
            return false;
        }

        // Get an instance of a publication model
        $publicationModel = $this->getInstance('publication', 'IssnregistryModel');
        // Remove ISSN from publisher
        if (!$publicationModel->removeIssn($issnUsed->publication_id)) {
            $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_PUBLICATION_ISSN_UPDATE_FAILED'));
            $table->transactionRollback();
            return false;
        }

        // Get publication
        $publication = $publicationModel->getItem($issnUsed->publication_id);
        // Get an instance of a form model
        $formModel = $this->getInstance('form', 'IssnregistryModel');
        // Get form 
        $form = $formModel->getItem($publication->form_id);
        // Update form counters
        if (!$formModel->decreasePublicationWithIssnCount($form->id, $form->publication_count_issn)) {
            $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_FORM_PUBLICATIONS_COUNT_ISSN_UPDATE_FAILED'));
            $table->transactionRollback();
            return false;
        }
        
        // Commit transaction
        $table->transactionCommit();
        // Return true
        return true;
    }

    /**
     * Updates the range matching the given identifier id and decreases its
     * next pointer by one. Also free and taken properties are updated 
     * accordingly.
     * @param integer $rangeId id of the range to be updated
     * @return boolean true if and only if the operation succeeds; otherwise
     * false
     */
    public function decreaseByOne($rangeId) {
        // Get db access
        $table = $this->getTable();
        // Get ISBN range object
        $range = $table->getRange($rangeId, false);

        // Check that we have a result
        if ($range != null) {
            // Decrease next pointer
            $range->next = substr($range->next, 0, 3) - 1;
            // Next pointer is a string, add left padding
            $range->next = str_pad($range->next, 3, "0", STR_PAD_LEFT);
            // Add ISSN range helper file
            require_once JPATH_COMPONENT . '/helpers/issnrange.php';
            // Get next pointer check digit
            $rangeNextCheckDigit = IssnrangeHelper::countIssnCheckDigit($range->block . $range->next);
            // Set next pointer check digit
            $range->next .= $rangeNextCheckDigit;
            // Validate next pointer
            if (!IssnrangeHelper::validateIssn($range->block . $range->next)) {
                $this->setError(JText::_('COM_ISSNREGISTRY_ERROR_ISSN_RANGE_UPDATE_INVALID_NEXT_POINTER'));
                return false;
            }
            // Update free
            $range->free += 1;
            // Update taken
            $range->taken -= 1;
            // Check if closed
            if ($range->is_closed) {
                // Update is_closed and is_active
                $range->is_closed = false;
                $range->is_active = true;
            }
            // Update to db
            if ($table->updateDecrease($range)) {
                return true;
            }
        }
        return false;
    }

}
