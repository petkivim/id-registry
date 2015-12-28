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
 
class JFormFieldSpan extends JFormField {
 
	protected $type = 'Span';
 
	public function getLabel()
	{
		return parent::getLabel();
	}
 
	public function getInput() {			   
		$html .= '<span id="'.$this->id.'" class="'.$this->class.'">'.JText::_($this->default).'</span>';
		return $html;
	}
}