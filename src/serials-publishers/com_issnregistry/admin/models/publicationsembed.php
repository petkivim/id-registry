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
 * PublicationsEmbed Model
 *
 * @since  1.0.0
 */
class IssnregistryModelPublicationsEmbed extends JModelList {

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery() {
        // Initialize variables.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Get publisher id URL parameter
        $publisherId = JFactory::getApplication()->input->getInt('publisherId');
        // Get form id URL parameter
        $formId = JFactory::getApplication()->input->getInt('formId');

        // Create the base select statement.
        $query->select('*')
                ->from($db->quoteName('#__issn_registry_publication'));

        // If publisher id is not null, the results are shown in edit 
        // publisher view.
        if ($publisherId != null) {
            $query->where($db->quoteName('publisher_id') . ' = ' . $db->quote($publisherId));
            $query->where('status != "NO_ISSN_GRANTED"');
            $query->order('title ASC');
        } else if ($formId != null) {
            $query->where($db->quoteName('form_id') . ' = ' . $db->quote($formId));
            $query->order('id DESC');
        }

        return $query;
    }

}
