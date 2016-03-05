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
 * Message type Form Field class for the ISSN Registry component
 *
 * @since  1.0.0
 */
class JFormFieldMessagetype extends JFormFieldList {

    /**
     * The field type.
     *
     * @var         string
     */
    protected $type = 'MessageType';

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array  An array of JHtml options.
     */
    protected function getOptions() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('id,name');
        $query->from('#__issn_registry_message_type');
	$query->order('name ASC');
        $db->setQuery((string) $query);
        $msgTypes = $db->loadObjectList();
        $options = array('' => JText::_('COM_ISSNREGISTRY_FIELD_SELECT'));

        if ($msgTypes) {
            foreach ($msgTypes as $msgType) {
                $options[] = JHtml::_('select.option', $msgType->id, $msgType->name);
            }
        }

        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }

}
