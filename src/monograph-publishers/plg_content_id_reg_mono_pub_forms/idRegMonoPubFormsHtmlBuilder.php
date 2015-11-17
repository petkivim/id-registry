<?php

/**
 * @Plugin 	"ID Registry - Monograph Publishers - Forms"
 * @version 	1.0.0
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * */
defined('_JEXEC') or die('Restricted access');

class IdRegMonoPubFormsHtmlBuilder {

    public static function getRegisterMonographPublisherForm($langTag, $errors = array()) {
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
        $html .= '<div><input type="text" name="question_1" id="question_1" class="question" size="30" value="' . $_POST['question_1'] . '" />';
		$html .= '<span class="error">' . JText::_($errors['question_1']) . '</span></div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_2_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_2" id="question_2" class="question" size="30" value="' . $_POST['question_2'] . '" />';
		$html .= '<span class="error">' . JText::_($errors['question_2']) . '</span></div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_3_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_3" id="question_3" class="question" size="30" value="' . $_POST['question_3'] . '" />';
		$html .= '<span class="error">' . JText::_($errors['question_3']) . '</span></div>';
        $html .= '<div class="field_info">' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_3_POST_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_4_FIELD') . '</div>';
        $html .= '<div><textarea name="question_4" id="question_4" class="question">' . $_POST['question_4'] . '</textarea>';
		$html .= '<span class="error">' . JText::_($errors['question_4']) . '</span></div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_5_FIELD') . '</div>';
        $html .= '<div><textarea name="question_5" id="question_5" class="question">' . $_POST['question_5'] . '</textarea>';
		$html .= '<span class="error">' . JText::_($errors['question_5']) . '</span></div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_6_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_6" id="question_6" class="question" size="30" value="' . $_POST['question_6'] . '" />';
		$html .= '<span  class="error">' . JText::_($errors['question_6']) . '</span></div>';
        $html .= '<div class="pre_field">' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_7_PRE_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_7_FIELD') . '</div>';
		$html .= IdRegMonoPubFormsHtmlBuilder::getClassificationMenu($errors);
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_QUESTION_8_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_8" id="question_8" class="question" size="30" value="' . $_POST['question_8'] . '" />';
		$html .= '<span class="error">' . JText::_($errors['question_8']) . '</span></div>';
        $html .= '<div class="pre_field">' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CONFIRMATION_PRE_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CONFIRMATION_FIELD') . '<span  class="error"> *</span></div>';
        $html .= '<div><input type="text" name="confirmation" id="confirmation" size="30" value="' . $_POST['confirmation'] . '" />';
        $html .= '<span  class="error">' . JText::_($errors['confirmation']) . '</span></div>';
        $html .= '<div class="field_info">' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CONFIRMATION_POST_FIELD') . '</div>';
        $html .= '<div><input type="submit" name="submit_registration" value="' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_SEND_BTN') . '" /></div>';
		$html .= '<input type="hidden" name="lang_code" value="' . $langTag . '" />';
        $html .= JHTML::_('form.token');
        $html .= '</form>';
        return $html;
    }

	public static function getClassificationMenu($errors = array()) {
		$html .= '<div>';
		$html .= '<select name="question_7[]" data-placeholder="' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_SELECT') . '" multiple="multiple" id="question_7">';
		$html .= '<option value="000"' . (in_array('000', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_000') . '</option>';
		$html .= '<option value="015"' . (in_array('015', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_015') . '</option>';
		$html .= '<option value="030"' . (in_array('030', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_030') . '</option>';
		$html .= '<option value="035"' . (in_array('035', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_035') . '</option>';
		$html .= '<option value="040"' . (in_array('040', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_040') . '</option>';
		$html .= '<option value="045"' . (in_array('045', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_045') . '</option>';
		$html .= '<option value="050"' . (in_array('050', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_050') . '</option>';
		$html .= '<option value="055"' . (in_array('055', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_055') . '</option>';
		$html .= '<option value="100"' . (in_array('100', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_100') . '</option>';
		$html .= '<option value="120"' . (in_array('120', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_120') . '</option>';
		$html .= '<option value="130"' . (in_array('130', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_130') . '</option>';
		$html .= '<option value="200"' . (in_array('200', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_200') . '</option>';
		$html .= '<option value="210"' . (in_array('210', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_210') . '</option>';
		$html .= '<option value="211"' . (in_array('211', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_211') . '</option>';
		$html .= '<option value="270"' . (in_array('270', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_270') . '</option>';
		$html .= '<option value="300"' . (in_array('300', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_300') . '</option>';
		$html .= '<option value="310"' . (in_array('310', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_310') . '</option>';
		$html .= '<option value="315"' . (in_array('315', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_315') . '</option>';
		$html .= '<option value="316"' . (in_array('316', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_316') . '</option>';
		$html .= '<option value="320"' . (in_array('320', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_320') . '</option>';
		$html .= '<option value="330"' . (in_array('330', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_330') . '</option>';
		$html .= '<option value="340"' . (in_array('340', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_340') . '</option>';
		$html .= '<option value="350"' . (in_array('350', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_350') . '</option>';
		$html .= '<option value="370"' . (in_array('370', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_370') . '</option>';
		$html .= '<option value="375"' . (in_array('375', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_375') . '</option>';
		$html .= '<option value="380"' . (in_array('380', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_380') . '</option>';
		$html .= '<option value="390"' . (in_array('390', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_390') . '</option>';
		$html .= '<option value="400"' . (in_array('400', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_400') . '</option>';
		$html .= '<option value="410"' . (in_array('410', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_410') . '</option>';
		$html .= '<option value="420"' . (in_array('420', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_420') . '</option>';
		$html .= '<option value="440"' . (in_array('440', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_440') . '</option>';
		$html .= '<option value="450"' . (in_array('450', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_450') . '</option>';
		$html .= '<option value="460"' . (in_array('460', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_460') . '</option>';
		$html .= '<option value="470"' . (in_array('470', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_470') . '</option>';
		$html .= '<option value="480"' . (in_array('480', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_480') . '</option>';
		$html .= '<option value="490"' . (in_array('490', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_490') . '</option>';
		$html .= '<option value="500"' . (in_array('500', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_500') . '</option>';
		$html .= '<option value="510"' . (in_array('510', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_510') . '</option>';
		$html .= '<option value="520"' . (in_array('520', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_520') . '</option>';
		$html .= '<option value="530"' . (in_array('530', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_530') . '</option>';
		$html .= '<option value="540"' . (in_array('540', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_540') . '</option>';
		$html .= '<option value="550"' . (in_array('550', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_550') . '</option>';
		$html .= '<option value="560"' . (in_array('560', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_560') . '</option>';
		$html .= '<option value="570"' . (in_array('570', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_570') . '</option>';
		$html .= '<option value="580"' . (in_array('580', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_580') . '</option>';
		$html .= '<option value="590"' . (in_array('590', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_590') . '</option>';
		$html .= '<option value="600"' . (in_array('600', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_600') . '</option>';
		$html .= '<option value="610"' . (in_array('610', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_610') . '</option>';
		$html .= '<option value="620"' . (in_array('620', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_620') . '</option>';
		$html .= '<option value="621"' . (in_array('621', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_621') . '</option>';
		$html .= '<option value="622"' . (in_array('622', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_622') . '</option>';
		$html .= '<option value="630"' . (in_array('630', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_630') . '</option>';
		$html .= '<option value="640"' . (in_array('640', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_640') . '</option>';
		$html .= '<option value="650"' . (in_array('650', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_650') . '</option>';
		$html .= '<option value="660"' . (in_array('660', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_660') . '</option>';
		$html .= '<option value="670"' . (in_array('670', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_670') . '</option>';
		$html .= '<option value="672"' . (in_array('672', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_672') . '</option>';
		$html .= '<option value="680"' . (in_array('680', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_680') . '</option>';
		$html .= '<option value="690"' . (in_array('690', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_690') . '</option>';
		$html .= '<option value="700"' . (in_array('700', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_700') . '</option>';
		$html .= '<option value="710"' . (in_array('710', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_710') . '</option>';
		$html .= '<option value="720"' . (in_array('720', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_720') . '</option>';
		$html .= '<option value="730"' . (in_array('730', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_730') . '</option>';
		$html .= '<option value="740"' . (in_array('740', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_740') . '</option>';
		$html .= '<option value="750"' . (in_array('750', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_750') . '</option>';
		$html .= '<option value="760"' . (in_array('760', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_760') . '</option>';
		$html .= '<option value="765"' . (in_array('765', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_765') . '</option>';
		$html .= '<option value="770"' . (in_array('770', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_770') . '</option>';
		$html .= '<option value="780"' . (in_array('780', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_780') . '</option>';
		$html .= '<option value="790"' . (in_array('790', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_790') . '</option>';
		$html .= '<option value="800"' . (in_array('800', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_800') . '</option>';
		$html .= '<option value="810"' . (in_array('810', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_810') . '</option>';
		$html .= '<option value="820"' . (in_array('820', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_820') . '</option>';
		$html .= '<option value="830"' . (in_array('830', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_830') . '</option>';
		$html .= '<option value="840"' . (in_array('840', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_840') . '</option>';
		$html .= '<option value="850"' . (in_array('850', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_850') . '</option>';
		$html .= '<option value="860"' . (in_array('860', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_860') . '</option>';
		$html .= '<option value="870"' . (in_array('870', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_870') . '</option>';
		$html .= '<option value="880"' . (in_array('880', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_880') . '</option>';
		$html .= '<option value="890"' . (in_array('890', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_890') . '</option>';
		$html .= '<option value="900"' . (in_array('900', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_900') . '</option>';
		$html .= '<option value="910"' . (in_array('910', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_910') . '</option>';
		$html .= '<option value="920"' . (in_array('920', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_920') . '</option>';
		$html .= '<option value="930"' . (in_array('930', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_930') . '</option>';
		$html .= '<option value="940"' . (in_array('940', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_940') . '</option>';
		$html .= '<option value="950"' . (in_array('950', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ID_REG_MONO_PUB_FORMS_CLASS_950') . '</option>';
		$html .= '</select><span class="error">' . JText::_($errors['question_7']) . '</span></div>';
		return $html;		
	}
}

?>