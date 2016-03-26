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
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        $html .= '<div class="form_header">' . JText::_('PLG_ISBNREGISTRY_FORMS_REGISTRATION_HEADER') . '</div>';
        $html .= '<div class="plg_isbnregistry_forms" id="plg_isbnregistry_forms_registration" >';
        $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_REGISTRATION_SUB_TITLE_1') . '</div>';
        $html .= '<form action="' . JURI::getInstance()->toString() . '" method="post" name="registerMonographPublisherForm" id="registerMonographPublisherForm">';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_OFFICIAL_NAME_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="official_name" id="official_name" size="30" value="' . $post->get('official_name', null, 'string') . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['official_name']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_OTHER_NAMES_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="other_names" id="other_names" size="30" value="' . $post->get('other_names', null, 'string') . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['other_names']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ADDRESS_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="address" id="address" size="30" value="' . $post->get('address', null, 'string') . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['address']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ZIP_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="zip" id="zip" size="5" value="' . $post->get('zip', null, 'string') . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['zip']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_CITY_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="city" id="city" size="20" value="' . $post->get('city', null, 'string') . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['city']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PHONE_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="phone" id="phone" size="10" value="' . $post->get('phone', null, 'string') . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['phone']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_EMAIL_FIELD') . ':</td>';
        // Content - Email Cloaking plugin must be disabled to get this work
        $html .= '<td><input type="text" id="email" name="email" size="30" value="' . $post->get('email', null, 'string') . '"  maxlength="100"/></td>';
        $html .= '<td class="error">* ' . JText::_($errors['email']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_WWW_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="www" id="www" size="30" value="' . $post->get('www', null, 'string') . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['www']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_CONTACT_PERSON_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="contact_person" id="contact_person" size="30" value="' . $post->get('contact_person', null, 'string') . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['contact_person']) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_REGISTRATION_SUB_TITLE_2') . '</div>';
        $html .= '<div class="pre_field">' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTIONS_PRE_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_1_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_1" id="question_1" class="question" size="30" value="' . $post->get('question_1', null, 'string') . '" />';
        $html .= '<span class="error">' . JText::_($errors['question_1']) . '</span></div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_2_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_2" id="question_2" class="question" size="30" value="' . $post->get('question_2', null, 'string') . '" />';
        $html .= '<span class="error">' . JText::_($errors['question_2']) . '</span></div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_3_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_3" id="question_3" class="question" size="30" value="' . $post->get('question_3', null, 'string') . '" />';
        $html .= '<span class="error">' . JText::_($errors['question_3']) . '</span></div>';
        $html .= '<div class="field_info">' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_3_POST_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_4_FIELD') . '</div>';
        $html .= '<div><textarea name="question_4" id="question_4" class="question">' . $post->get('question_4', null, 'string') . '</textarea>';
        $html .= '<span class="error">' . JText::_($errors['question_4']) . '</span></div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_5_FIELD') . '</div>';
        $html .= '<div><textarea name="question_5" id="question_5" class="question">' . $post->get('question_5', null, 'string') . '</textarea>';
        $html .= '<span class="error">' . JText::_($errors['question_5']) . '</span></div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_6_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_6" id="question_6" class="question" size="30" value="' . $post->get('question_6', null, 'string') . '" />';
        $html .= '<span  class="error">' . JText::_($errors['question_6']) . '</span></div>';
        $html .= '<div class="pre_field">' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_7_PRE_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_7_FIELD') . '</div>';
        $html .= self::getClassificationMenu($errors);
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_QUESTION_8_FIELD') . '</div>';
        $html .= '<div><input type="text" name="question_8" id="question_8" class="question" size="30" value="' . $post->get('question_8', null, 'string') . '" />';
        $html .= '<span class="error">' . JText::_($errors['question_8']) . '</span></div>';
        $html .= '<div class="pre_field">' . JText::_('PLG_ISBNREGISTRY_FORMS_CONFIRMATION_PRE_FIELD') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_CONFIRMATION_FIELD') . '<span  class="error"> *</span></div>';
        $html .= '<div><input type="text" name="confirmation" id="confirmation" size="30" value="';
        $confirmation = $post->get('confirmation', null, 'string');
        $html .= isset($confirmation) ? $confirmation : date("d.m.Y");
        $html .= '" />';
        $html .= '<span  class="error">' . JText::_($errors['confirmation']) . '</span></div>';
        $html .= '<div class="field_info">' . JText::_('PLG_ISBNREGISTRY_FORMS_CONFIRMATION_POST_FIELD') . '</div>';
        $html .= '<div><input type="submit" name="submit_registration" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_SEND_BTN') . '" /></div>';
        $html .= JHTML::_('form.token');
        $html .= '</form></div>';
        return $html;
    }

    public static function getIsbnApplicationFormPt1($errors = array()) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        $html .= '<div class="form_header">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_HEADER') . '</div>';
        $html .= '<div class="plg_isbnregistry_forms" id="plg_isbnregistry_forms_application" >';
        $html .= '<form action="' . JURI::getInstance()->toString() . '" method="post" name="isbnApplicationForm" id="isbnApplicationForm">';
        // Preliminary information about the publication
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_FIELD') . '</div>';
        $html .= '<div class="post_field"><select name="publication_type" id="publication_type">';
        $html .= '<option value="-"' . (($post->get('publication_type', null, 'string') == '-') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_SELECT') . '</option>';
        $html .= '<option value="BOOK"' . (($post->get('publication_type', null, 'string') == 'BOOK') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_BOOK') . '</option>';
        $html .= '<option value="DISSERTATION"' . (($post->get('publication_type', null, 'string') == 'DISSERTATION') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_DISSERTATION') . '</option>';
        $html .= '<option value="SHEET_MUSIC"' . (($post->get('publication_type', null, 'string') == 'SHEET_MUSIC') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_SHEET_MUSIC') . '</option>';
        $html .= '<option value="MAP"' . (($post->get('publication_type', null, 'string') == 'MAP') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_MAP') . '</option>';
        $html .= '<option value="OTHER"' . (($post->get('publication_type', null, 'string') == 'OTHER') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_OTHER') . '</option>';
        $html .= '</select><span class="error">* ' . JText::_($errors['publication_type']) . '</span></div>';
        $html .= '<div><input type="submit" name="submit_application_pt1" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_CONTINUE_BTN') . '" /></div>';
        $html .= self::getIsbnApplicationFormPt2Hidden();
        $backApplicationPt2 = $post->get('back_application_pt2', null, 'string');
        $submitApplicationPt4 = $post->get('submit_application_pt4', null, 'string');
        if (isset($backApplicationPt2) || isset($submitApplicationPt4)) {
            $html .= self::getIsbnApplicationFormPt3Hidden();
        }
        $html .= JHTML::_('form.token');
        $html .= '</form></div>';
        return $html;
    }

    public static function getIsbnApplicationFormPt2($errors = array()) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        // Information about the publisher
        if (IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            $html .= '<div class="form_header">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_HEADER_DISSERTATION') . '</div>';
        }
        $html .= '<div class="plg_isbnregistry_forms" id="plg_isbnregistry_forms_application" >';
        $html .= '<div class="sub_title">';
        if (IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            $html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_1_1');
        } else {
            $html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_1');
        }
        $html .= '</div>';
        $html .= '<form action="' . JURI::getInstance()->toString() . '" method="post" name="isbnApplicationForm" id="isbnApplicationForm">';
        $html .= '<table>';
        $html .= '<tr>';
        if (IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_UNIVERSITY') . ':</td>';
            $html .= '<td><input type="text" name="official_name" id="official_name" size="30" value="' . $post->get('official_name', null, 'string') . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['official_name']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_LOCALITY') . ':</td>';
            $html .= '<td><input type="text" name="locality" id="locality" size="30" value="' . $post->get('locality', null, 'string') . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['locality']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_FIRST_NAME') . ':</td>';
            $html .= '<td><input type="text" name="first_name" id="first_name" size="30" value="' . $post->get('first_name', null, 'string') . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['first_name']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_LAST_NAME') . ':</td>';
            $html .= '<td><input type="text" name="last_name" id="last_name" size="30" value="' . $post->get('last_name', null, 'string') . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['last_name']) . '</td>';
            $html .= '</tr><tr>';
        } else {
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_OFFICIAL_NAME_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="official_name" id="official_name" size="30" value="' . $post->get('official_name', null, 'string') . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['official_name']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHER_IDENTIFIER_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="publisher_identifier_str" id="publisher_identifier_str" size="30" value="' . $post->get('publisher_identifier_str', null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['publisher_identifier_str']) . '</td>';
            $html .= '</tr><tr>';
        }
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ADDRESS_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="address" id="address" size="30" value="' . $post->get('address', null, 'string') . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['address']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ZIP_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="zip" id="zip" size="5" value="' . $post->get('zip', null, 'string') . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['zip']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_CITY_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="city" id="city" size="20" value="' . $post->get('city', null, 'string') . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['city']) . '</td>';
        $html .= '</tr><tr>';
        if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_CONTACT_PERSON_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="contact_person" id="contact_person" size="30" value="' . $post->get('contact_person', null, 'string') . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['contact_person']) . '</td>';
            $html .= '</tr><tr>';
        }
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PHONE_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="phone" id="phone" size="15" value="' . $post->get('phone', null, 'string') . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['phone']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_EMAIL_FIELD') . ':</td>';
        // Content - Email Cloaking plugin must be disabled to get this work
        $html .= '<td><input type="text" id="email" name="email" size="30" value="' . $post->get('email', null, 'string') . '" maxlength="100"/></td>';
        $html .= '<td class="error">* ' . JText::_($errors['email']) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        // These fields are for for all the other publication types, but not for dissertations
        if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            // Information about publishing activities
            $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_2') . '</div>';
            $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHED_BEFORE_FIELD') . '</div>';
            $html .= '<div class="post_field"><select name="published_before" id="published_before">';
            $html .= '<option value="-"' . (($post->get('published_before', null, 'string') == '-') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_SELECT') . '</option>';
            $html .= '<option value="1"' . (($post->get('published_before', null, 'string') == '1') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_YES') . '</option>';
            $html .= '<option value="0"' . (($post->get('published_before', null, 'string') == '0') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_NO') . '</option>';
            $html .= '</select><span class="error">* ' . JText::_($errors['published_before']) . '</span></div>';
            $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATIONS_PUBLIC_FIELD') . '</div>';
            $html .= '<div class="post_field"><select name="publications_public" id="publications_public">';
            $html .= '<option value="-"' . (($post->get('publications_public', null, 'string') == '-') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_SELECT') . '</option>';
            $html .= '<option value="1"' . (($post->get('publications_public', null, 'string') == '1') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_YES') . '</option>';
            $html .= '<option value="0"' . (($post->get('publications_public', null, 'string') == '0') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_NO') . '</option>';
            $html .= '</select><span class="error">* ' . JText::_($errors['publications_public']) . '</span></div>';
            $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATIONS_INTRA_FIELD') . '</div>';
            $html .= '<div class="post_field"><select name="publications_intra" id="publications_intra">';
            $html .= '<option value="-"' . (($post->get('publications_intra', null, 'string') == '-') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_SELECT') . '</option>';
            $html .= '<option value="1"' . (($post->get('publications_intra', null, 'string') == '1') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_YES') . '</option>';
            $html .= '<option value="0"' . (($post->get('publications_intra', null, 'string') == '0') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_NO') . '</option>';
            $html .= '</select><span class="error">* ' . JText::_($errors['publications_intra']) . '</span></div>';
            $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_FIELD') . '</div>';
            $html .= '<div class="post_field"><select name="publishing_activity" id="publishing_activity">';
            $html .= '<option value="-"' . (($post->get('publishing_activity', null, 'string') == '-') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_SELECT') . '</option>';
            $html .= '<option value="OCCASIONAL"' . (($post->get('publishing_activity', null, 'string') == 'OCCASIONAL') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_OCCASIONAL') . '</option>';
            $html .= '<option value="CONTINUOUS"' . (($post->get('publishing_activity', null, 'string') == 'CONTINUOUS') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_CONTINUOUS') . '</option>';
            $html .= '</select><span class="error">* ' . JText::_($errors['publishing_activity']) . '</span></div>';
            $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_AMOUNT_FIELD') . '</div>';
            $html .= '<div class="post_field"><input type="text" name="publishing_activity_amount" id="publishing_activity_amount" size="5" class="question" value="' . $post->get('publishing_activity_amount', null, 'string') . '" />';
            $html .= '<span class="error"> ' . JText::_($errors['publishing_activity_amount']) . '</span></div>';
        }
        // Preliminary information about the publication
        $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_3') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_FORMAT_FIELD') . '</div>';
        $html .= '<div><select name="publication_format" id="publication_format">';
        $html .= '<option value="-"' . (($post->get('publication_format', null, 'string') == '-') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_SELECT') . '</option>';
        $html .= '<option value="PRINT"' . (($post->get('publication_format', null, 'string') == 'PRINT') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_FORMAT_PRINT') . '</option>';
        $html .= '<option value="ELECTRONICAL"' . (($post->get('publication_format', null, 'string') == 'ELECTRONICAL') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_FORMAT_ELECTRONICAL') . '</option>';
        $html .= '<option value="PRINT_ELECTRONICAL"' . (($post->get('publication_format', null, 'string') == 'PRINT_ELECTRONICAL') ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_FORMAT_BOTH') . '</option>';
        $html .= '</select><span class="error">* ' . JText::_($errors['publication_format']) . '</span></div>';
        $html .= '<div class="field_info">' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATION_FORMAT_POST_FIELD') . '</div>';
        $html .= '<div><input type="submit" name="back_application_pt2" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_BACK_BTN') . '" />';
        $html .= '<input type="submit" name="submit_application_pt2" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_CONTINUE_BTN') . '" /></div>';
        $html .= self::getIsbnApplicationFormPt1Hidden();
        $html .= self::getIsbnApplicationFormPt3Hidden();
        $html .= JHTML::_('form.token');
        $html .= '</form></div>';
        return $html;
    }

    public static function getIsbnApplicationFormPt3($errors = array()) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        $html .= '<div class="plg_isbnregistry_forms" id="plg_isbnregistry_forms_application" >';
        $html .= '<form action = "' . JURI::getInstance()->toString() . '" method = "post" name="isbnApplicationForm" id="isbnApplicationForm">';
        // Author info is not needed for dissertations - for other publication types it's mandatory		
        if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            // Information about the authors
            $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_4') . '</div>';
            $html .= '<table>';
            $html .= '<tr>';
            $html .= '<th></th>';
            $html .= '<th>' . JText::_('PLG_ISBNREGISTRY_FORMS_AUTHOR_FIELD') . '</th>';
            $html .= '<th></th>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FIRST_NAME_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="first_name_1" id="first_name_1" size="30" value="' . $post->get('first_name_1', null, 'string') . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['first_name_1']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_LAST_NAME_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="last_name_1" id="last_name_1" size="30" value="' . $post->get('last_name_1', null, 'string') . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['last_name_1']) . '</td>';
            $html .= '</tr><tr class="spacer_bottom">';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_FIELD') . ':</td>';
            $html .= '<td>';
            $role1 = $post->get('role_1', null, 'array');
            $html .= '<input type="checkbox" name="role_1[]" value="AUTHOR"' . (isset($role1) && in_array('AUTHOR', $role1) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_AUTHOR');
            $html .= '<input class="role_checkbox" type="checkbox" name="role_1[]" value="ILLUSTRATOR"' . (isset($role1) && in_array('ILLUSTRATOR', $role1) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_ILLUSTRATOR');
            $html .= '<input class="role_checkbox" type="checkbox" name="role_1[]" value="TRANSLATOR"' . (isset($role1) && in_array('TRANSLATOR', $role1) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_TRANSLATOR');
            $html .= '<input class="role_checkbox" type="checkbox" name="role_1[]" value="EDITOR"' . (isset($role1) && in_array('EDITOR', $role1) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_EDITOR');
            $html .= '</td>';
            $html .= '<td class="error">* ' . JText::_($errors['role_1']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<th></th>';
            $html .= '<th>' . JText::_('PLG_ISBNREGISTRY_FORMS_OTHER_AUTHORS_FIELD') . '</th>';
            $html .= '<th></th>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FIRST_NAME_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="first_name_2" id="first_name_2" size="30" value="' . $post->get('first_name_2', null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['first_name_2']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_LAST_NAME_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="last_name_2" id="last_name_2" size="30" value="' . $post->get('last_name_2', null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['last_name_2']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_FIELD') . ':</td>';
            $html .= '<td>';
            $role2 = $post->get('role_2', null, 'array');
            $html .= '<input type="checkbox" name="role_2[]" value="AUTHOR"' . (isset($role2) && in_array('AUTHOR', $role2) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_AUTHOR');
            $html .= '<input class="role_checkbox" type="checkbox" name="role_2[]" value="ILLUSTRATOR"' . (isset($role2) && in_array('ILLUSTRATOR', $role2) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_ILLUSTRATOR');
            $html .= '<input class="role_checkbox" type="checkbox" name="role_2[]" value="TRANSLATOR"' . (isset($role2) && in_array('TRANSLATOR', $role2) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_TRANSLATOR');
            $html .= '<input class="role_checkbox" type="checkbox" name="role_2[]" value="EDITOR"' . (isset($role2) && in_array('EDITOR', $role2) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_EDITOR');
            $html .= '</td>';
            $html .= '<td class="error">' . JText::_($errors['role_2']) . '</td>';
            $html .= '</tr><tr class="spacer_top">';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FIRST_NAME_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="first_name_3" id="first_name_3" size="30" value="' . $post->get('first_name_3', null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['first_name_3']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_LAST_NAME_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="last_name_3" id="last_name_3" size="30" value="' . $post->get('last_name_3', null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['last_name_3']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_FIELD') . ':</td>';
            $html .= '<td>';
            $role3 = $post->get('role_3', null, 'array');
            $html .= '<input type="checkbox" name="role_3[]" value="AUTHOR"' . (isset($role3) && in_array('AUTHOR', $role3) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_AUTHOR');
            $html .= '<input class="role_checkbox" type="checkbox" name="role_3[]" value="ILLUSTRATOR"' . (isset($role3) && in_array('ILLUSTRATOR', $role3) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_ILLUSTRATOR');
            $html .= '<input class="role_checkbox" type="checkbox" name="role_3[]" value="TRANSLATOR"' . (isset($role3) && in_array('TRANSLATOR', $role3) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_TRANSLATOR');
            $html .= '<input class="role_checkbox" type="checkbox" name="role_3[]" value="EDITOR"' . (isset($role3) && in_array('EDITOR', $role3) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_EDITOR');
            $html .= '</td>';
            $html .= '<td class="error">' . JText::_($errors['role_3']) . '</td>';
            $html .= '</tr><tr class="spacer_top">';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FIRST_NAME_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="first_name_4" id="first_name_4" size="30" value="' . $post->get('first_name_4', null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['first_name_4']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_LAST_NAME_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="last_name_4" id="last_name_4" size="30" value="' . $post->get('last_name_4', null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['last_name_4']) . '</td>';
            $html .= '</tr><tr class="spacer_bottom">';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_FIELD') . ':</td>';
            $html .= '<td>';
            $role4 = $post->get('role_4', null, 'array');
            $html .= '<input type="checkbox" name="role_4[]" value="AUTHOR"' . (isset($role4) && in_array('AUTHOR', $role4) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_AUTHOR');
            $html .= '<input class="role_checkbox" type="checkbox" name="role_4[]" value="ILLUSTRATOR"' . (isset($role4) && in_array('ILLUSTRATOR', $role4) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_ILLUSTRATOR');
            $html .= '<input class="role_checkbox" type="checkbox" name="role_4[]" value="TRANSLATOR"' . (isset($role4) && in_array('TRANSLATOR', $role4) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_TRANSLATOR');
            $html .= '<input class="role_checkbox" type="checkbox" name="role_4[]" value="EDITOR"' . (isset($role4) && in_array('EDITOR', $role4) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_ROLE_EDITOR');
            $html .= '</td>';
            $html .= '<td class="error">' . JText::_($errors['role_4']) . '</td>';
            $html .= '</tr>';
            $html .= '</table>';
        }
        // Information about the publication
        $html .= '<div class="sub_title">';
        if (IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            $html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_5_1');
        } else {
            $html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_5');
        }
        $html .= '</div>';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_TITLE_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="title" id="title" size="50" value="' . $post->get('title', null, 'string') . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['title']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_SUBTITLE_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="subtitle" id="title" size="50" value="' . $post->get('subtitle', null, 'string') . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['subtitle']) . '</td>';
        $html .= '</tr><tr>';
        if (IsbnregistryFormsHelper::isMap($post->get('publication_type', null, 'string'))) {
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_MAP_SCALE_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="map_scale" id="map_scale" size="15" value="' . $post->get('map_scale', null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['map_scale']) . '</td>';
            $html .= '</tr><tr>';
        }
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_LANGUAGE_FIELD') . ':</td>';
        $html .= '<td>';
        $html .= self::getLanguageMenu();
        $html .= '</td>';
        $html .= '<td class="error">* ' . JText::_($errors['language']) . '</td>';
        $html .= '</tr><tr class="spacer_bottom">';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHED_FIELD') . ':</td>';
        $html .= '<td>';
        $html .= self::getPublishedYearMenu();
        $html .= self::getPublishedMonthMenu();
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
        $html .= '<td><input type="text" name="series" id="series" size="50" value="' . $post->get('series', null, 'string') . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['series']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ISSN_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="issn" id="issn" size="9" value="' . $post->get('issn', null, 'string') . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['issn']) . '</td>';
        $html .= '</tr><tr class="spacer_bottom">';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_VOLUME_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="volume" id="volume" size="2" value="' . $post->get('volume', null, 'string') . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['volume']) . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        if (IsbnregistryFormsHelper::isPrint($post->get('publication_format', null, 'string'))) {
            // Information about the printed publication
            $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_7') . '</div>';
            $html .= '<table>';
            $html .= '<tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PRINTING_HOUSE_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="printing_house" id="printing_house" size="50" value="' . $post->get('printing_house', null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['printing_house']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PRINTING_HOUSE_CITY_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="printing_house_city" id="printing_house_city" size="50" value="' . $post->get('printing_house_city', null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['printing_house_city']) . '</td>';
            $html .= '</tr>';
            // Copies and edition fields are shown only if the publication is not a dissertation
            if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
                $html .= '<tr>';
                $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_COPIES_FIELD') . ':</td>';
                $html .= '<td><input type="text" name="copies" id="copies" size="4" value="' . $post->get('copies', null, 'string') . '" /></td>';
                $html .= '<td class="error">' . JText::_($errors['copies']) . '</td>';
                $html .= '</tr><tr>';
                $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_EDITION_FIELD') . ':</td>';
                $html .= '<td>';
                $html .= self::getEditionMenu();
                $html .= '</td>';
                $html .= '<td class="error">' . JText::_($errors['edition']) . '</td>';
                $html .= '</tr>';
            }
            $html .= '<tr class="spacer_bottom">';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_TYPE_FIELD') . ':</td>';
            $html .= '<td>';
            $type = $post->get('type', null, 'array');
            $html .= '<input type="checkbox" name="type[]" value="PAPERBACK"' . (isset($type) && in_array('PAPERBACK', $type) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_TYPE_PAPERBACK');
            // HARDBACK and SPIRAL_BINDING are shown only if the publication is not a dissertation
            if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
                $html .= '<input class="role_checkbox" type="checkbox" name="type[]" value="HARDBACK"' . (isset($type) && in_array('HARDBACK', $type) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_TYPE_HARDBACK');
                $html .= '<input class="role_checkbox" type="checkbox" name="type[]" value="SPIRAL_BINDING"' . (isset($type) && in_array('SPIRAL_BINDING', $type) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_TYPE_SPIRAL_BINDING');
            }
            $html .= '<input class="role_checkbox" type="checkbox" name="type[]" value="OTHER_PRINT"' . (isset($type) && in_array('OTHER_PRINT', $type) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_TYPE_OTHER_PRINT');
            $html .= '</td>';
            $html .= '<td class="error">* ' . JText::_($errors['type']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_TYPE_OTHER_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="type_other" id="type_other" size="25" value="' . $post->get('type_other', null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['type_other']) . '</td>';
            $html .= '</tr>';
            $html .= '</table>';
        }
        // Additional information
        $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_8') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISBNREGISTRY_FORMS_COMMENTS_FIELD') . '</div>';
        $html .= '<div class="spacer_bottom"><textarea name="comments" id="comments" class="question">' . $post->get('comments', null, 'string') . '</textarea>';
        $html .= '<span class="error">' . JText::_($errors['comments']) . '</span></div>';
        if (IsbnregistryFormsHelper::isElectronical($post->get('publication_format', null, 'string'))) {
            // Other information
            $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_9') . '</div>';
            $html .= '<table>';
            $html .= '<tr class="spacer_bottom">';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_FIELD') . ':</td>';
            $html .= '<td>';
            $fileformat = $post->get('fileformat', null, 'array');
            $html .= '<input class="role_checkbox" type="checkbox" name="fileformat[]" value="PDF"' . (isset($fileformat) && in_array('PDF', $fileformat) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_PDF');
            // EPUB and CD_ROM are shown only if the publication is not a dissertation
            if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
                $html .= '<input class="role_checkbox" type="checkbox" name="fileformat[]" value="EPUB"' . (isset($fileformat) && in_array('EPUB', $fileformat) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_EPUB');
                $html .= '<input class="role_checkbox" type="checkbox" name="fileformat[]" value="CD_ROM"' . (isset($fileformat) && in_array('CD_ROM', $fileformat) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_CD_ROM');
            }
            $html .= '<input class="role_checkbox" type="checkbox" name="fileformat[]" value="OTHER"' . (isset($fileformat) && in_array('OTHER', $fileformat) ? ' checked' : '') . '/>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_OTHER');
            $html .= '</td>';
            $html .= '<td class="error">* ' . JText::_($errors['fileformat']) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_OTHER_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="fileformat_other" id="fileformat_other" size="25" value="' . $post->get('fileformat_other', null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['fileformat_other']) . '</td>';
            $html .= '</tr>';
            $html .= '</table>';
        }
        $html .= self::getIsbnApplicationFormPt1Hidden();
        $html .= self::getIsbnApplicationFormPt2Hidden();
        $html .= '<div><input type="submit" name="back_application_pt3" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_BACK_BTN') . '" />';
        $html .= '<input type="submit" name="submit_application_pt3" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_CONTINUE_BTN') . '" /></div>';
        $html .= JHTML::_('form.token');
        $html .= '</form></div>';
        return $html;
    }

    public static function getIsbnApplicationFormPt4() {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        // Information about the publisher
        $html .= '<div class="form_header">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUMMARY_HEADER') . '</div>';
        $html .= '<div class="plg_isbnregistry_forms" id="plg_isbnregistry_forms_application_summary" >';
        $html .= '<div class="sub_title">';
        if (IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            $html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_1_1');
        } else {
            $html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_1');
        }
        $html .= '</div>';
        $html .= '<table>';
        $html .= '<tr>';
        if (IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_UNIVERSITY') . ':</td>';
            $html .= '<td>' . $post->get('official_name', null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_LOCALITY') . ':</td>';
            $html .= '<td>' . $post->get('locality', null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_DISSERTATION_DOCTOR_CANDIDATE') . ':</td>';
            $html .= '<td>' . $post->get('first_name', null, 'string') . ' ' . $post->get('last_name', null, 'string') . '</td>';
            $html .= '</tr><tr>';
        } else {
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHER_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('official_name', null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHER_IDENTIFIER_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('publisher_identifier_str', null, 'string') . '</td>';
            $html .= '</tr><tr>';
        }
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ADDRESS_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('address', null, 'string') . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_ZIP_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('zip', null, 'string') . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_CITY_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('city', null, 'string') . '</td>';
        $html .= '</tr><tr>';
        if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_CONTACT_PERSON_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('contact_person', null, 'string') . '</td>';
            $html .= '</tr><tr>';
        }
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PHONE_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('phone', null, 'string') . '</td>';
        $html .= '</tr><tr class="spacer_bottom">';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_EMAIL_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('email', null, 'string') . '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            // Information about publishing activities
            $html .= '<div class="sub_title">' . JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_2') . '</div>';
            $html .= '<table>';
            $html .= '<tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHED_BEFORE_SUMMARY_FIELD') . ':</td>';
            $html .= '<td>' . ($post->get('published_before', false, 'boolean') ? JText::_('PLG_ISBNREGISTRY_FORMS_YES') : JText::_('PLG_ISBNREGISTRY_FORMS_NO')) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATIONS_PUBLIC_SUMMARY_FIELD') . ':</td>';
            $html .= '<td>' . ($post->get('publications_public', false, 'boolean') ? JText::_('PLG_ISBNREGISTRY_FORMS_YES') : JText::_('PLG_ISBNREGISTRY_FORMS_NO')) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLICATIONS_INTRA_SUMMARY_FIELD') . ':</td>';
            $html .= '<td>' . ($post->get('publications_intra', false, 'boolean') ? JText::_('PLG_ISBNREGISTRY_FORMS_YES') : JText::_('PLG_ISBNREGISTRY_FORMS_NO')) . '</td>';
            $html .= '</tr><tr class="spacer_bottom">';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_SUMMARY_FIELD') . ':</td>';
            $html .= '<td>' . ((strcmp($post->get('publishing_activity', null, 'string'), 'OCCASIONAL') == 0) ? JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_OCCASIONAL') : JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_CONTINUOUS')) . '</td>';
            $html .= '</tr>';
            $html .= '</table>';
        }
        // Information about the publication
        $html .= '<div class="sub_title">';
        if (IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            $html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_5_1');
        } else {
            $html .= JText::_('PLG_ISBNREGISTRY_FORMS_APPLICATION_SUB_TITLE_5');
        }
        $html .= '</div>';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_AUTHORS_FIELD') . ':</td>';
        $html .= '<td>';
        if (IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            $html .= $post->get('first_name', null, 'string') . ' ' . $post->get('last_name', null, 'string');
        } else {
            $html .= IsbnregistryFormsHelper::buildAuthorsField();
        }
        $html .= '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_TITLE_SUMMARY_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('title', null, 'string') . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_SUBTITLE_SUMMARY_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('subtitle', null, 'string') . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHED_FIELD') . ':</td>';
        $html .= '<td>' . IsbnregistryFormsHelper::getPublishedDateString() . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_LANGUAGE_SUMMARY_FIELD') . ':</td>';
        $html .= '<td>' . IsbnregistryFormsHelper::getLanguageLabel() . '</td>';
        if (IsbnregistryFormsHelper::isPrint($post->get('publication_format', null, 'string'))) {
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_TYPE_FIELD') . ':</td>';
            $html .= '<td>' . IsbnregistryFormsHelper::getTypeString() . '</td>';
        }
        if (IsbnregistryFormsHelper::isElectronical($post->get('publication_format', null, 'string'))) {
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_FIELD') . ':</td>';
            $html .= '<td>' . IsbnregistryFormsHelper::getFileFormatString() . '</td>';
        }
        $html .= '</tr>';
        $html .= '</table><br />';
        $html .= '<form action = "' . JURI::getInstance()->toString() . '" method = "post" name="isbnApplicationForm" id="isbnApplicationForm">';
        $html .= self::getIsbnApplicationFormPt1Hidden();
        $html .= self::getIsbnApplicationFormPt2Hidden();
        $html .= self::getIsbnApplicationFormPt3Hidden();
        $html .= '<div><input type="submit" name="back_application_pt4" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_BACK_BTN') . '" />';
        $html .= '<input type="submit" name="submit_application_pt4" value="' . JText::_('PLG_ISBNREGISTRY_FORMS_SEND_BTN') . '" /></div>';
        $html .= JHTML::_('form.token');
        $html .= '</form></div>';
        return $html;
    }

    private static function getIsbnApplicationFormPt1Hidden() {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        $html .= '<input type="hidden" name="publication_type" value="' . $post->get('publication_type', null, 'string') . '" />';
        return $html;
    }

    private static function getIsbnApplicationFormPt2Hidden() {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        $html .= '<input type="hidden" name="official_name" value="' . $post->get('official_name', null, 'string') . '" />';
        $html .= '<input type="hidden" name="address" value="' . $post->get('address', null, 'string') . '" />';
        $html .= '<input type="hidden" name="zip" value="' . $post->get('zip', null, 'string') . '" />';
        $html .= '<input type="hidden" name="city" value="' . $post->get('city', null, 'string') . '" />';
        $html .= '<input type="hidden" name="phone" value="' . $post->get('phone', null, 'string') . '" />';
        $html .= '<input type="hidden" name="email" value="' . $post->get('email', null, 'string') . '" />';
        if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            $html .= '<input type="hidden" name="contact_person" value="' . $post->get('contact_person', null, 'string') . '" />';
            $html .= '<input type="hidden" name="publisher_identifier_str" value="' . $post->get('publisher_identifier_str', null, 'string') . '" />';
            $html .= '<input type="hidden" name="published_before" value="' . $post->get('published_before', null, 'string') . '" />';
            $html .= '<input type="hidden" name="publications_public" value="' . $post->get('publications_public', null, 'string') . '" />';
            $html .= '<input type="hidden" name="publications_intra" value="' . $post->get('publications_intra', null, 'string') . '" />';
            $html .= '<input type="hidden" name="publishing_activity" value="' . $post->get('publishing_activity', null, 'string') . '" />';
            $html .= '<input type="hidden" name="publishing_activity_amount" value="' . $post->get('publishing_activity_amount', null, 'string') . '" />';
        } else {
            $html .= '<input type="hidden" name="first_name" value="' . $post->get('first_name', null, 'string') . '" />';
            $html .= '<input type="hidden" name="last_name" value="' . $post->get('last_name', null, 'string') . '" />';
            $html .= '<input type ="hidden" name ="locality" value="' . $post->get('locality', null, 'string') . '" />';
        }
        $html .= '<input type="hidden" name="publication_format" value="' . $post->get('publication_format', null, 'string') . '" />';
        return $html;
    }

    private static function getIsbnApplicationFormPt3Hidden() {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        // Author info is included only if the publication is not a dissertation
        if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
            $html .= '<input type="hidden" name="first_name_1" value="' . $post->get('first_name_1', null, 'string') . '" />';
            $html .= '<input type="hidden" name="last_name_1" value="' . $post->get('last_name_1', null, 'string') . '" />';
            $role1 = $post->get('role_1', null, 'array');
            $html .= '<input style="display:none;" type="checkbox" name="role_1[]" value="AUTHOR"' . (isset($role1) && in_array('AUTHOR', $role1) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="role_1[]" value="ILLUSTRATOR"' . (isset($role1) && in_array('ILLUSTRATOR', $role1) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="role_1[]" value="TRANSLATOR"' . (isset($role1) && in_array('TRANSLATOR', $role1) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="role_1[]" value="EDITOR"' . (isset($role1) && in_array('EDITOR', $role1) ? ' checked' : '') . '/>';
            $html .= '<input type="hidden" name="first_name_2" value="' . $post->get('first_name_2', null, 'string') . '" />';
            $html .= '<input type="hidden" name="last_name_2" value="' . $post->get('last_name_2', null, 'string') . '" />';
            $role2 = $post->get('role_2', null, 'array');
            $html .= '<input style="display:none;" type="checkbox" name="role_2[]" value="AUTHOR"' . (isset($role2) && in_array('AUTHOR', $role2) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="role_2[]" value="ILLUSTRATOR"' . (isset($role2) && in_array('ILLUSTRATOR', $role2) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="role_2[]" value="TRANSLATOR"' . (isset($role2) && in_array('TRANSLATOR', $role2) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="role_2[]" value="EDITOR"' . (isset($role2) && in_array('EDITOR', $role2) ? ' checked' : '') . '/>';
            $html .= '<input type="hidden" name="first_name_3" value="' . $post->get('first_name_3', null, 'string') . '" />';
            $html .= '<input type="hidden" name="last_name_3" value="' . $post->get('last_name_3', null, 'string') . '" />';
            $role3 = $post->get('role_3', null, 'array');
            $html .= '<input style="display:none;" type="checkbox" name="role_3[]" value="AUTHOR"' . (isset($role3) && in_array('AUTHOR', $role3) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="role_3[]" value="ILLUSTRATOR"' . (isset($role3) && in_array('ILLUSTRATOR', $role3) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="role_3[]" value="TRANSLATOR"' . (isset($role3) && in_array('TRANSLATOR', $role3) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="role_3[]" value="EDITOR"' . (isset($role3) && in_array('EDITOR', $role3) ? ' checked' : '') . '/>';
            $html .= '<input type="hidden" name="first_name_4" value="' . $post->get('first_name_4', null, 'string') . '" />';
            $html .= '<input type="hidden" name="last_name_4" value="' . $post->get('last_name_4', null, 'string') . '" />';
            $role4 = $post->get('role_4', null, 'array');
            $html .= '<input style="display:none;" type="checkbox" name="role_4[]" value="AUTHOR"' . (isset($role4) && in_array('AUTHOR', $role4) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="role_4[]" value="ILLUSTRATOR"' . (isset($role4) && in_array('ILLUSTRATOR', $role4) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="role_4[]" value="TRANSLATOR"' . (isset($role4) && in_array('TRANSLATOR', $role4) ? ' checked' : '') . '/>';
            $html .= '<input style="display:none;" type="checkbox" name="role_4[]" value="EDITOR"' . (isset($role4) && in_array('EDITOR', $role4) ? ' checked' : '') . '/>';
        }
        $html .= '<input type="hidden" name="title" value="' . $post->get('title', null, 'string') . '" />';
        $html .= '<input type="hidden" name="subtitle" value="' . $post->get('subtitle', null, 'string') . '" />';
        if (IsbnregistryFormsHelper::isMap($post->get('publication_type', null, 'string'))) {
            $html .= '<input type="hidden" name="map_scale" value="' . $post->get('map_scale', null, 'string') . '" />';
        }
        $html .= '<input type="hidden" name="language" value="' . $post->get('language', null, 'string') . '" />';
        $html .= '<input type="hidden" name="year" value="' . $post->get('year', null, 'string') . '" />';
        $html .= '<input type="hidden" name="month" value="' . $post->get('month', null, 'string') . '" />';
        $html .= '<input type="hidden" name="series" value="' . $post->get('series', null, 'string') . '" />';
        $html .= '<input type="hidden" name="issn" value="' . $post->get('issn', null, 'string') . '" />';
        $html .= '<input type="hidden" name="volume" value="' . $post->get('volume', null, 'string') . '" />';
        if (IsbnregistryFormsHelper::isPrint($post->get('publication_format', null, 'string'))) {
            // Information about the printed publication
            $html .= '<input type="hidden" name="printing_house" value="' . $post->get('printing_house', null, 'string') . '" />';
            $html .= '<input type="hidden" name="printing_house_city" value="' . $post->get('printing_house_city', null, 'string') . '" />';
            // Copies and edition fields are included only if the publication is not a dissertation
            if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
                $html .= '<input type="hidden" name="copies" value="' . $post->get('copies', null, 'string') . '" />';
                $html .= '<input type="hidden" name="edition" value="' . $post->get('edition', null, 'string') . '" />';
            }
            $type = $post->get('type', null, 'array');
            $html .= '<input style="display:none;" type="checkbox" name="type[]" value="PAPERBACK"' . (isset($type) && in_array('PAPERBACK', $type) ? ' checked' : '') . '/>';
            if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
                $html .= '<input style="display:none;" type="checkbox" name="type[]" value="HARDBACK"' . (isset($type) && in_array('HARDBACK', $type) ? ' checked' : '') . '/>';
                $html .= '<input style="display:none;" type="checkbox" name="type[]" value="SPIRAL_BINDING"' . (isset($type) && in_array('SPIRAL_BINDING', $type) ? ' checked' : '') . '/>';
            }
            $html .= '<input style="display:none;" type="checkbox" name="type[]" value="OTHER_PRINT"' . (isset($type) && in_array('OTHER_PRINT', $type) ? ' checked' : '') . '/>';
            $html .= '<input type="hidden" name="type_other" value="' . $post->get('type_other', null, 'string') . '" />';
        }
        // Additional information
        $html .= '<textarea name="comments" style="display:none;">' . $post->get('comments', null, 'string') . '</textarea>';
        if (IsbnregistryFormsHelper::isElectronical($post->get('publication_format', null, 'string'))) {
            $fileformat = $post->get('fileformat', null, 'array');
            $html .= '<input style="display:none;" type="checkbox" name="fileformat[]" value="PDF"' . (isset($fileformat) && in_array('PDF', $fileformat) ? ' checked' : '') . '/>';
            if (!IsbnregistryFormsHelper::isDissertation($post->get('publication_type', null, 'string'))) {
                $html .= '<input style="display:none;" type="checkbox" name="fileformat[]" value="EPUB"' . (isset($fileformat) && in_array('EPUB', $fileformat) ? ' checked' : '') . '/>';
                $html .= '<input style="display:none;" type="checkbox" name="fileformat[]" value="CD_ROM"' . (isset($fileformat) && in_array('CD_ROM', $fileformat) ? ' checked' : '') . '/>';
            }
            $html .= '<input style="display:none;" type="checkbox" name="fileformat[]" value="OTHER"' . (isset($fileformat) && in_array('OTHER', $fileformat) ? ' checked' : '') . '/>';
            $html .= '<input type="hidden" name="fileformat_other" value="' . $post->get('fileformat_other', null, 'string') . '" />';
        }
        return $html;
    }

    private static function getLanguageMenu() {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        $userLanguage = $post->get('language', null, 'string');

        $html .= '<select name="language" id="language">';
        $html .= '<option value="-"' . (strcmp($userLanguage, '-') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_SELECT') . '</option>';
        foreach (IsbnregistryFormsHelper::getLanguageList() as $language) {
            $html .= '<option value="' . $language . '"' . (strcmp($userLanguage, $language) == 0 ? ' selected' : '') . '>' . JText::_("PLG_ISBNREGISTRY_FORMS_LANGUAGE_$language") . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    private static function getPublishedYearMenu() {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        $year = $post->get('year', null, 'string');

        $html .= '<select name="year" id="year">';
        $html .= '<option value="-"' . (strcmp($year, '-') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHED_YEAR') . '</option>';
        $html .= '<option value="' . (date("Y") - 2) . '"' . ($year == (date("Y") - 2) ? ' selected' : '') . '>' . (date("Y") - 2) . '</option>';
        $html .= '<option value="' . (date("Y") - 1) . '"' . ($year == (date("Y") - 1) ? ' selected' : '') . '>' . (date("Y") - 1) . '</option>';
        $html .= '<option value="' . (date("Y")) . '"' . ($year == date("Y") ? ' selected' : '') . '>' . (date("Y")) . '</option>';
        $html .= '<option value="' . (date("Y") + 1) . '"' . ($year == (date("Y") + 1) ? ' selected' : '') . '>' . (date("Y") + 1) . '</option>';
        $html .= '<option value="' . (date("Y") + 2) . '"' . ($year == (date("Y") + 2) ? ' selected' : '') . '>' . (date("Y") + 2) . '</option>';
        $html .= '</select>';
        return $html;
    }

    private static function getPublishedMonthMenu() {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        $month = $post->get('month', null, 'string');

        $html .= '<select name="month" id="month">';
        $html .= '<option value="-"' . (strcmp($month, '-') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_PUBLISHED_MONTH') . '</option>';
        $html .= '<option value="01"' . (strcmp($month, '01') == 0 ? ' selected' : '') . '>01</option>';
        $html .= '<option value="02"' . (strcmp($month, '02') == 0 ? ' selected' : '') . '>02</option>';
        $html .= '<option value="03"' . (strcmp($month, '03') == 0 ? ' selected' : '') . '>03</option>';
        $html .= '<option value="04"' . (strcmp($month, '04') == 0 ? ' selected' : '') . '>04</option>';
        $html .= '<option value="05"' . (strcmp($month, '05') == 0 ? ' selected' : '') . '>05</option>';
        $html .= '<option value="06"' . (strcmp($month, '06') == 0 ? ' selected' : '') . '>06</option>';
        $html .= '<option value="07"' . (strcmp($month, '07') == 0 ? ' selected' : '') . '>07</option>';
        $html .= '<option value="08"' . (strcmp($month, '08') == 0 ? ' selected' : '') . '>08</option>';
        $html .= '<option value="09"' . (strcmp($month, '09') == 0 ? ' selected' : '') . '>09</option>';
        $html .= '<option value="10"' . (strcmp($month, '10') == 0 ? ' selected' : '') . '>10</option>';
        $html .= '<option value="11"' . (strcmp($month, '11') == 0 ? ' selected' : '') . '>11</option>';
        $html .= '<option value="12"' . (strcmp($month, '12') == 0 ? ' selected' : '') . '>12</option>';
        $html .= '</select>';
        return $html;
    }

    private static function getEditionMenu() {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        $html .= '<select name="edition" id="edition">';
        $html .= '<option value="-"' . (strcmp($post->get('year', null, 'string'), '-') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_SELECT') . '</option>';
        for ($x = 1; $x <= 10; $x++) {
            $html .= '<option value="' . $x . '"' . (strcmp($post->get('edition', null, 'string'), $x) == 0 ? ' selected' : '') . '>' . $x . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    private static function getClassificationMenu($errors = array()) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        $question7 = $post->get('question_7', null, 'array');

        $html .= '<div>';
        $html .= '<select name="question_7[]" data-placeholder = "' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_SELECT') . '" multiple = "multiple" id="question_7">';
        $html .= '<option value="000"' . (isset($question7) && in_array('000', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_000') . '</option>';
        $html .= '<option value="015"' . (isset($question7) && in_array('015', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_015') . '</option>';
        $html .= '<option value="030"' . (isset($question7) && in_array('030', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_030') . '</option>';
        $html .= '<option value="035"' . (isset($question7) && in_array('035', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_035') . '</option>';
        $html .= '<option value="040"' . (isset($question7) && in_array('040', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_040') . '</option>';
        $html .= '<option value="045"' . (isset($question7) && in_array('045', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_045') . '</option>';
        $html .= '<option value="050"' . (isset($question7) && in_array('050', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_050') . '</option>';
        $html .= '<option value="055"' . (isset($question7) && in_array('055', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_055') . '</option>';
        $html .= '<option value="100"' . (isset($question7) && in_array('100', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_100') . '</option>';
        $html .= '<option value="120"' . (isset($question7) && in_array('120', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_120') . '</option>';
        $html .= '<option value="130"' . (isset($question7) && in_array('130', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_130') . '</option>';
        $html .= '<option value="200"' . (isset($question7) && in_array('200', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_200') . '</option>';
        $html .= '<option value="210"' . (isset($question7) && in_array('210', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_210') . '</option>';
        $html .= '<option value="211"' . (isset($question7) && in_array('211', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_211') . '</option>';
        $html .= '<option value="270"' . (isset($question7) && in_array('270', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_270') . '</option>';
        $html .= '<option value="300"' . (isset($question7) && in_array('300', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_300') . '</option>';
        $html .= '<option value="310"' . (isset($question7) && in_array('310', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_310') . '</option>';
        $html .= '<option value="315"' . (isset($question7) && in_array('315', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_315') . '</option>';
        $html .= '<option value="316"' . (isset($question7) && in_array('316', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_316') . '</option>';
        $html .= '<option value="320"' . (isset($question7) && in_array('320', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_320') . '</option>';
        $html .= '<option value="330"' . (isset($question7) && in_array('330', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_330') . '</option>';
        $html .= '<option value="340"' . (isset($question7) && in_array('340', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_340') . '</option>';
        $html .= '<option value="350"' . (isset($question7) && in_array('350', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_350') . '</option>';
        $html .= '<option value="370"' . (isset($question7) && in_array('370', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_370') . '</option>';
        $html .= '<option value="375"' . (isset($question7) && in_array('375', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_375') . '</option>';
        $html .= '<option value="380"' . (isset($question7) && in_array('380', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_380') . '</option>';
        $html .= '<option value="390"' . (isset($question7) && in_array('390', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_390') . '</option>';
        $html .= '<option value="400"' . (isset($question7) && in_array('400', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_400') . '</option>';
        $html .= '<option value="410"' . (isset($question7) && in_array('410', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_410') . '</option>';
        $html .= '<option value="420"' . (isset($question7) && in_array('420', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_420') . '</option>';
        $html .= '<option value="440"' . (isset($question7) && in_array('440', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_440') . '</option>';
        $html .= '<option value="450"' . (isset($question7) && in_array('450', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_450') . '</option>';
        $html .= '<option value="460"' . (isset($question7) && in_array('460', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_460') . '</option>';
        $html .= '<option value="470"' . (isset($question7) && in_array('470', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_470') . '</option>';
        $html .= '<option value="480"' . (isset($question7) && in_array('480', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_480') . '</option>';
        $html .= '<option value="490"' . (isset($question7) && in_array('490', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_490') . '</option>';
        $html .= '<option value="500"' . (isset($question7) && in_array('500', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_500') . '</option>';
        $html .= '<option value="510"' . (isset($question7) && in_array('510', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_510') . '</option>';
        $html .= '<option value="520"' . (isset($question7) && in_array('520', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_520') . '</option>';
        $html .= '<option value="530"' . (isset($question7) && in_array('530', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_530') . '</option>';
        $html .= '<option value="540"' . (isset($question7) && in_array('540', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_540') . '</option>';
        $html .= '<option value="550"' . (isset($question7) && in_array('550', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_550') . '</option>';
        $html .= '<option value="560"' . (isset($question7) && in_array('560', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_560') . '</option>';
        $html .= '<option value="570"' . (isset($question7) && in_array('570', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_570') . '</option>';
        $html .= '<option value="580"' . (isset($question7) && in_array('580', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_580') . '</option>';
        $html .= '<option value="590"' . (isset($question7) && in_array('590', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_590') . '</option>';
        $html .= '<option value="600"' . (isset($question7) && in_array('600', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_600') . '</option>';
        $html .= '<option value="610"' . (isset($question7) && in_array('610', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_610') . '</option>';
        $html .= '<option value="620"' . (isset($question7) && in_array('620', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_620') . '</option>';
        $html .= '<option value="621"' . (isset($question7) && in_array('621', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_621') . '</option>';
        $html .= '<option value="622"' . (isset($question7) && in_array('622', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_622') . '</option>';
        $html .= '<option value="630"' . (isset($question7) && in_array('630', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_630') . '</option>';
        $html .= '<option value="640"' . (isset($question7) && in_array('640', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_640') . '</option>';
        $html .= '<option value="650"' . (isset($question7) && in_array('650', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_650') . '</option>';
        $html .= '<option value="660"' . (isset($question7) && in_array('660', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_660') . '</option>';
        $html .= '<option value="670"' . (isset($question7) && in_array('670', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_670') . '</option>';
        $html .= '<option value="672"' . (isset($question7) && in_array('672', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_672') . '</option>';
        $html .= '<option value="680"' . (isset($question7) && in_array('680', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_680') . '</option>';
        $html .= '<option value="690"' . (isset($question7) && in_array('690', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_690') . '</option>';
        $html .= '<option value="700"' . (isset($question7) && in_array('700', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_700') . '</option>';
        $html .= '<option value="710"' . (isset($question7) && in_array('710', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_710') . '</option>';
        $html .= '<option value="720"' . (isset($question7) && in_array('720', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_720') . '</option>';
        $html .= '<option value="730"' . (isset($question7) && in_array('730', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_730') . '</option>';
        $html .= '<option value="740"' . (isset($question7) && in_array('740', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_740') . '</option>';
        $html .= '<option value="750"' . (isset($question7) && in_array('750', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_750') . '</option>';
        $html .= '<option value="760"' . (isset($question7) && in_array('760', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_760') . '</option>';
        $html .= '<option value="765"' . (isset($question7) && in_array('765', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_765') . '</option>';
        $html .= '<option value="770"' . (isset($question7) && in_array('770', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_770') . '</option>';
        $html .= '<option value="780"' . (isset($question7) && in_array('780', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_780') . '</option>';
        $html .= '<option value="790"' . (isset($question7) && in_array('790', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_790') . '</option>';
        $html .= '<option value="800"' . (isset($question7) && in_array('800', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_800') . '</option>';
        $html .= '<option value="810"' . (isset($question7) && in_array('810', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_810') . '</option>';
        $html .= '<option value="820"' . (isset($question7) && in_array('820', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_820') . '</option>';
        $html .= '<option value="830"' . (isset($question7) && in_array('830', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_830') . '</option>';
        $html .= '<option value="840"' . (isset($question7) && in_array('840', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_840') . '</option>';
        $html .= '<option value="850"' . (isset($question7) && in_array('850', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_850') . '</option>';
        $html .= '<option value="860"' . (isset($question7) && in_array('860', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_860') . '</option>';
        $html .= '<option value="870"' . (isset($question7) && in_array('870', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_870') . '</option>';
        $html .= '<option value="880"' . (isset($question7) && in_array('880', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_880') . '</option>';
        $html .= '<option value="890"' . (isset($question7) && in_array('890', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_890') . '</option>';
        $html .= '<option value="900"' . (isset($question7) && in_array('900', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_900') . '</option>';
        $html .= '<option value="910"' . (isset($question7) && in_array('910', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_910') . '</option>';
        $html .= '<option value="920"' . (isset($question7) && in_array('920', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_920') . '</option>';
        $html .= '<option value="930"' . (isset($question7) && in_array('930', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_930') . '</option>';
        $html .= '<option value="940"' . (isset($question7) && in_array('940', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_940') . '</option>';
        $html .= '<option value="950"' . (isset($question7) && in_array('950', $question7) ? ' selected' : '') . '>' . JText::_('PLG_ISBNREGISTRY_FORMS_CLASS_950') . '</option>';
        $html .= '</select><span class="error">' . JText::_($errors['question_7']) . '</span></div>';
        return $html;
    }

}

?>