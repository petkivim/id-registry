<?php

/**
 * @Plugin 	"ID Registry - Monograph Publishers - Forms"
 * @version 	1.0.0
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * */
defined('_JEXEC') or die('Restricted access');

class IsbnregistryFormsHtmlBuilder {

    public static function getRegisterMonographPublisherForm($errors = array()) {
        $html .= '<div class="form_header">' . JText::_('PLG_ISBNREGISTRY_FORMS_REGISTRATION_HEADER') . '</div>';
        $html .= '<div class="plg_isbnregistry_forms" id="plg_isbnregistry_forms_registration" >';
        $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_REGISTRATION_SUB_TITLE_1') . '</div>';
        $html .= '<form action="' . $_SERVER["REQUEST_URI"] . '" method="post" name="registerMonographPublisherForm" id="registerMonographPublisherForm">';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_OFFICIAL_NAME_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="official_name" id="official_name" size="30" value="' . $_POST['official_name'] . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['official_name']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_OTHER_NAMES_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="other_names" id="other_names" size="30" value="' . $_POST['other_names'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['other_names']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ADDRESS_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="address" id="address" size="30" value="' . $_POST['address'] . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['address']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ZIP_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="zip" id="zip" size="5" value="' . $_POST['zip'] . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['zip']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_CITY_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="city" id="city" size="20" value="' . $_POST['city'] . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['city']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PHONE_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="phone" id="phone" size="10" value="' . $_POST['phone'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['phone']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_EMAIL_FIELD') . ':</td>';
        // Content - Email Cloaking plugin must be disabled to get this work
        $html .= '<td><input type="text" id="email" name="email" size="30" value="' . $_POST['email'] . '"  maxlength="100"/></td>';
        $html .= '<td class="error">* ' . JText::_($errors['email']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_WWW_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="www" id="www" size="30" value="' . $_POST['www'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['www']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_CONTACT_PERSON_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="contact_person" id="contact_person" size="30" value="' . $_POST['contact_person'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['contact_person']) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_REGISTRATION_SUB_TITLE_2') . '</div>';
        $html .= '<div class="pre_field">' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTIONS_PRE_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_1_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_1" id="question_1" class="question" size="30" value="' . $_POST['question_1'] . '" />';
        $html .= '<span class="error">' . JText::_($errors['question_1']) . '</span></div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_2_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_2" id="question_2" class="question" size="30" value="' . $_POST['question_2'] . '" />';
        $html .= '<span class="error">' . JText::_($errors['question_2']) . '</span></div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_3_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_3" id="question_3" class="question" size="30" value="' . $_POST['question_3'] . '" />';
        $html .= '<span class="error">' . JText::_($errors['question_3']) . '</span></div>';
        $html .= '<div class="field_info">' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_3_POST_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_4_FIELD') . '</div>';
        $html .= '<div><textarea name="question_4" id="question_4" class="question">' . $_POST['question_4'] . '</textarea>';
        $html .= '<span class="error">' . JText::_($errors['question_4']) . '</span></div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_5_FIELD') . '</div>';
        $html .= '<div><textarea name="question_5" id="question_5" class="question">' . $_POST['question_5'] . '</textarea>';
        $html .= '<span class="error">' . JText::_($errors['question_5']) . '</span></div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_6_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_6" id="question_6" class="question" size="30" value="' . $_POST['question_6'] . '" />';
        $html .= '<span  class="error">' . JText::_($errors['question_6']) . '</span></div>';
        $html .= '<div class="pre_field">' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_7_PRE_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_7_FIELD') . '</div>';
        $html .= IsbnregistryFormsHtmlBuilder::getClassificationMenu($errors);
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_8_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_8" id="question_8" class="question" size="30" value="' . $_POST['question_8'] . '" />';
        $html .= '<span class="error">' . JText::_($errors['question_8']) . '</span></div>';
        $html .= '<div class="pre_field">' . JText::_('PLG_ISBNREGISTRY_FORMS_CONFIRMATION_PRE_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_CONFIRMATION_FIELD') . '<span  class="error"> *</span></div>';
        $html .= '<div><input type="text" name="confirmation" id="confirmation" size="30" value="';
        $html .= isset($_POST['confirmation']) ? $_POST['confirmation'] : date("d.m.Y");
        $html .= '" />';
        $html .= '<span  class="error">' . JText::_($errors['confirmation']) . '</span></div>';
        $html .= '<div class="field_info">' . JText::_('PLG_ISBNREGISTRY_FORMS_CONFIRMATION_POST_FIELD') . '</div>';
        $html .= '<div><input type="submit" name="submit_registration" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_SEND_BTN') . '" /></div>';
        $html .= JHTML::_('form.token');
        $html .= '</form></div>';
        return $html;
    }

    public static function getIsbnApplicationFormPt1($errors = array()) {
        $html .= '<div class="form_header">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_HEADER') . '</div>';
        $html .= '<div class="plg_isbnregistry_forms" id="plg_isbnregistry_forms_application" >';
        $html .= '<form action="' . $_SERVER["REQUEST_URI"] . '" method="post" name="isbnApplicationForm" id="isbnApplicationForm">';
        // Preliminary information about the publication
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_FIELD') . '</div>';
        $html .= '<div class="post_field"><select name="publication_type" id="publication_type">';
        $html .= '<option value="-"' . (($_POST['publication_type'] == '-') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_SELECT') . '</option>';
        $html .= '<option value="BOOK"' . (($_POST['publication_type'] == 'BOOK') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_BOOK') . '</option>';
        $html .= '<option value="DISSERTATION"' . (($_POST['publication_type'] == 'DISSERTATION') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_DISSERTATION') . '</option>';
        $html .= '<option value="SHEET_MUSIC"' . (($_POST['publication_type'] == 'SHEET_MUSIC') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_SHEET_MUSIC') . '</option>';
        $html .= '<option value="MAP"' . (($_POST['publication_type'] == 'MAP') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_MAP') . '</option>';
        $html .= '<option value="OTHER"' . (($_POST['publication_type'] == 'OTHER') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_OTHER') . '</option>';
        $html .= '</select><span class="error">* ' . JText::_($errors['publication_type']) . '</span></div>';
        $html .= '<div><input type="submit" name="submit_application_pt1" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_CONTINUE_BTN') . '" /></div>';
        $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt2Hidden();
        if (isset($_POST['back_application_pt2']) || isset($_POST['submit_application_pt4'])) {
            $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt3Hidden();
        }
        $html .= JHTML::_('form.token');
        $html .= '</form></div>';
        return $html;
    }

    public static function getIsbnApplicationFormPt2($errors = array()) {
        // Information about the publisher
		if (IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
			$html .= '<div class="form_header">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_HEADER_DISSERTATION') . '</div>';
		}
        $html .= '<div class="plg_isbnregistry_forms" id="plg_isbnregistry_forms_application" >';
        $html .= '<div class="sub_title">';
		if (IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
			$html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_1_1');
		} else {
			$html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_1');
		}
		$html .= '</div>';
        $html .= '<form action="' . $_SERVER["REQUEST_URI"] . '" method="post" name="isbnApplicationForm" id="isbnApplicationForm">';
		$html .= '<table>';
        $html .= '<tr>';
        if (IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_UNIVERSITY') . ':</td>';
            $html .= '<td><input type="text" name="official_name" id="official_name" size="30" value="' . $_POST['official_name'] . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['official_name']) . '</td>';
            $html .= '</tr><tr>';			
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_LOCALITY') . ':</td>';
            $html .= '<td><input type="text" name="locality" id="locality" size="30" value="' . $_POST['locality'] . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['locality']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_FIRST_NAME') . ':</td>';
            $html .= '<td><input type="text" name="first_name" id="first_name" size="30" value="' . $_POST['first_name'] . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['first_name']) . '</td>';
            $html .= '</tr><tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_LAST_NAME') . ':</td>';
            $html .= '<td><input type="text" name="last_name" id="last_name" size="30" value="' . $_POST['last_name'] . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['last_name']) . '</td>';
            $html .= '</tr><tr>';
        } else {
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_OFFICIAL_NAME_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="official_name" id="official_name" size="30" value="' . $_POST['official_name'] . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['official_name']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHER_IDENTIFIER_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="publisher_identifier_str" id="publisher_identifier_str" size="30" value="' . $_POST['publisher_identifier_str'] . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['publisher_identifier_str']) . '</td>';
            $html .= '</tr><tr>';
		}
		$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ADDRESS_FIELD') . ':</td>';
		$html .= '<td><input type="text" name="address" id="address" size="30" value="' . $_POST['address'] . '" /></td>';
		$html .= '<td class="error">* ' . JText::_($errors['address']) . '</td>';
		$html .= '</tr><tr>';
		$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ZIP_FIELD') . ':</td>';
		$html .= '<td><input type="text" name="zip" id="zip" size="5" value="' . $_POST['zip'] . '" /></td>';
		$html .= '<td class="error">* ' . JText::_($errors['zip']) . '</td>';
		$html .= '</tr><tr>';
		$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_CITY_FIELD') . ':</td>';
		$html .= '<td><input type="text" name="city" id="city" size="20" value="' . $_POST['city'] . '" /></td>';
		$html .= '<td class="error">* ' . JText::_($errors['city']) . '</td>';
		$html .= '</tr><tr>';
		if (!IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_CONTACT_PERSON_FIELD') . ':</td>';
			$html .= '<td><input type="text" name="contact_person" id="contact_person" size="30" value="' . $_POST['contact_person'] . '" /></td>';
			$html .= '<td class="error">* ' . JText::_($errors['contact_person']) . '</td>';
			$html .= '</tr><tr>';
		}
		$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PHONE_FIELD') . ':</td>';
		$html .= '<td><input type="text" name="phone" id="phone" size="15" value="' . $_POST['phone'] . '" /></td>';
		$html .= '<td class="error">* ' . JText::_($errors['phone']) . '</td>';
		$html .= '</tr><tr>';
		$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_EMAIL_FIELD') . ':</td>';
		// Content - Email Cloaking plugin must be disabled to get this work
		$html .= '<td><input type="text" id="email" name="email" size="30" value="' . $_POST['email'] . '" maxlength="100"/></td>';
		$html .= '<td class="error">* ' . JText::_($errors['email']) . '</td>';
		$html .= '</tr>';
		$html .= '</table>';
		// These fields are for for all the other publication types, but not for dissertations
		if (!IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
            // Information about publishing activities
            $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_2') . '</div>';
            $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHED_BEFORE_FIELD') . '</div>';
            $html .= '<div class="post_field"><select name="published_before" id="published_before">';
            $html .= '<option value="-"' . (($_POST['published_before'] == '-') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_SELECT') . '</option>';
            $html .= '<option value="1"' . (($_POST['published_before'] == '1') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_YES') . '</option>';
            $html .= '<option value="0"' . (($_POST['published_before'] == '0') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_NO') . '</option>';
            $html .= '</select><span class="error">* ' . JText::_($errors['published_before']) . '</span></div>';
            $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATIONS_PUBLIC_FIELD') . '</div>';
            $html .= '<div class="post_field"><select name="publications_public" id="publications_public">';
            $html .= '<option value="-"' . (($_POST['publications_public'] == '-') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_SELECT') . '</option>';
            $html .= '<option value="1"' . (($_POST['publications_public'] == '1') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_YES') . '</option>';
            $html .= '<option value="0"' . (($_POST['publications_public'] == '0') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_NO') . '</option>';
            $html .= '</select><span class="error">* ' . JText::_($errors['publications_public']) . '</span></div>';
            $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATIONS_INTRA_FIELD') . '</div>';
            $html .= '<div class="post_field"><select name="publications_intra" id="publications_intra">';
            $html .= '<option value="-"' . (($_POST['publications_intra'] == '-') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_SELECT') . '</option>';
            $html .= '<option value="1"' . (($_POST['publications_intra'] == '1') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_YES') . '</option>';
            $html .= '<option value="0"' . (($_POST['publications_intra'] == '0') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_NO') . '</option>';
            $html .= '</select><span class="error">* ' . JText::_($errors['publications_intra']) . '</span></div>';
            $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_FIELD') . '</div>';
            $html .= '<div class="post_field"><select name="publishing_activity" id="publishing_activity">';
            $html .= '<option value="-"' . (($_POST['publishing_activity'] == '-') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_SELECT') . '</option>';
            $html .= '<option value="OCCASIONAL"' . (($_POST['publishing_activity'] == 'OCCASIONAL') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_OCCASIONAL') . '</option>';
            $html .= '<option value="CONTINUOUS"' . (($_POST['publishing_activity'] == 'CONTINUOUS') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_CONTINUOUS') . '</option>';
            $html .= '</select><span class="error">* ' . JText::_($errors['publishing_activity']) . '</span></div>';
            $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_AMOUNT_FIELD') . '</div>';
            $html .= '<div class="post_field"><input type="text" name="publishing_activity_amount" id="publishing_activity_amount" size="5" class="question" value="' . $_POST['publishing_activity_amount'] . '" />';
            $html .= '<span class="error"> ' . JText::_($errors['publishing_activity_amount']) . '</span></div>';
        }
        // Preliminary information about the publication
        $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_3') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_FORMAT_FIELD') . '</div>';
        $html .= '<div><select name="publication_format" id="publication_format">';
        $html .= '<option value="-"' . (($_POST['publication_format'] == '-') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_SELECT') . '</option>';
        $html .= '<option value="PRINT"' . (($_POST['publication_format'] == 'PRINT') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_FORMAT_PRINT') . '</option>';
        $html .= '<option value="ELECTRONICAL"' . (($_POST['publication_format'] == 'ELECTRONICAL') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_FORMAT_ELECTRONICAL') . '</option>';
        $html .= '<option value="PRINT_ELECTRONICAL"' . (($_POST['publication_format'] == 'PRINT_ELECTRONICAL') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_FORMAT_BOTH') . '</option>';
        $html .= '</select><span class="error">* ' . JText::_($errors['publication_format']) . '</span></div>';
        $html .= '<div class="field_info">' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_FORMAT_POST_FIELD') . '</div>';
        $html .= '<div><input type="submit" name="back_application_pt2" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_BACK_BTN') . '" />';
        $html .= '<input type="submit" name="submit_application_pt2" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_CONTINUE_BTN') . '" /></div>';
        $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt1Hidden();
        $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt3Hidden();
        $html .= JHTML::_('form.token');
        $html .= '</form></div>';
        return $html;
    }

    public static function getIsbnApplicationFormPt3($errors = array()) {
        $html .= '<div class="plg_isbnregistry_forms" id="plg_isbnregistry_forms_application" >';            
        $html .= '<form action = "' . $_SERVER["REQUEST_URI"] . '" method = "post" name="isbnApplicationForm" id="isbnApplicationForm">';
		// Author info is not needed for dissertations - for other publication types it's mandatory		
		if (!IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
			// Information about the authors
			$html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_4') . '</div>';
			$html .= '<table>';
			$html .= '<tr>';
			$html .= '<th></th>';
			$html .= '<th>' . JText::_('PLG_ISBNREGISTRY_FORMS_AUTHOR_FIELD') . '</th>';
			$html .= '<th></th>';
			$html .= '</tr><tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FIRST_NAME_FIELD') . ':</td>';
			$html .= '<td><input type="text" name="first_name_1" id="first_name_1" size="30" value="' . $_POST['first_name_1'] . '" /></td>';
			$html .= '<td class="error">* ' . JText::_($errors['first_name_1']) . '</td>';
			$html .= '</tr><tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_LAST_NAME_FIELD') . ':</td>';
			$html .= '<td><input type="text" name="last_name_1" id="last_name_1" size="30" value="' . $_POST['last_name_1'] . '" /></td>';
			$html .= '<td class="error">* ' . JText::_($errors['last_name_1']) . '</td>';
			$html .= '</tr><tr class="spacer_bottom">';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_FIELD') . ':</td>';
			$html .= '<td>';
			$html .= '<input type="checkbox" name="role_1[]" value="AUTHOR"' . (isset($_POST['role_1']) && in_array('AUTHOR', $_POST['role_1']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_AUTHOR');
			$html .= '<input class="role_checkbox" type="checkbox" name="role_1[]" value="ILLUSTRATOR"' . (isset($_POST['role_1']) && in_array('ILLUSTRATOR', $_POST['role_1']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_ILLUSTRATOR');
			$html .= '<input class="role_checkbox" type="checkbox" name="role_1[]" value="TRANSLATOR"' . (isset($_POST['role_1']) && in_array('TRANSLATOR', $_POST['role_1']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_TRANSLATOR');
			$html .= '<input class="role_checkbox" type="checkbox" name="role_1[]" value="EDITOR"' . (isset($_POST['role_1']) && in_array('EDITOR', $_POST['role_1']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_EDITOR');
			$html .= '</td>';
			$html .= '<td class="error">* ' . JText::_($errors['role_1']) . '</td>';
			$html .= '</tr><tr>';
			$html .= '<th></th>';
			$html .= '<th>' . JText::_('PLG_ISBNREGISTRY_FORMS_OTHER_AUTHORS_FIELD') . '</th>';
			$html .= '<th></th>';
			$html .= '</tr><tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FIRST_NAME_FIELD') . ':</td>';
			$html .= '<td><input type="text" name="first_name_2" id="first_name_2" size="30" value="' . $_POST['first_name_2'] . '" /></td>';
			$html .= '<td class="error">' . JText::_($errors['first_name_2']) . '</td>';
			$html .= '</tr><tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_LAST_NAME_FIELD') . ':</td>';
			$html .= '<td><input type="text" name="last_name_2" id="last_name_2" size="30" value="' . $_POST['last_name_2'] . '" /></td>';
			$html .= '<td class="error">' . JText::_($errors['last_name_2']) . '</td>';
			$html .= '</tr><tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_FIELD') . ':</td>';
			$html .= '<td>';
			$html .= '<input type="checkbox" name="role_2[]" value="AUTHOR"' . (isset($_POST['role_2']) && in_array('AUTHOR', $_POST['role_2']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_AUTHOR');
			$html .= '<input class="role_checkbox" type="checkbox" name="role_2[]" value="ILLUSTRATOR"' . (isset($_POST['role_2']) && in_array('ILLUSTRATOR', $_POST['role_2']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_ILLUSTRATOR');
			$html .= '<input class="role_checkbox" type="checkbox" name="role_2[]" value="TRANSLATOR"' . (isset($_POST['role_2']) && in_array('TRANSLATOR', $_POST['role_2']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_TRANSLATOR');
			$html .= '<input class="role_checkbox" type="checkbox" name="role_2[]" value="EDITOR"' . (isset($_POST['role_2']) && in_array('EDITOR', $_POST['role_2']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_EDITOR');
			$html .= '</td>';
			$html .= '<td class="error">' . JText::_($errors['role_2']) . '</td>';
			$html .= '</tr><tr class="spacer_top">';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FIRST_NAME_FIELD') . ':</td>';
			$html .= '<td><input type="text" name="first_name_3" id="first_name_3" size="30" value="' . $_POST['first_name_3'] . '" /></td>';
			$html .= '<td class="error">' . JText::_($errors['first_name_3']) . '</td>';
			$html .= '</tr><tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_LAST_NAME_FIELD') . ':</td>';
			$html .= '<td><input type="text" name="last_name_3" id="last_name_3" size="30" value="' . $_POST['last_name_3'] . '" /></td>';
			$html .= '<td class="error">' . JText::_($errors['last_name_3']) . '</td>';
			$html .= '</tr><tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_FIELD') . ':</td>';
			$html .= '<td>';
			$html .= '<input type="checkbox" name="role_3[]" value="AUTHOR"' . (isset($_POST['role_3']) && in_array('AUTHOR', $_POST['role_3']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_AUTHOR');
			$html .= '<input class="role_checkbox" type="checkbox" name="role_3[]" value="ILLUSTRATOR"' . (isset($_POST['role_3']) && in_array('ILLUSTRATOR', $_POST['role_3']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_ILLUSTRATOR');
			$html .= '<input class="role_checkbox" type="checkbox" name="role_3[]" value="TRANSLATOR"' . (isset($_POST['role_3']) && in_array('TRANSLATOR', $_POST['role_3']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_TRANSLATOR');
			$html .= '<input class="role_checkbox" type="checkbox" name="role_3[]" value="EDITOR"' . (isset($_POST['role_3']) && in_array('EDITOR', $_POST['role_3']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_EDITOR');
			$html .= '</td>';
			$html .= '<td class="error">' . JText::_($errors['role_3']) . '</td>';
			$html .= '</tr><tr class="spacer_top">';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FIRST_NAME_FIELD') . ':</td>';
			$html .= '<td><input type="text" name="first_name_4" id="first_name_4" size="30" value="' . $_POST['first_name_4'] . '" /></td>';
			$html .= '<td class="error">' . JText::_($errors['first_name_4']) . '</td>';
			$html .= '</tr><tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_LAST_NAME_FIELD') . ':</td>';
			$html .= '<td><input type="text" name="last_name_4" id="last_name_4" size="30" value="' . $_POST['last_name_4'] . '" /></td>';
			$html .= '<td class="error">' . JText::_($errors['last_name_4']) . '</td>';
			$html .= '</tr><tr class="spacer_bottom">';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_FIELD') . ':</td>';
			$html .= '<td>';
			$html .= '<input type="checkbox" name="role_4[]" value="AUTHOR"' . (isset($_POST['role_4']) && in_array('AUTHOR', $_POST['role_4']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_AUTHOR');
			$html .= '<input class="role_checkbox" type="checkbox" name="role_4[]" value="ILLUSTRATOR"' . (isset($_POST['role_4']) && in_array('ILLUSTRATOR', $_POST['role_4']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_ILLUSTRATOR');
			$html .= '<input class="role_checkbox" type="checkbox" name="role_4[]" value="TRANSLATOR"' . (isset($_POST['role_4']) && in_array('TRANSLATOR', $_POST['role_4']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_TRANSLATOR');
			$html .= '<input class="role_checkbox" type="checkbox" name="role_4[]" value="EDITOR"' . (isset($_POST['role_4']) && in_array('EDITOR', $_POST['role_4']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_EDITOR');
			$html .= '</td>';
			$html .= '<td class="error">' . JText::_($errors['role_4']) . '</td>';
			$html .= '</tr>';
			$html .= '</table>';
		} 
        // Information about the publication
        $html .= '<div class="sub_title">';
		if (IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
			$html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_5_1');
		} else {
			$html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_5');
		}
		$html .= '</div>';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_TITLE_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="title" id="title" size="50" value="' . $_POST['title'] . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['title']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_SUBTITLE_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="subtitle" id="title" size="50" value="' . $_POST['subtitle'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['subtitle']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_LANGUAGE_FIELD') . ':</td>';
        $html .= '<td>';
        $html .= IsbnregistryFormsHtmlBuilder::getLanguageMenu();
        $html .= '</td>';
        $html .= '<td class="error">* ' . JText::_($errors['language']) . '</td>';
        $html .= '</tr><tr class="spacer_bottom">';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHED_FIELD') . ':</td>';
        $html .= '<td>';
        $html .= IsbnregistryFormsHtmlBuilder::getPublishedYearMenu();
        $html .= IsbnregistryFormsHtmlBuilder::getPublishedMonthMenu();
        $html .= '</td>';
        $html .= '<td class="error">* ' . JText::_($errors['published']) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        // Information about the series
        $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_6') . '</div>';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td id="series_info">' . JText::_('PLG_ISBNREGISTRY_FORMS_SERIES_PRE_FIELD') . '</td>';
        $html .= '<td></td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_SERIES_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="series" id="series" size="50" value="' . $_POST['series'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['series']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ISSN_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="issn" id="issn" size="9" value="' . $_POST['issn'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['issn']) . '</td>';
        $html .= '</tr><tr class="spacer_bottom">';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_VOLUME_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="volume" id="volume" size="2" value="' . $_POST['volume'] . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['volume']) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        if (IsbnregistryFormsHelper::isPrint($_POST['publication_format'])) {
            // Information about the printed publication
            $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_7') . '</div>';
            $html .= '<table>';
            $html .= '<tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PRINTING_HOUSE_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="printing_house" id="printing_house" size="50" value="' . $_POST['printing_house'] . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['printing_house']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PRINTING_HOUSE_CITY_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="printing_house_city" id="printing_house_city" size="50" value="' . $_POST['printing_house_city'] . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['printing_house_city']) . '</td>';
            $html .= '</tr>';
			// Copies and edition fields are shown only if the publication is not a dissertation
			if (!IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
				$html .= '<tr>';
				$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_COPIES_FIELD') . ':</td>';
				$html .= '<td><input type="text" name="copies" id="copies" size="4" value="' . $_POST['copies'] . '" /></td>';
				$html .= '<td class="error">' . JText::_($errors['copies']) . '</td>';
				$html .= '</tr><tr>';
				$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_EDITION_FIELD') . ':</td>';
				$html .= '<td>';
				$html .= IsbnregistryFormsHtmlBuilder::getEditionMenu();
				$html .= '</td>';
				$html .= '<td class="error">' . JText::_($errors['edition']) . '</td>';
				$html .= '</tr>';
			}
			$html .= '<tr class="spacer_bottom">';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_TYPE_FIELD') . ':</td>';
            $html .= '<td>';
            $html .= '<input type="checkbox" name="type[]" value="PAPERBACK"' . (isset($_POST['type']) && in_array('PAPERBACK', $_POST['type']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_TYPE_PAPERBACK');
            $html .= '<input class="role_checkbox" type="checkbox" name="type[]" value="HARDBACK"' . (isset($_POST['type']) && in_array('HARDBACK', $_POST['type']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_TYPE_HARDBACK');
            $html .= '<input class="role_checkbox" type="checkbox" name="type[]" value="SPIRAL_BINDING"' . (isset($_POST['type']) && in_array('SPIRAL_BINDING', $_POST['type']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_TYPE_SPIRAL_BINDING');
            $html .= '</td>';
            $html .= '<td class="error">* ' . JText::_($errors['type']) . '</td>';
            $html .= '</tr>';
            $html .= '</table>';
        }
        // Additional information
        $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_8') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_COMMENTS_FIELD') . '</div>';
        $html .= '<div class="spacer_bottom"><textarea name="comments" id="comments" class="question">' . $_POST['comments'] . '</textarea>';
        $html .= '<span class="error">' . JText::_($errors['comments']) . '</span></div>';
        if (IsbnregistryFormsHelper::isElectronical($_POST['publication_format'])) {
            // Other information
            $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_9') . '</div>';
            $html .= '<table>';
            $html .= '<tr class="spacer_bottom">';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_FIELD') . ':</td>';
            $html .= '<td>';
            $html .= '<input class="role_checkbox" type="checkbox" name="fileformat[]" value="PDF"' . (isset($_POST['fileformat']) && in_array('PDF', $_POST['fileformat']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_PDF');
            $html .= '<input class="role_checkbox" type="checkbox" name="fileformat[]" value="EPUB"' . (isset($_POST['fileformat']) && in_array('EPUB', $_POST['fileformat']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_EPUB');
            $html .= '<input class="role_checkbox" type="checkbox" name="fileformat[]" value="CD_ROM"' . (isset($_POST['fileformat']) && in_array('CD_ROM', $_POST['fileformat']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_CD_ROM');
            $html .= '<input class="role_checkbox" type="checkbox" name="fileformat[]" value="OTHER"' . (isset($_POST['fileformat']) && in_array('OTHER', $_POST['fileformat']) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_OTHER');
            $html .= '</td>';
            $html .= '<td class="error">* ' . JText::_($errors['fileformat']) . '</td>';
            $html .= '</tr>';
            $html .= '</table>';
        }
        $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt1Hidden();
        $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt2Hidden();
        $html .= '<div><input type="submit" name="back_application_pt3" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_BACK_BTN') . '" />';
        $html .= '<input type="submit" name="submit_application_pt3" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_CONTINUE_BTN') . '" /></div>';
        $html .= JHTML::_('form.token');
        $html .= '</form></div>';
        return $html;
    }

    public static function getIsbnApplicationFormPt4() {
        // Information about the publisher
        $html .= '<div class="form_header">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUMMARY_HEADER') . '</div>';
        $html .= '<div class="plg_isbnregistry_forms" id="plg_isbnregistry_forms_application_summary" >';
		$html .= '<div class="sub_title">';
		if (IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
			$html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_1_1');
		} else {
			$html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_1');
		}		
		$html .= '</div>';
        $html .= '<table>';
        $html .= '<tr>';
        if (IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_UNIVERSITY') . ':</td>';
			$html .= '<td>' . $_POST['official_name'] . '</td>';
			$html .= '</tr><tr>';		
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_LOCALITY') . ':</td>';
			$html .= '<td>' . $_POST['locality'] . '</td>';
			$html .= '</tr><tr>';		
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_DOCTOR_CANDIDATE') . ':</td>';
			$html .= '<td>' . $_POST['first_name'] . ' ' . $_POST['last_name'] . '</td>';
			$html .= '</tr><tr>';			
        } else {
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHER_FIELD') . ':</td>';
			$html .= '<td>' . $_POST['official_name'] . '</td>';
			$html .= '</tr><tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHER_IDENTIFIER_FIELD') . ':</td>';
			$html .= '<td>' . $_POST['publisher_identifier_str'] . '</td>';
			$html .= '</tr><tr>';
		}		
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ADDRESS_FIELD') . ':</td>';
        $html .= '<td>' . $_POST['address'] . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ZIP_FIELD') . ':</td>';
        $html .= '<td>' . $_POST['zip'] . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_CITY_FIELD') . ':</td>';
        $html .= '<td>' . $_POST['city'] . '</td>';
        $html .= '</tr><tr>';
		if (!IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_CONTACT_PERSON_FIELD') . ':</td>';
			$html .= '<td>' . $_POST['contact_person'] . '</td>';
			$html .= '</tr><tr>';
		}
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PHONE_FIELD') . ':</td>';
        $html .= '<td>' . $_POST['phone'] . '</td>';
        $html .= '</tr><tr class="spacer_bottom">';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_EMAIL_FIELD') . ':</td>';
        $html .= '<td>' . $_POST['email'] . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
		if (!IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
			// Information about publishing activities
			$html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_2') . '</div>';
			$html .= '<table>';
			$html .= '<tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHED_BEFORE_SUMMARY_FIELD') . ':</td>';
			$html .= '<td>' . ($_POST['published_before'] ? JText::_('PLG_ISBNREGISTRY_FORMS_YES') : JText::_('PLG_ISBNREGISTRY_FORMS_NO')) . '</td>';
			$html .= '</tr><tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATIONS_PUBLIC_SUMMARY_FIELD') . ':</td>';
			$html .= '<td>' . ($_POST['publications_public'] ? JText::_('PLG_ISBNREGISTRY_FORMS_YES') : JText::_('PLG_ISBNREGISTRY_FORMS_NO')) . '</td>';
			$html .= '</tr><tr>';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATIONS_INTRA_SUMMARY_FIELD') . ':</td>';
			$html .= '<td>' . ($_POST['publications_intra'] ? JText::_('PLG_ISBNREGISTRY_FORMS_YES') : JText::_('PLG_ISBNREGISTRY_FORMS_NO')) . '</td>';
			$html .= '</tr><tr class="spacer_bottom">';
			$html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_SUMMARY_FIELD') . ':</td>';
			$html .= '<td>' . (($_POST['publishing_activity'] == 'OCCASIONAL') ? JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_OCCASIONAL') : JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_CONTINUOUS')) . '</td>';
			$html .= '</tr>';
			$html .= '</table>';
		}
        // Information about the publication
        $html .= '<div class="sub_title">';
		if (IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
			$html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_5_1');
		} else {
			$html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_5');
		}
		$html .= '</div>';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_AUTHORS_FIELD') . ':</td>';
        $html .= '<td>';
		if (IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
			$html .= $_POST['first_name'] . ' ' . $_POST['last_name'];	
		} else {
			$html .= IsbnregistryFormsHelper::buildAuthorsField();
		}		
		$html .= '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_TITLE_SUMMARY_FIELD') . ':</td>';
        $html .= '<td>' . $_POST['title'] . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_SUBTITLE_SUMMARY_FIELD') . ':</td>';
        $html .= '<td>' . $_POST['subtitle'] . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHED_FIELD') . ':</td>';
        $html .= '<td>' . IsbnregistryFormsHelper::getPublishedDateString() . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_LANGUAGE_SUMMARY_FIELD') . ':</td>';
        $html .= '<td>' . IsbnregistryFormsHelper::getLanguageLabel() . '</td>';
        if (IsbnregistryFormsHelper::isPrint($_POST['publication_format'])) {
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_TYPE_FIELD') . ':</td>';
            $html .= '<td>' . IsbnregistryFormsHelper::getTypeString() . '</td>';
        }
        if (IsbnregistryFormsHelper::isElectronical($_POST['publication_format'])) {
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_FIELD') . ':</td>';
            $html .= '<td>' . IsbnregistryFormsHelper::getFileFormatString() . '</td>';
        }
        $html .= '</tr>';
        $html .= '</table><br />';
        $html .= '<form action = "' . $_SERVER["REQUEST_URI"] . '" method = "post" name="isbnApplicationForm" id="isbnApplicationForm">';
        $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt1Hidden();
        $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt2Hidden();
        $html .= IsbnregistryFormsHtmlBuilder::getIsbnApplicationFormPt3Hidden();
        $html .= '<div><input type="submit" name="back_application_pt4" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_BACK_BTN') . '" />';
        $html .= '<input type="submit" name="submit_application_pt4" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_SEND_BTN') . '" /></div>';
        $html .= JHTML::_('form.token');
        $html .= '</form></div>';
        return $html;
    }

    private static function getIsbnApplicationFormPt1Hidden() {
        $html .= '<input type="hidden" name="publication_type" value="' . $_POST['publication_type'] . '" />';
        return $html;
    }

    private static function getIsbnApplicationFormPt2Hidden() {
        $html .= '<input type="hidden" name="official_name" value="' . $_POST['official_name'] . '" />';
        $html .= '<input type="hidden" name="address" value="' . $_POST['address'] . '" />';
        $html .= '<input type="hidden" name="zip" value="' . $_POST['zip'] . '" />';
        $html .= '<input type="hidden" name="city" value="' . $_POST['city'] . '" />';      
        $html .= '<input type="hidden" name="phone" value="' . $_POST['phone'] . '" />';
        $html .= '<input type="hidden" name="email" value="' . $_POST['email'] . '" />';
		if (!IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
			$html .= '<input type="hidden" name="contact_person" value="' . $_POST['contact_person'] . '" />';
			$html .= '<input type="hidden" name="publisher_identifier_str" value="' . $_POST['publisher_identifier_str'] . '" />';
			$html .= '<input type="hidden" name="published_before" value="' . $_POST['published_before'] . '" />';
			$html .= '<input type="hidden" name="publications_public" value="' . $_POST['publications_public'] . '" />';
			$html .= '<input type="hidden" name="publications_intra" value="' . $_POST['publications_intra'] . '" />';
			$html .= '<input type="hidden" name="publishing_activity" value="' . $_POST['publishing_activity'] . '" />';
			$html .= '<input type="hidden" name="publishing_activity_amount" value="' . $_POST['publishing_activity_amount'] . '" />';
		} else {
			$html .= '<input type="hidden" name="first_name" value="' . $_POST['first_name'] . '" />';
			$html .= '<input type="hidden" name="last_name" value="' . $_POST['last_name'] . '" />';
			$html .= '<input type ="hidden" name ="locality" value="' . $_POST['locality'] . '" />';
		}
        $html .= '<input type="hidden" name="publication_format" value="' . $_POST['publication_format'] . '" />';
        return $html;
    }

    private static function getIsbnApplicationFormPt3Hidden() {
		// Author info is included only if the publication is not a dissertation
		if (!IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
			$html .= '<input type="hidden" name="first_name_1" value="' . $_POST['first_name_1'] . '" />';
			$html .= '<input type="hidden" name="last_name_1" value="' . $_POST['last_name_1'] . '" />';
			$html .= '<input style="display:none;" type="checkbox" name="role_1[]" value="AUTHOR"' . (isset($_POST['role_1']) && in_array('AUTHOR', $_POST['role_1']) ? ' checked' : '') . '/>';
			$html .= '<input style="display:none;" type="checkbox" name="role_1[]" value="ILLUSTRATOR"' . (isset($_POST['role_1']) && in_array('ILLUSTRATOR', $_POST['role_1']) ? ' checked' : '') . '/>';
			$html .= '<input style="display:none;" type="checkbox" name="role_1[]" value="TRANSLATOR"' . (isset($_POST['role_1']) && in_array('TRANSLATOR', $_POST['role_1']) ? ' checked' : '') . '/>';
			$html .= '<input style="display:none;" type="checkbox" name="role_1[]" value="EDITOR"' . (isset($_POST['role_1']) && in_array('EDITOR', $_POST['role_1']) ? ' checked' : '') . '/>';
			$html .= '<input type="hidden" name="first_name_2" value="' . $_POST['first_name_2'] . '" />';
			$html .= '<input type="hidden" name="last_name_2" value="' . $_POST['last_name_2'] . '" />';
			$html .= '<input style="display:none;" type="checkbox" name="role_2[]" value="AUTHOR"' . (isset($_POST['role_2']) && in_array('AUTHOR', $_POST['role_2']) ? ' checked' : '') . '/>';
			$html .= '<input style="display:none;" type="checkbox" name="role_2[]" value="ILLUSTRATOR"' . (isset($_POST['role_2']) && in_array('ILLUSTRATOR', $_POST['role_2']) ? ' checked' : '') . '/>';
			$html .= '<input style="display:none;" type="checkbox" name="role_2[]" value="TRANSLATOR"' . (isset($_POST['role_2']) && in_array('TRANSLATOR', $_POST['role_2']) ? ' checked' : '') . '/>';
			$html .= '<input style="display:none;" type="checkbox" name="role_2[]" value="EDITOR"' . (isset($_POST['role_2']) && in_array('EDITOR', $_POST['role_2']) ? ' checked' : '') . '/>';
			$html .= '<input type="hidden" name="first_name_3" value="' . $_POST['first_name_3'] . '" />';
			$html .= '<input type="hidden" name="last_name_3" value="' . $_POST['last_name_3'] . '" />';
			$html .= '<input style="display:none;" type="checkbox" name="role_3[]" value="AUTHOR"' . (isset($_POST['role_3']) && in_array('AUTHOR', $_POST['role_3']) ? ' checked' : '') . '/>';
			$html .= '<input style="display:none;" type="checkbox" name="role_3[]" value="ILLUSTRATOR"' . (isset($_POST['role_3']) && in_array('ILLUSTRATOR', $_POST['role_3']) ? ' checked' : '') . '/>';
			$html .= '<input style="display:none;" type="checkbox" name="role_3[]" value="TRANSLATOR"' . (isset($_POST['role_3']) && in_array('TRANSLATOR', $_POST['role_3']) ? ' checked' : '') . '/>';
			$html .= '<input style="display:none;" type="checkbox" name="role_3[]" value="EDITOR"' . (isset($_POST['role_3']) && in_array('EDITOR', $_POST['role_3']) ? ' checked' : '') . '/>';
			$html .= '<input type="hidden" name="first_name_4" value="' . $_POST['first_name_4'] . '" />';
			$html .= '<input type="hidden" name="last_name_4" value="' . $_POST['last_name_4'] . '" />';
			$html .= '<input style="display:none;" type="checkbox" name="role_4[]" value="AUTHOR"' . (isset($_POST['role_4']) && in_array('AUTHOR', $_POST['role_4']) ? ' checked' : '') . '/>';
			$html .= '<input style="display:none;" type="checkbox" name="role_4[]" value="ILLUSTRATOR"' . (isset($_POST['role_4']) && in_array('ILLUSTRATOR', $_POST['role_4']) ? ' checked' : '') . '/>';
			$html .= '<input style="display:none;" type="checkbox" name="role_4[]" value="TRANSLATOR"' . (isset($_POST['role_4']) && in_array('TRANSLATOR', $_POST['role_4']) ? ' checked' : '') . '/>';
			$html .= '<input style="display:none;" type="checkbox" name="role_4[]" value="EDITOR"' . (isset($_POST['role_4']) && in_array('EDITOR', $_POST['role_4']) ? ' checked' : '') . '/>';
		}
		$html .= '<input type="hidden" name="title" value="' . $_POST['title'] . '" />';
        $html .= '<input type="hidden" name="subtitle" value="' . $_POST['subtitle'] . '" />';
        $html .= '<input type="hidden" name="language" value="' . $_POST['language'] . '" />';
        $html .= '<input type="hidden" name="year" value="' . $_POST['year'] . '" />';
        $html .= '<input type="hidden" name="month" value="' . $_POST['month'] . '" />';
        $html .= '<input type="hidden" name="series" value="' . $_POST['series'] . '" />';
        $html .= '<input type="hidden" name="issn" value="' . $_POST['issn'] . '" />';
        $html .= '<input type="hidden" name="volume" value="' . $_POST['volume'] . '" />';
        if (IsbnregistryFormsHelper::isPrint($_POST['publication_format'])) {
            // Information about the printed publication
            $html .= '<input type="hidden" name="printing_house" value="' . $_POST['printing_house'] . '" />';
            $html .= '<input type="hidden" name="printing_house_city" value="' . $_POST['printing_house_city'] . '" />';
			// Copies and edition fields are included only if the publication is not a dissertation
			if (!IsbnregistryFormsHelper::isDissertation($_POST['publication_type'])) {
				$html .= '<input type="hidden" name="copies" value="' . $_POST['copies'] . '" />';
				$html .= '<input type="hidden" name="edition" value="' . $_POST['edition'] . '" />';
			}
            $html .= '<input style="display:none;" type="checkbox" name="type[]" value="PAPERBACK"' . (isset($_POST['type']) && in_array('PAPERBACK', $_POST['type']) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="type[]" value="HARDBACK"' . (isset($_POST['type']) && in_array('HARDBACK', $_POST['type']) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="type[]" value="SPIRAL_BINDING"' . (isset($_POST['type']) && in_array('SPIRAL_BINDING', $_POST['type']) ? ' checked' : '') . '/>';
        }
        // Additional information
        $html .= '<textarea name="comments" style="display:none;">' . $_POST['comments'] . '</textarea>';
        if (IsbnregistryFormsHelper::isElectronical($_POST['publication_format'])) {
            $html .= '<input style="display:none;" type="checkbox" name="fileformat[]" value="PDF"' . (isset($_POST['fileformat']) && in_array('PDF', $_POST['fileformat']) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="fileformat[]" value="EPUB"' . (isset($_POST['fileformat']) && in_array('EPUB', $_POST['fileformat']) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="fileformat[]" value="CD_ROM"' . (isset($_POST['fileformat']) && in_array('CD_ROM', $_POST['fileformat']) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="fileformat[]" value="OTHER"' . (isset($_POST['fileformat']) && in_array('OTHER', $_POST['fileformat']) ? ' checked' : '') . '/>';
        }
        return $html;
    }

    private static function getLanguageMenu() {
        $html .= '<select name="language" id="language">';
        $html .= '<option value="-"' . (strcmp($_POST['language'], '-') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_SELECT') . '</option>';
        foreach (IsbnregistryFormsHelper::getLanguageList() as $language) {
            $html .= '<option value="' . $language . '"' . (strcmp($_POST['language'], $language) == 0 ? ' selected' : '') . '>' . JText::_("PLG_ISBNREGISTRY_FORMS_LANGUAGE_$language") . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    private static function getPublishedYearMenu() {
        $html .= '<select name="year" id="year">';
        $html .= '<option value="-"' . (strcmp($_POST['year'], '-') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHED_YEAR') . '</option>';
        $html .= '<option value="' . (date("Y") - 2) . '"' . ($_POST['year'] == (date("Y") - 2) ? ' selected' : '') . '>' . (date("Y") - 2) . '</option>';
        $html .= '<option value="' . (date("Y") - 1) . '"' . ($_POST['year'] == (date("Y") - 1) ? ' selected' : '') . '>' . (date("Y") - 1) . '</option>';
        $html .= '<option value="' . (date("Y")) . '"' . ($_POST['year'] == date("Y") ? ' selected' : '') . '>' . (date("Y")) . '</option>';
        $html .= '<option value="' . (date("Y") + 1) . '"' . ($_POST['year'] == (date("Y") + 1) ? ' selected' : '') . '>' . (date("Y") + 1) . '</option>';
        $html .= '<option value="' . (date("Y") + 2) . '"' . ($_POST['year'] == (date("Y") + 2) ? ' selected' : '') . '>' . (date("Y") + 2) . '</option>';
        $html .= '</select>';
        return $html;
    }

    private static function getPublishedMonthMenu() {
        $html .= '<select name="month" id="month">';
        $html .= '<option value="-"' . (strcmp($_POST['month'], '-') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHED_MONTH') . '</option>';
        $html .= '<option value="01"' . (strcmp($_POST['month'], '01') == 0 ? ' selected' : '') . '>01</option>';
        $html .= '<option value="02"' . (strcmp($_POST['month'], '02') == 0 ? ' selected' : '') . '>02</option>';
        $html .= '<option value="03"' . (strcmp($_POST['month'], '03') == 0 ? ' selected' : '') . '>03</option>';
        $html .= '<option value="04"' . (strcmp($_POST['month'], '04') == 0 ? ' selected' : '') . '>04</option>';
        $html .= '<option value="05"' . (strcmp($_POST['month'], '05') == 0 ? ' selected' : '') . '>05</option>';
        $html .= '<option value="06"' . (strcmp($_POST['month'], '06') == 0 ? ' selected' : '') . '>06</option>';
        $html .= '<option value="07"' . (strcmp($_POST['month'], '07') == 0 ? ' selected' : '') . '>07</option>';
        $html .= '<option value="08"' . (strcmp($_POST['month'], '08') == 0 ? ' selected' : '') . '>08</option>';
        $html .= '<option value="09"' . (strcmp($_POST['month'], '09') == 0 ? ' selected' : '') . '>09</option>';
        $html .= '<option value="10"' . (strcmp($_POST['month'], '10') == 0 ? ' selected' : '') . '>10</option>';
        $html .= '<option value="11"' . (strcmp($_POST['month'], '11') == 0 ? ' selected' : '') . '>11</option>';
        $html .= '<option value="12"' . (strcmp($_POST['month'], '12') == 0 ? ' selected' : '') . '>12</option>';
        $html .= '</select>';
        return $html;
    }

    private static function getEditionMenu() {
        $html .= '<select name="edition" id="edition">';
        $html .= '<option value="-"' . (strcmp($_POST['year'], '-') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_SELECT') . '</option>';
        for ($x = 1; $x <= 10; $x++) {
            $html .= '<option value="' . $x . '"' . (strcmp($_POST['edition'], $x) == 0 ? ' selected' : '') . '>' . $x . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    private static function getClassificationMenu($errors = array()) {
        $html .= '<div>';
        $html .= '<select name="question_7[]" data-placeholder = "' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_SELECT') . '" multiple = "multiple" id="question_7">';
        $html .= '<option value="000"' . (isset($_POST['question_7']) && in_array('000', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_000') . '</option>';
        $html .= '<option value="015"' . (isset($_POST['question_7']) && in_array('015', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_015') . '</option>';
        $html .= '<option value="030"' . (isset($_POST['question_7']) && in_array('030', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_030') . '</option>';
        $html .= '<option value="035"' . (isset($_POST['question_7']) && in_array('035', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_035') . '</option>';
        $html .= '<option value="040"' . (isset($_POST['question_7']) && in_array('040', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_040') . '</option>';
        $html .= '<option value="045"' . (isset($_POST['question_7']) && in_array('045', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_045') . '</option>';
        $html .= '<option value="050"' . (isset($_POST['question_7']) && in_array('050', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_050') . '</option>';
        $html .= '<option value="055"' . (isset($_POST['question_7']) && in_array('055', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_055') . '</option>';
        $html .= '<option value="100"' . (isset($_POST['question_7']) && in_array('100', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_100') . '</option>';
        $html .= '<option value="120"' . (isset($_POST['question_7']) && in_array('120', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_120') . '</option>';
        $html .= '<option value="130"' . (isset($_POST['question_7']) && in_array('130', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_130') . '</option>';
        $html .= '<option value="200"' . (isset($_POST['question_7']) && in_array('200', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_200') . '</option>';
        $html .= '<option value="210"' . (isset($_POST['question_7']) && in_array('210', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_210') . '</option>';
        $html .= '<option value="211"' . (isset($_POST['question_7']) && in_array('211', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_211') . '</option>';
        $html .= '<option value="270"' . (isset($_POST['question_7']) && in_array('270', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_270') . '</option>';
        $html .= '<option value="300"' . (isset($_POST['question_7']) && in_array('300', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_300') . '</option>';
        $html .= '<option value="310"' . (isset($_POST['question_7']) && in_array('310', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_310') . '</option>';
        $html .= '<option value="315"' . (isset($_POST['question_7']) && in_array('315', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_315') . '</option>';
        $html .= '<option value="316"' . (isset($_POST['question_7']) && in_array('316', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_316') . '</option>';
        $html .= '<option value="320"' . (isset($_POST['question_7']) && in_array('320', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_320') . '</option>';
        $html .= '<option value="330"' . (isset($_POST['question_7']) && in_array('330', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_330') . '</option>';
        $html .= '<option value="340"' . (isset($_POST['question_7']) && in_array('340', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_340') . '</option>';
        $html .= '<option value="350"' . (isset($_POST['question_7']) && in_array('350', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_350') . '</option>';
        $html .= '<option value="370"' . (isset($_POST['question_7']) && in_array('370', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_370') . '</option>';
        $html .= '<option value="375"' . (isset($_POST['question_7']) && in_array('375', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_375') . '</option>';
        $html .= '<option value="380"' . (isset($_POST['question_7']) && in_array('380', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_380') . '</option>';
        $html .= '<option value="390"' . (isset($_POST['question_7']) && in_array('390', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_390') . '</option>';
        $html .= '<option value="400"' . (isset($_POST['question_7']) && in_array('400', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_400') . '</option>';
        $html .= '<option value="410"' . (isset($_POST['question_7']) && in_array('410', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_410') . '</option>';
        $html .= '<option value="420"' . (isset($_POST['question_7']) && in_array('420', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_420') . '</option>';
        $html .= '<option value="440"' . (isset($_POST['question_7']) && in_array('440', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_440') . '</option>';
        $html .= '<option value="450"' . (isset($_POST['question_7']) && in_array('450', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_450') . '</option>';
        $html .= '<option value="460"' . (isset($_POST['question_7']) && in_array('460', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_460') . '</option>';
        $html .= '<option value="470"' . (isset($_POST['question_7']) && in_array('470', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_470') . '</option>';
        $html .= '<option value="480"' . (isset($_POST['question_7']) && in_array('480', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_480') . '</option>';
        $html .= '<option value="490"' . (isset($_POST['question_7']) && in_array('490', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_490') . '</option>';
        $html .= '<option value="500"' . (isset($_POST['question_7']) && in_array('500', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_500') . '</option>';
        $html .= '<option value="510"' . (isset($_POST['question_7']) && in_array('510', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_510') . '</option>';
        $html .= '<option value="520"' . (isset($_POST['question_7']) && in_array('520', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_520') . '</option>';
        $html .= '<option value="530"' . (isset($_POST['question_7']) && in_array('530', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_530') . '</option>';
        $html .= '<option value="540"' . (isset($_POST['question_7']) && in_array('540', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_540') . '</option>';
        $html .= '<option value="550"' . (isset($_POST['question_7']) && in_array('550', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_550') . '</option>';
        $html .= '<option value="560"' . (isset($_POST['question_7']) && in_array('560', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_560') . '</option>';
        $html .= '<option value="570"' . (isset($_POST['question_7']) && in_array('570', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_570') . '</option>';
        $html .= '<option value="580"' . (isset($_POST['question_7']) && in_array('580', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_580') . '</option>';
        $html .= '<option value="590"' . (isset($_POST['question_7']) && in_array('590', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_590') . '</option>';
        $html .= '<option value="600"' . (isset($_POST['question_7']) && in_array('600', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_600') . '</option>';
        $html .= '<option value="610"' . (isset($_POST['question_7']) && in_array('610', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_610') . '</option>';
        $html .= '<option value="620"' . (isset($_POST['question_7']) && in_array('620', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_620') . '</option>';
        $html .= '<option value="621"' . (isset($_POST['question_7']) && in_array('621', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_621') . '</option>';
        $html .= '<option value="622"' . (isset($_POST['question_7']) && in_array('622', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_622') . '</option>';
        $html .= '<option value="630"' . (isset($_POST['question_7']) && in_array('630', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_630') . '</option>';
        $html .= '<option value="640"' . (isset($_POST['question_7']) && in_array('640', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_640') . '</option>';
        $html .= '<option value="650"' . (isset($_POST['question_7']) && in_array('650', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_650') . '</option>';
        $html .= '<option value="660"' . (isset($_POST['question_7']) && in_array('660', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_660') . '</option>';
        $html .= '<option value="670"' . (isset($_POST['question_7']) && in_array('670', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_670') . '</option>';
        $html .= '<option value="672"' . (isset($_POST['question_7']) && in_array('672', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_672') . '</option>';
        $html .= '<option value="680"' . (isset($_POST['question_7']) && in_array('680', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_680') . '</option>';
        $html .= '<option value="690"' . (isset($_POST['question_7']) && in_array('690', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_690') . '</option>';
        $html .= '<option value="700"' . (isset($_POST['question_7']) && in_array('700', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_700') . '</option>';
        $html .= '<option value="710"' . (isset($_POST['question_7']) && in_array('710', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_710') . '</option>';
        $html .= '<option value="720"' . (isset($_POST['question_7']) && in_array('720', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_720') . '</option>';
        $html .= '<option value="730"' . (isset($_POST['question_7']) && in_array('730', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_730') . '</option>';
        $html .= '<option value="740"' . (isset($_POST['question_7']) && in_array('740', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_740') . '</option>';
        $html .= '<option value="750"' . (isset($_POST['question_7']) && in_array('750', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_750') . '</option>';
        $html .= '<option value="760"' . (isset($_POST['question_7']) && in_array('760', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_760') . '</option>';
        $html .= '<option value="765"' . (isset($_POST['question_7']) && in_array('765', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_765') . '</option>';
        $html .= '<option value="770"' . (isset($_POST['question_7']) && in_array('770', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_770') . '</option>';
        $html .= '<option value="780"' . (isset($_POST['question_7']) && in_array('780', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_780') . '</option>';
        $html .= '<option value="790"' . (isset($_POST['question_7']) && in_array('790', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_790') . '</option>';
        $html .= '<option value="800"' . (isset($_POST['question_7']) && in_array('800', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_800') . '</option>';
        $html .= '<option value="810"' . (isset($_POST['question_7']) && in_array('810', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_810') . '</option>';
        $html .= '<option value="820"' . (isset($_POST['question_7']) && in_array('820', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_820') . '</option>';
        $html .= '<option value="830"' . (isset($_POST['question_7']) && in_array('830', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_830') . '</option>';
        $html .= '<option value="840"' . (isset($_POST['question_7']) && in_array('840', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_840') . '</option>';
        $html .= '<option value="850"' . (isset($_POST['question_7']) && in_array('850', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_850') . '</option>';
        $html .= '<option value="860"' . (isset($_POST['question_7']) && in_array('860', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_860') . '</option>';
        $html .= '<option value="870"' . (isset($_POST['question_7']) && in_array('870', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_870') . '</option>';
        $html .= '<option value="880"' . (isset($_POST['question_7']) && in_array('880', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_880') . '</option>';
        $html .= '<option value="890"' . (isset($_POST['question_7']) && in_array('890', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_890') . '</option>';
        $html .= '<option value="900"' . (isset($_POST['question_7']) && in_array('900', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_900') . '</option>';
        $html .= '<option value="910"' . (isset($_POST['question_7']) && in_array('910', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_910') . '</option>';
        $html .= '<option value="920"' . (isset($_POST['question_7']) && in_array('920', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_920') . '</option>';
        $html .= '<option value="930"' . (isset($_POST['question_7']) && in_array('930', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_930') . '</option>';
        $html .= '<option value="940"' . (isset($_POST['question_7']) && in_array('940', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_940') . '</option>';
        $html .= '<option value="950"' . (isset($_POST['question_7']) && in_array('950', $_POST['question_7']) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_950') . '</option>';
        $html .= '</select><span class="error">' . JText::_($errors['question_7']) . '</span></div>';
        return $html;
    }

}

?>