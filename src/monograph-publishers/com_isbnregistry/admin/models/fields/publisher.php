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
        $query->from('#__isbn_registry_publisher');
        $db->setQuery((string) $query);
        $publishers = $db->loadObjectList();
        $options = array();

        if ($publishers) {
            foreach ($publishers as $publisher) {
                $options[] = JHtml::_('select.option', $publisher->id, $publisher->official_name);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}
