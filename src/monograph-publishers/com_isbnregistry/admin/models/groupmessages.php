<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Group Messages Model
 *
 * @since  1.0.0
 */
class IsbnregistryModelGroupmessages extends JModelList
{
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select(
                        'gm.id, gm.isbn_categories, gm.isbn_publishers_count, '
                        . 'gm.ismn_categories, gm.ismn_publishers_count, '
                        . 'gm.has_quitted, gm.publishers_count, gm.created, gm.created_by, mt.name')
			  ->from($db->quoteName('#__isbn_registry_group_message') . ' AS gm')
			  ->join('INNER', '#__isbn_registry_message_type AS mt ON mt.id = gm.message_type_id')
			  ->order('gm.created DESC');

		return $query;
	}
}
