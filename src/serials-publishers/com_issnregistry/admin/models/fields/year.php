<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldYear extends JFormField {

    protected $type = 'Year';

    // getLabel() left out

    public function getInput() {
        // Get current year
        $currentYear = date("Y");
        $html = '<select name="' . $this->name . '" id="' . $this->id . '" class="' . $this->class . '">';
        $html .= '<option value=""' . (strcmp($this->value, '') == 0 ? ' selected' : '') . '>' . JText::_('COM_ISSNREGISTRY_FIELD_SELECT') . '</option>';
        for ($i = $currentYear; $i >= 1900; $i--) {
            $html .= '<option value="' . $i . '"' . (($this->value == $i) ? ' selected' : '') . '>' . $i . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

}
