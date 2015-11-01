<?php

/**
 * @Plugin 	"ID Registry - Monograph Publishers - Forms"
 * @version 	1.0.0
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * */
defined('_JEXEC') or die('Restricted access');

class IdRegMonoPubFormsHtmlBuilder {
    public static function getRegisterMonographPublisherForm($errors = array()) {
		$html .= '<div class="form_header">' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_REGISTRATION_HEADER') . '</div>';
        $html .= '<div class="plg_id_reg_mono_pub_forms" id="plg_id_reg_mono_pub_forms_registration" >';
		$html .= '<div class="sub_title">' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_REGISTRATION_SUB_TITLE_1') . '</div>';
        $html .= '<form action="' . $_SERVER["REQUEST_URI"] . '" method="post" name="registerMonographPublisherForm" id="registerMonographPublisherForm">';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_OFFICIAL_NAME_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="official_name" id="official_name" size="30" value="' . $_POST['official_name'] . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['official_name']) . '</td>';
        $html .= '</tr><tr>';
		$html .= '<td>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_OTHER_NAMES_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="other_names" id="other_names" size="30" value="' . $_POST['other_names'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['other_names']) . '</td>';
        $html .= '</tr><tr>';		
        $html .= '<td>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_ADDRESS_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="address" id="address" size="30" value="' . $_POST['address'] . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['address']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_ZIP_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="zip" id="zip" size="5" value="' . $_POST['zip'] . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['zip']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CITY_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="city" id="city" size="20" value="' . $_POST['city'] . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['city']) . '</td>';
        $html .= '</tr><tr>';			
		$html .= '<td>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_PHONE_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="phone" id="phone" size="10" value="' . $_POST['phone'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['phone']) . '</td>';
        $html .= '</tr><tr>';
		$html .= '<td>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_FAX_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="fax" id="fax" size="10" value="' . $_POST['fax'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['fax']) . '</td>';
        $html .= '</tr><tr>';		
        $html .= '<td>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_EMAIL_FIELD') . ':</td>';
        // Content - Email Cloaking plugin must be disabled to get this work
        $html .= '<td><input type="text" id="email" name="email" size="30" value="' . $_POST['email'] . '"  maxlength="100"/></td>';
        $html .= '<td class="error">* ' . JText::_($errors['email']) . '</td>';
        $html .= '</tr><tr>';
		$html .= '<td>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_WWW_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="www" id="www" size="30" value="' . $_POST['www'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['www']) . '</td>';
        $html .= '</tr><tr>';		
		$html .= '<td>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CONTACT_PERSON_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="contact_person" id="contact_person" size="30" value="' . $_POST['contact_person'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['contact_person']) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
		$html .= '<div class="sub_title">' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_REGISTRATION_SUB_TITLE_2') . '</div>';
        $html .= '<div class="pre_field">' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTIONS_PRE_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_1_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_1" id="question_1" size="30" value="' . $_POST['question_1'] . '" /></div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_2_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_2" id="question_2" size="30" value="' . $_POST['question_2'] . '" /></div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_3_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_3" id="question_3" size="30" value="' . $_POST['question_3'] . '" /></div>';
        $html .= '<div class="field_info">' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_3_POST_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_4_FIELD') . '</div>';
        $html .= '<div><textarea name="question_4" id="question_4">' . $_POST['question_4'] . '</textarea></div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_5_FIELD') . '</div>';
        $html .= '<div><textarea name="question_5" id="question_5">' . $_POST['question_5'] . '</textarea></div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_6_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_6" id="question_6" size="30" value="' . $_POST['question_6'] . '" /></div>';
        $html .= '<div class="pre_field">' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_7_PRE_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_7_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_7" id="question_7" size="30" value="' . $_POST['question_7'] . '" /></div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_8_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_8" id="question_8" size="30" value="' . $_POST['question_8'] . '" /></div>';
        $html .= '<div class="pre_field">' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CONFIRMATION_PRE_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CONFIRMATION_FIELD') . '<span  class="error"> *</span></div>';
        $html .= '<div><input type="text" name="confirmation" id="confirmation" size="30" value="' . $_POST['confirmation'] . '" />';
        $html .= '<span  class="error">' . JText::_($errors['confirmation']) . '</span></div>';
        $html .= '<div class="field_info">' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CONFIRMATION_POST_FIELD') . '</div>';
        $html .= '<div><input type="submit" name="submit_registration" value="' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_SEND_BTN') . '" /></div>';
        $html .= JHTML::_('form.token');
        $html .= '</form>';
        return $html;
    }
}

?>