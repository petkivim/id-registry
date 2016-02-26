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

JFormHelper::loadFieldClass('list');

/**
 * Publisher Form Field class for the ISBN Registry component
 *
 * @since  1.0.0
 */
class JFormFieldPublisher extends JFormFieldList {

    /**
     * The field type.
     *
     * @var         string
     */
    protected $type = 'Publisher';

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array  An array of JHtml options.
     */
    protected function getOptions() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,official_name');
        $query->from('#__issn_registry_publisher');
		$query->order('official_name ASC');
        $db->setQuery((string) $query);
        $publishers = $db->loadObjectList();
        $options = array('' => JText::_('COM_ISSNREGISTRY_FIELD_SELECT'));

        if ($publishers) {
            foreach ($publishers as $publisher) {
                $options[] = JHtml::_('select.option', $publisher->id, $publisher->official_name);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}
