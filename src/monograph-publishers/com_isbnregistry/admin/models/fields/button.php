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
 
class JFormFieldButton extends JFormField {
 
	protected $type = 'Button';
 
	// getLabel() left out
 
	public function getInput() {			   
		$html .= '<input type="button" name="'.$this->name.'" id="'.$this->id.'" class="'.$this->class.'" value="'.JText::_($this->default).'" />';
		return $html;
	}
}