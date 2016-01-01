<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JFormHelper::loadFieldClass('list');

/**
 * ISBN Range Form Field class for the ISMN Registry component
 *
 * @since  1.0.0
 */
class JFormFieldIsmnrange extends JFormFieldList {

    /**
     * The field type.
     *
     * @var         string
     */
    protected $type = 'Ismnrange';

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array  An array of JHtml options.
     */
    protected function getOptions() {
        $db = JFactory::getDBO();
		$conditions = array(
            $db->quoteName('is_active') . ' = ' . $db->quote(true),
			$db->quoteName('is_closed') . ' = ' . $db->quote(false)
        );		
        $query = $db->getQuery(true);
        $query->select('id,prefix,range_begin,range_end,free,next');
        $query->from('#__isbn_registry_ismn_range');
		$query->where($conditions);
		$query->order('prefix ASC, category ASC, range_begin ASC');
        $db->setQuery((string) $query);
        $ismnranges = $db->loadObjectList();
        $options = array('' => JText::_('COM_ISBNREGISTRY_FIELD_SELECT'));

        if ($ismnranges) {
            foreach ($ismnranges as $ismnrange) {
				$label = $ismnrange->prefix . ' : ' . $ismnrange->range_begin . ' - ' . $ismnrange->range_end;
				$label .= ', ' . JText::_('COM_ISBNREGISTRY_ISBN_RANGE_FIELD_FREE_LABEL') . ': ' . $ismnrange->free;
                $options[] = JHtml::_('select.option', $ismnrange->id, $label);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}
