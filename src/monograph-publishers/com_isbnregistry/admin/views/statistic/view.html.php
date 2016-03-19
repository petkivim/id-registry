<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Statistic View
 *
 * @since  1.0.0
 */
class IsbnregistryViewStatistic extends JViewLegacy {

    protected $form = null;
    protected $item = null;
    protected $state = null;

    /**
     * Display the Statistic view
     *
     * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
     *
     * @return  void
     */
    function display($tpl = null) {
        // Get the Data
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));

            return false;
        }

        // Get component parameters
        $params = JComponentHelper::getParams('com_isbnregistry');
        // Get statistics file format
        $format = $params->get('statistics_format', 'XLS');
        // Send format to template
        $this->format = strtolower($format);
        
        // Add jQuery
        JHtml::_('jquery.framework');
        // Display the template
        parent::display($tpl);
    }

}
