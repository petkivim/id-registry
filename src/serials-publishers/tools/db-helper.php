<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Get Joomla! framework
define('_JEXEC', 1);
define('JPATH_BASE', '/var/www/html/administrator');
require_once ( JPATH_BASE . '/includes/defines.php' );
require_once ( JPATH_BASE . '/includes/framework.php' );

/**
 * This class offers static helper methods for database operatopns
 * on importing data.
 */
class DbHelper {

    public static function saveForms(&$forms) {
        // Start transaction
        JFactory::getDbo()->transactionStart();
        // Loop through forms
        foreach ($forms as $key => $value) {
            // Save form
            $id = self::saveForm($key, $value);
            if ($id == 0) {
                // If operation failed, do rollback
                JFactory::getDbo()->transactionRollback();
                return false;
            }
            // Set new id
            $forms[$key]['id'] = $id;
        }
        // Commit transaction
        JFactory::getDbo()->transactionCommit();
        return true;
    }

    private static function saveForm($id, $form) {
        try {
            // database connection
            $db = JFactory::getDbo();
            // Insert columns
            $columnsBase = array('publisher', 'contact_person', 'email', 'phone', 'address', 'zip', 'city', 'publication_count', 'lang_code', 'created', 'created_by');
            $columnsNewTemp = array('publication_count_issn', 'publisher_id', 'publisher_created', 'status');
            $columnsNew = array_merge($columnsNewTemp, $columnsBase);
            $columnsArchiveTemp = array('id_old');
            $columnsArchive = array_merge($columnsArchiveTemp, $columnsBase);
            // Insert values
            $valuesBase = array($db->quote($form['publisher']), $db->quote($form['contact_person']), $db->quote($form['email']), $db->quote($form['phone']), $db->quote($form['address']), $db->quote($form['zip']), $db->quote($form['city']), $db->quote($form['publication_count']), $db->quote($form['lang_code']), $db->quote($form['created']), $db->quote($form['created_by']));
            $valuesNewTemp = array($db->quote($form['publication_count_issn']), $db->quote($form['publisher_id']), $db->quote($form['publisher_created']), $db->quote($form['status']));
            $valuesNew = array_merge($valuesNewTemp, $valuesBase);
            $valuesArchiveTemp = array($id);
            $valuesArchive = array_merge($valuesArchiveTemp, $valuesBase);

            // Create a new query object.
            $query = $db->getQuery(true);
            // Prepare the insert query
            $query->insert($db->quoteName('#__issn_registry_form'))
                    ->columns($db->quoteName($columnsNew))
                    ->values(implode(',', $valuesNew));
            // Set the query using our newly populated query object and execute it
            $db->setQuery($query);
            $db->execute();
            $newID = $db->insertid();

            // If form was succesfully saved, add archive entry
            if ($newID > 0) {
                array_push($columnsArchive, 'form_id');
                array_push($valuesArchive, $newID);
                // Create a new query object.
                $query = $db->getQuery(true);
                // Prepare the insert query
                $query->insert($db->quoteName('#__issn_registry_form_archive'))
                        ->columns($db->quoteName($columnsArchive))
                        ->values(implode(',', $valuesArchive));
                $db->setQuery($query);
                $db->execute();
                if ($db->insertid() == 0) {
                    return 0;
                }
            }
            return $newID;
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function updateForms(&$forms, &$publishers) {
        // Start transaction
        JFactory::getDbo()->transactionStart();
        // Loop through publishers
        foreach ($forms as $key => $value) {
            if (array_key_exists($value['publisher_id'], $publishers)) {
                // Update form
                $rowCount = self::updateForm($value['id'], $value['publisher_id'], $publishers[$value['publisher_id']]['id']);
                if ($rowCount == 0) {
                    // If operation failed, do rollback
                    JFactory::getDbo()->transactionRollback();
                    return false;
                }
                // Set new publisher id
                $forms[$key]['publisher_id'] = $publishers[$value['publisher_id']]['id'];
            }
        }
        // Commit transaction
        JFactory::getDbo()->transactionCommit();
        return true;
    }

    private static function updateForm($id, $oldPublisherId, $publisherId) {
        try {
            // database connection
            $db = JFactory::getDbo();

            // Create a new query object.
            $query = $db->getQuery(true);

            // Fields to update.
            $fields = array(
                $db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId)
            );

            // Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('id') . ' = ' . $db->quote($id),
                $db->quoteName('publisher_id') . ' = ' . $db->quote($oldPublisherId)
            );
            // Create query
            $query->update($db->quoteName('#__issn_registry_form'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $db->execute();
            // Return the number of rows that was updated
            return $db->getAffectedRows();
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function savePublishers(&$publishers, &$forms) {
        // Start transaction
        JFactory::getDbo()->transactionStart();
        // Loop through publishers
        foreach ($publishers as $key => $value) {
            $formId = 0;
            if (array_key_exists($value['form_id'], $forms)) {
                $formId = $forms[$value['form_id']]['id'];
                $publishers[$key]['form_id'] = $forms[$value['form_id']]['id'];
            }
            // Save publisher
            $id = self::savePublisher($key, $value, $formId);
            if ($id == 0) {
                // If operation failed, do rollback
                JFactory::getDbo()->transactionRollback();
                return false;
            }
            // Set new id
            $publishers[$key]['id'] = $id;
        }
        // Commit transaction
        JFactory::getDbo()->transactionCommit();
        return true;
    }

    private static function savePublisher($id, $publisher, $formId) {
        try {
            // database connection
            $db = JFactory::getDbo();
            // Insert columns
            $columns = array('official_name', 'contact_person', 'email', 'phone', 'address', 'zip', 'city', 'lang_code', 'additional_info', 'form_id', 'id_old', 'created', 'created_by', 'modified', 'modified_by');
            // Insert values           
            $values = array($db->quote($publisher['official_name']), $db->quote($publisher['contact_person']), $db->quote($publisher['email']), $db->quote($publisher['phone']), $db->quote($publisher['address']), $db->quote($publisher['zip']), $db->quote($publisher['city']), $db->quote($publisher['lang_code']), $db->quote($publisher['additional_info']), $db->quote($formId), $db->quote($publisher['id_old']), $db->quote($publisher['created']), $db->quote($publisher['created_by']), $db->quote($publisher['modified']), $db->quote($publisher['modified_by']));

            // Create a new query object.
            $query = $db->getQuery(true);
            // Prepare the insert query
            $query->insert($db->quoteName('#__issn_registry_publisher'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
            // Set the query using our newly populated query object and execute it
            $db->setQuery($query);
            $db->execute();
            return $db->insertid();
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function savePublications(&$publications, &$forms, &$publishers) {
        // Start transaction
        JFactory::getDbo()->transactionStart();
        // Loop through publications
        foreach ($publications as $key => $value) {
            $formId = 0;
            if (array_key_exists($value['form_id'], $forms)) {
                $formId = $forms[$value['form_id']]['id'];
                $publications[$key]['form_id'] = $forms[$value['form_id']]['id'];
            }
            $publisherId = 0;
            if (array_key_exists($value['publisher_id'], $publishers)) {
                $publisherId = $publishers[$value['publisher_id']]['id'];
                $publications[$key]['publisher_id'] = $publishers[$value['publisher_id']]['id'];
            }
            // Save publisher
            $id = self::savePublication($key, $value, $formId, $publisherId);
            if ($id == 0) {
                // If operation failed, do rollback
                JFactory::getDbo()->transactionRollback();
                return false;
            }
            // Set new id
            $publications[$key]['id'] = $id;
        }
        // Commit transaction
        JFactory::getDbo()->transactionCommit();
        return true;
    }

    private static function savePublication($id, $publication, $formId, $publisherId) {
        try {
            // database connection
            $db = JFactory::getDbo();
            // Insert columns
            $columnsBase = array(
                'title', 'place_of_publication', 'printer', 'issued_from_year',
                'issued_from_number', 'frequency', 'frequency_other',
                'language', 'publication_type', 'publication_type_other',
                'medium', 'medium_other', 'url', 'previous', 'main_series',
                'subseries', 'another_medium', 'additional_info', 'form_id',
                'created', 'created_by');
            $columnsNewTemp = array('issn', 'status', 'publisher_id', 'modified', 'modified_by');
            $columnsNew = array_merge($columnsNewTemp, $columnsBase);
            $columnsArchiveTemp = array('id_old');
            $columnsArchive = array_merge($columnsArchiveTemp, $columnsBase);
            // Insert values     
            $valuesBase = array(
                $db->quote($publication['title']), $db->quote($publication['place_of_publication']), $db->quote($publication['printer']), $db->quote($publication['issued_from_year']),
                $db->quote($publication['issued_from_number']), $db->quote($publication['frequency']), $db->quote($publication['frequency_other']),
                $db->quote($publication['language']), $db->quote($publication['publication_type']), $db->quote($publication['publication_type_other']),
                $db->quote($publication['medium']), $db->quote($publication['medium_other']), $db->quote($publication['url']), $db->quote($publication['previous']), $db->quote($publication['main_series']),
                $db->quote($publication['subseries']), $db->quote($publication['another_medium']), $db->quote($publication['additional_info']), $db->quote($formId),
                $db->quote($publication['created']), $db->quote($publication['created_by'])
            );
            $valuesNewTemp = array($db->quote($publication['issn']), $db->quote($publication['status']), $db->quote($publisherId), $db->quote($publication['modified']), $db->quote($publication['modified_by']));
            $valuesNew = array_merge($valuesNewTemp, $valuesBase);
            $valuesArchiveTemp = array($id);
            $valuesArchive = array_merge($valuesArchiveTemp, $valuesBase);

            // Create a new query object.
            $query = $db->getQuery(true);
            // Prepare the insert query
            $query->insert($db->quoteName('#__issn_registry_publication'))
                    ->columns($db->quoteName($columnsNew))
                    ->values(implode(',', $valuesNew));
            // Set the query using our newly populated query object and execute it
            $db->setQuery($query);
            $db->execute();
            $newID = $db->insertid();

            // If publication was succesfully saved, add archive entry
            if ($newID > 0) {
                array_push($columnsArchive, 'publication_id');
                array_push($valuesArchive, $newID);
                // Create a new query object.
                $query = $db->getQuery(true);
                // Prepare the insert query
                $query->insert($db->quoteName('#__issn_registry_publication_archive'))
                        ->columns($db->quoteName($columnsArchive))
                        ->values(implode(',', $valuesArchive));
                $db->setQuery($query);
                $db->execute();
                if ($db->insertid() == 0) {
                    return 0;
                }
            }
            return $newID;
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function saveRanges(&$ranges) {
        // Start transaction
        JFactory::getDbo()->transactionStart();
        // Loop through ranges
        foreach ($ranges as $key => $value) {
            // Save range
            $id = self::saveRange($key, $value);
            if ($id == 0) {
                // If operation failed, do rollback
                JFactory::getDbo()->transactionRollback();
                return false;
            }
            // Set new id
            $ranges[$key]['id'] = $id;
        }
        // Commit transaction
        JFactory::getDbo()->transactionCommit();
        return true;
    }

    private static function saveRange($id, $range) {
        try {
            // database connection
            $db = JFactory::getDbo();
            // Insert columns
            $columns = array('block', 'range_begin', 'range_end', 'free', 'taken', 'next', 'is_active', 'is_closed', 'id_old', 'created', 'created_by');
            // Insert values      
            $values = array($db->quote($range['block']), $db->quote($range['range_begin']), $db->quote($range['range_end']), $db->quote($range['free']), $db->quote($range['taken']), $db->quote($range['next']), $db->quote($range['is_active']), $db->quote($range['is_closed']), $db->quote($id), $db->quote($range['created']), $db->quote($range['created_by']));

            // Create a new query object.
            $query = $db->getQuery(true);
            // Prepare the insert query
            $query->insert($db->quoteName('#__issn_registry_issn_range'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
            $db->setQuery($query);
            $db->execute();
            return $db->insertid();
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function saveIdentifiersUsed(&$identifiersUsed, &$ranges, &$publications) {
        // Start transaction
        JFactory::getDbo()->transactionStart();
        // Loop through identifiers
        foreach ($identifiersUsed as $key => $value) {
            $rangeId = 0;
            if (array_key_exists($value['issn_range_id'], $ranges)) {
                $rangeId = $ranges[$value['issn_range_id']]['id'];
                $identifiersUsed[$key]['issn_range_id'] = $ranges[$value['issn_range_id']]['id'];
            }
            $publicationId = 0;
            if (array_key_exists($value['publication_id'], $publications)) {
                $publicationId = $publications[$value['publication_id']]['id'];
                $identifiersUsed[$key]['publication_id'] = $publications[$value['publication_id']]['id'];
            }
            // Check that references are OK
            if ($rangeId == 0 || $publicationId == 0) {
                //JFactory::getDbo()->transactionRollback();
                //return false;
            }
            // Save identifier
            $id = self::saveIdentifierUsed($value, $rangeId, $publicationId);
            if ($id == 0) {
                // If operation failed, do rollback
                JFactory::getDbo()->transactionRollback();
                return false;
            }
            // Set new id
            $identifiersUsed[$key]['id'] = $id;
        }
        // Commit transaction
        JFactory::getDbo()->transactionCommit();
        return true;
    }

    private static function saveIdentifierUsed($identifier, $rangeId, $publicationId) {
        try {
            // database connection
            $db = JFactory::getDbo();
            // Insert columns
            $columns = array('issn', 'publication_id', 'issn_range_id', 'created', 'created_by');
            // Insert values      
            $values = array($db->quote($identifier['issn']), $db->quote($publicationId), $db->quote($rangeId), $db->quote($identifier['created']), $db->quote($identifier['created_by']));

            // Create a new query object.
            $query = $db->getQuery(true);
            // Prepare the insert query
            $query->insert($db->quoteName('#__issn_registry_issn_used'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
            // Set the query using our newly populated query object and execute it
            $db->setQuery($query);
            $db->execute();
            return $db->insertid();
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function deleteAll() {
        $tables = array(
            '#__issn_registry_form',
            '#__issn_registry_form_archive',
            '#__issn_registry_publication',
            '#__issn_registry_publication_archive',
            '#__issn_registry_publisher',
            '#__issn_registry_issn_range',
            '#__issn_registry_issn_used',
            '#__issn_registry_issn_canceled',
            '#__issn_registry_message'
        );
        // Get DB connection
        $db = JFactory::getDbo();
        // Start transaction
        JFactory::getDbo()->transactionStart();
        try {
            foreach ($tables as $table) {
                // Create query
                $query = $db->getQuery(true);
                // Set conditions
                $conditions = array(
                    $db->quoteName('id') . ' > ' . $db->quote(0)
                );

                $query->delete($db->quoteName($table));
                $query->where($conditions);

                $db->setQuery($query);

                $result = $db->execute();
            }
            // Commit transaction
            JFactory::getDbo()->transactionCommit();
            return true;
        } catch (Exception $e) {
            // If operation failed, do rollback
            JFactory::getDbo()->transactionRollback();
            return false;
        }
    }

}

?>