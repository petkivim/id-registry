<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
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

    private static $createdBy = 'SYSTEM';

    public static function savePublishers(&$publishers) {
        // Start transaction
        JFactory::getDbo()->transactionStart();
        // Loop through publishers
        foreach ($publishers as $key => $value) {
            // Save publisher
            $id = self::savePublisher($key, $value);
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

    private static function savePublisher($id, $publisher) {
        try {
            // database connection
            $db = JFactory::getDbo();
            // Insert columns
            $columns = array('official_name', 'other_names', 'lang_code', 'created', 'created_by', 'address', 'zip', 'city', 'phone', 'email', 'contact_person', 'www', 'question_7', 'confirmation');
            $columnsNewTemp = array('previous_names', 'year_quitted', 'has_quitted', 'modified', 'modified_by', 'additional_info');
            $columnsNew = array_merge($columnsNewTemp, $columns);
            $columnsArchiveTemp = array('id_old');
            $columnsArchive = array_merge($columnsArchiveTemp, $columns);
            // Insert values
            $values = array($db->quote($publisher['official_name']), $db->quote($publisher['other_names']), $db->quote($publisher['lang_code']), $db->quote($publisher['created']), $db->quote($publisher['created_by']), $db->quote($publisher['address']), $db->quote($publisher['zip']), $db->quote($publisher['city']), $db->quote($publisher['phone']), $db->quote($publisher['email']), $db->quote($publisher['contact_person']), $db->quote($publisher['www']), $db->quote($publisher['question_7']), $db->quote(''));
            $valuesNewTemp = array($db->quote($publisher['previous_names']), $db->quote($publisher['year_quitted']), $publisher['has_quitted'] ? 'true' : 'false', $db->quote($publisher['modified']), $db->quote($publisher['modified_by']), $db->quote($publisher['additional_info']));
            $valuesNew = array_merge($valuesNewTemp, $values);
            $valuesArchiveTemp = array($id);
            $valuesArchive = array_merge($valuesArchiveTemp, $values);
            // Create a new query object.
            $query = $db->getQuery(true);
            // Prepare the insert query
            $query->insert($db->quoteName('#__isbn_registry_publisher'))
                    ->columns($db->quoteName($columnsNew))
                    ->values(implode(',', $valuesNew));
            // Set the query using our newly populated query object and execute it
            $db->setQuery($query);
            $db->execute();
            $publisherID = $db->insertid();

            // If publisher was succesfully saved, add archive entry
            if ($publisherID > 0) {
                array_push($columnsArchive, 'publisher_id');
                array_push($valuesArchive, $publisherID);
                // Create a new query object.
                $query = $db->getQuery(true);
                // Prepare the insert query
                $query->insert($db->quoteName('#__isbn_registry_publisher_archive'))
                        ->columns($db->quoteName($columnsArchive))
                        ->values(implode(',', $valuesArchive));
                $db->setQuery($query);
                $db->execute();
                if ($db->insertid() == 0) {
                    return 0;
                }
            }
            return $publisherID;
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function deletePublishers() {
        try {
            // Get DB connection
            $db = JFactory::getDbo();
            // Create query
            $query = $db->getQuery(true);
            // Set conditions
            $conditions = array(
                $db->quoteName('id') . ' > ' . $db->quote(0)
            );

            $query->delete($db->quoteName('#__isbn_registry_publisher'));
            $query->where($conditions);

            $db->setQuery($query);

            $result = $db->execute();

            // Create new query
            $query = $db->getQuery(true);

            $query->delete($db->quoteName('#__isbn_registry_publisher_archive'));
            $query->where($conditions);

            $db->setQuery($query);

            $result = $db->execute();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function saveIdentifierRanges(&$ranges, $ismn = false) {
        // Start transaction
        JFactory::getDbo()->transactionStart();
        // Loop through ranges
        foreach ($ranges as $key => $value) {
            // Save range
            $id = self::saveIdentifierRange($key, $value, $ismn);
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

    private static function saveIdentifierRange($id, $range, $ismn = false) {
        try {
            $table = $ismn ? '#__isbn_registry_ismn_range' : '#__isbn_registry_isbn_range';
            // database connection
            $db = JFactory::getDbo();
            // Created info
            $created = JFactory::getDate();
            // Insert columns
            $columns = array('prefix', 'category', 'range_begin', 'range_end', 'free', 'taken', 'canceled', 'next', 'is_active', 'is_closed', 'id_old', 'created', 'created_by');
            // Insert values
            $values = array($db->quote($range['prefix']), $db->quote($range['category']), $db->quote($range['range_begin']), $db->quote($range['range_end']), $db->quote($range['free']), $db->quote($range['taken']), $db->quote($range['canceled']), $db->quote($range['next']), $db->quote($range['is_active']), $db->quote($range['is_closed']), $db->quote($id), $db->quote($created->toSql()), $db->quote(self::$createdBy));
            // Is ISMN range?
            if (!$ismn) {
                array_push($columns, 'lang_group');
                array_push($values, $db->quote($range['lang_group']));
            }
            // Create a new query object.
            $query = $db->getQuery(true);
            // Prepare the insert query
            $query->insert($db->quoteName($table))
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

    public static function deleteIdentifierRanges($ismn) {
        try {
            // Is ISMN?
            $table = $ismn ? '#__isbn_registry_ismn_range' : '#__isbn_registry_isbn_range';

            // Get DB connection
            $db = JFactory::getDbo();
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

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function savePublisherIdentifierRangesCanceled(&$canceledRanges, $ranges, $ismn = false) {
        // Start transaction
        JFactory::getDbo()->transactionStart();
        // Loop through ranges
        foreach ($canceledRanges as $key => $value) {
            // Get old class id
            $classId = $value['range_id'];
            // Set new range id
            $value['range_id'] = $ranges[$classId]['id'];
            $canceledRanges[$key]['range_id'] = $ranges[$classId]['id'];
            // Check that we have a new id
            if ($ranges[$classId]['id'] == 0) {
                return 0;
            }
            // Save range
            $id = self::savePublisherIdentifierRangeCanceled($key, $value, $ismn);
            if ($id == 0) {
                // If operation failed, do rollback
                JFactory::getDbo()->transactionRollback();
                return false;
            }
            // Set new id
            $canceledRanges[$key]['id'] = $id;
        }
        // Commit transaction
        JFactory::getDbo()->transactionCommit();
        return true;
    }

    private static function savePublisherIdentifierRangeCanceled($id, $range, $ismn = false) {
        try {
            $table = $ismn ? '#__isbn_registry_publisher_ismn_range_canceled' : '#__isbn_registry_publisher_isbn_range_canceled';
            // database connection
            $db = JFactory::getDbo();
            // Created info
            $created = JFactory::getDate();
            // Insert columns
            $columns = array('identifier', 'category', 'range_id', 'id_old', 'canceled', 'canceled_by');
            // Insert values
            $values = array($db->quote($range['identifier']), $db->quote($range['category']), $range['range_id'], $id, $db->quote($created->toSql()), $db->quote(self::$createdBy));
            // Create a new query object.
            $query = $db->getQuery(true);
            // Prepare the insert query
            $query->insert($db->quoteName($table))
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

    public static function deletePublisherIdentifierRangesCanceled($ismn) {
        try {
            // Is ISMN?
            $table = $ismn ? '#__isbn_registry_publisher_ismn_range_canceled' : '#__isbn_registry_publisher_isbn_range_canceled';

            // Get DB connection
            $db = JFactory::getDbo();
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

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function savePublisherIdentifierRangesUsed(&$usedRanges, $ranges, $publishers, $ismn = false) {
        // Start transaction
        JFactory::getDbo()->transactionStart();
        // Loop through ranges
        foreach ($usedRanges as $key => $value) {
            $rangeIdLabel = $ismn ? 'ismn_range_id' : 'isbn_range_id';
            // Get old class id
            $classId = $value[$rangeIdLabel];
            // Set new range id
            $value[$rangeIdLabel] = $ranges[$classId]['id'];
            $usedRanges[$key][$rangeIdLabel] = $ranges[$classId]['id'];
            // Get old publisher id
            $oldPublisherId = $value['publisher_id'];
            // If publisher id is not set or matching publisher is not found,
            // assign the identifier to publisher which id is 1
            if (empty($oldPublisherId)) {
                $oldPublisherId = 1;
            } else if (!isset($publishers[$oldPublisherId])) {
                $oldPublisherId = 1;
            }
            // Set publisher id
            $value['publisher_id'] = $publishers[$oldPublisherId]['id'];
            $usedRanges[$key]['publisher_id'] = $publishers[$oldPublisherId]['id'];
            // Check that we have a ids
            if ($ranges[$classId]['id'] == 0 || $publishers[$oldPublisherId]['id'] == 0) {
                return 0;
            }
            // Save range
            $id = self::savePublisherIdentifierRangeUsed($key, $value, $ismn);
            if ($id == 0) {
                // If operation failed, do rollback
                JFactory::getDbo()->transactionRollback();
                return false;
            }
            // Set new id
            $usedRanges[$key]['id'] = $id;
        }
        // Commit transaction
        JFactory::getDbo()->transactionCommit();
        return true;
    }

    private static function savePublisherIdentifierRangeUsed($id, $range, $ismn = false) {
        try {
            $rangeIdLabel = $ismn ? 'ismn_range_id' : 'isbn_range_id';
            $table = $ismn ? '#__isbn_registry_publisher_ismn_range' : '#__isbn_registry_publisher_isbn_range';
            // database connection
            $db = JFactory::getDbo();
            // Insert columns
            $columns = array('publisher_identifier', 'publisher_id', $rangeIdLabel, 'category', 'range_begin', 'range_end', 'free', 'taken', 'canceled', 'next', 'is_active', 'is_closed', 'id_old', 'created', 'created_by');
            // Insert values
            $values = array($db->quote($range['publisher_identifier']), $range['publisher_id'], $range[$rangeIdLabel], $range['category'], $db->quote($range['range_begin']), $db->quote($range['range_end']), $range['free'], $range['taken'], $range['canceled'], $db->quote($range['next']), $range['is_active'] ? 'true' : 'false', $range['is_closed'] ? 'true' : 'false', $id, $db->quote($range['created']), $db->quote($range['created_by']));
            // Create a new query object.
            $query = $db->getQuery(true);
            // Prepare the insert query
            $query->insert($db->quoteName($table))
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

    public static function deletePublisherIdentifierRangesUsed($ismn) {
        try {
            // Is ISMN?
            $table = $ismn ? '#__isbn_registry_publisher_ismn_range' : '#__isbn_registry_publisher_isbn_range';

            // Get DB connection
            $db = JFactory::getDbo();
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

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function deleteOther() {
        // Get DB connection
        $db = JFactory::getDbo();
        // Start transaction
        JFactory::getDbo()->transactionStart();
        try {
            // Create query
            $query = $db->getQuery(true);
            // Set conditions
            $conditions = array(
                $db->quoteName('id') . ' > ' . $db->quote(0)
            );

            $query->delete($db->quoteName('#__isbn_registry_publication'));
            $query->where($conditions);

            $db->setQuery($query);

            $result = $db->execute();

            // Create new query
            $query = $db->getQuery(true);

            $query->delete($db->quoteName('#__isbn_registry_message'));
            $query->where($conditions);

            $db->setQuery($query);

            $result = $db->execute();

            // Create new query
            $query = $db->getQuery(true);

            $query->delete($db->quoteName('#__isbn_registry_identifier_batch'));
            $query->where($conditions);

            $db->setQuery($query);

            $result = $db->execute();

            // Create new query
            $query = $db->getQuery(true);

            $query->delete($db->quoteName('#__isbn_registry_identifier'));
            $query->where($conditions);

            $db->setQuery($query);

            $result = $db->execute();

            // Create new query
            $query = $db->getQuery(true);

            $query->delete($db->quoteName('#__isbn_registry_identifier_canceled'));
            $query->where($conditions);

            $db->setQuery($query);

            $result = $db->execute();

            // Create new query
            $query = $db->getQuery(true);

            $query->delete($db->quoteName('#__isbn_registry_group_message'));
            $query->where($conditions);

            $db->setQuery($query);

            $result = $db->execute();

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