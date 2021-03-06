<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
 * @author 	Petteri Kivim�ki
 * @copyright	Copyright (C) 2016 Petteri Kivim�ki. All rights reserved.
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
        // Get current month
        $currentMonth = date('n');
        $html = '<select name="' . $this->name . '" id="' . $this->id . '" class="' . $this->class . '">';
        $html .= '<option value=""' . (strcmp($this->value, '') == 0 ? ' selected' : '') . '>' . JText::_('COM_ISSNREGISTRY_FIELD_SELECT') . '</option>';
        // Starting from September the next year must be added
        if ($currentMonth >= 9) {
            $html .= '<option value="' . ($currentYear + 1) . '"' . (($this->value == $currentYear + 1) ? ' selected' : '') . '>' . ($currentYear + 1) . '</option>';
        }
        for ($i = $currentYear; $i >= 1900; $i--) {
            $html .= '<option value="' . $i . '"' . (($this->value == $i) ? ' selected' : '') . '>' . $i . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

}
