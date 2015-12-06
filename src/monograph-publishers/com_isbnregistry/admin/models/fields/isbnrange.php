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
 * ISBN Range Form Field class for the ISBN Registry component
 *
 * @since  1.0.0
 */
class JFormFieldIsbnrange extends JFormFieldList {

    /**
     * The field type.
     *
     * @var         string
     */
    protected $type = 'Isbnrange';

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array  An array of JHtml options.
     */
    protected function getOptions() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,prefix,lang_group,range_begin,range_end,free,next');
        $query->from('#__isbn_registry_isbn_range');
		$query->where('is_active = true');
		$query->order('prefix ASC, lang_group ASC, category ASC, range_begin ASC');
        $db->setQuery((string) $query);
        $isbnranges = $db->loadObjectList();
        $options = array('' => JText::_('COM_ISBNREGISTRY_FIELD_SELECT'));

        if ($isbnranges) {
            foreach ($isbnranges as $isbnrange) {
				$label = $isbnrange->prefix > 0 ? $isbnrange->prefix . '-' : '';
				$label .= $isbnrange->lang_group . ' : ' . $isbnrange->range_begin . ' - ' . $isbnrange->range_end;
				$label .= ', ' . JText::_('COM_ISBNREGISTRY_ISBN_RANGE_FIELD_FREE_LABEL') . ': ' . $isbnrange->free;
                $options[] = JHtml::_('select.option', $isbnrange->id, $label);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}
