<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
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
    protected $type = 'Donation';

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array  An array of JHtml options.
     */
    protected function getOptions() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,first_name,last_name');
        $query->from('#__isbn_registry_publisher');
        $db->setQuery((string) $query);
        $publishers = $db->loadObjectList();
        $options = array();

        if ($publishers) {
            foreach ($publishers as $publisher) {
                $options[] = JHtml::_('select.option', $publisher->id, $publisher->first_name, $publisher->last_name);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}
