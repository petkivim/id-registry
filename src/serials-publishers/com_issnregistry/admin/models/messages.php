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
 * Messages Model
 *
 * @since  1.0.0
 */
class IssnregistryModelMessages extends JModelList {

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery() {
        // Get publisher id URL parameter
        $publisherId = JFactory::getApplication()->input->getInt('publisherId');
        // Get form id URL parameter
        $formId = JFactory::getApplication()->input->getInt('formId');
        // Get group message id URL parameter
        $groupMessageId = JFactory::getApplication()->input->getInt('groupMessageId');
        // Initialize variables.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Create the base select statement.
        $query->select('*')
                ->from($db->quoteName('#__issn_registry_message'));
        // If publisher id is not null, add where clause and
        // show only publications that belong to the given publisher
        if ($publisherId != null) {
            $query->where($db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId));
        } else if ($groupMessageId != null) {
            $query->where($db->quoteName('group_message_id') . ' = ' . $db->quote($groupMessageId));
        } else if ($formId != null) {
            $query->where($db->quoteName('form_id') . ' = ' . $db->quote($formId));
        }
        $query->order('sent DESC');

        return $query;
    }

}
