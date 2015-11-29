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
 * Publication Form Field class for the ISBN Registry component
 *
 * @since  1.0.0
 */
class JFormFieldPublication extends JFormFieldList {

    /**
     * The field type.
     *
     * @var         string
     */
    protected $type = 'Publication';

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array  An array of JHtml options.
     */
    protected function getOptions() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,official_name');
        $query->from('#__isbn_registry_publication');
        $db->setQuery((string) $query);
        $publications = $db->loadObjectList();
        $options = array();

        if ($publications) {
            foreach ($publications as $publication) {
                $options[] = JHtml::_('select.option', $publication->id, $publication->official_name);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}
