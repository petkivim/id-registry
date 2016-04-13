<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 		Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldYear extends JFormField {

    protected $type = 'Year';

    // getLabel() left out

    public function getInput() {
        $html = '<select name="' . $this->name . '" id="' . $this->id . '" class="' . $this->class . '">';
        $html .= '<option value=""' . (strcmp($this->value, '') == 0 ? ' selected' : '') . '>' . JText::_('COM_ISBNREGISTRY_FIELD_SELECT') . '</option>';
        $html .= '<option value="' . (date("Y") - 2) . '"' . ($this->value == (date("Y") - 2) ? ' selected' : '') . '>' . (date("Y") - 2) . '</option>';
        $html .= '<option value="' . (date("Y") - 1) . '"' . ($this->value == (date("Y") - 1) ? ' selected' : '') . '>' . (date("Y") - 1) . '</option>';
        $html .= '<option value="' . (date("Y")) . '"' . ($this->value == date("Y") ? ' selected' : '') . '>' . (date("Y")) . '</option>';
        $html .= '<option value="' . (date("Y") + 1) . '"' . ($this->value == (date("Y") + 1) ? ' selected' : '') . '>' . (date("Y") + 1) . '</option>';
        $html .= '<option value="' . (date("Y") + 2) . '"' . ($this->value == (date("Y") + 2) ? ' selected' : '') . '>' . (date("Y") + 2) . '</option>';
        $html .= '</select>';
        return $html;
    }

}
