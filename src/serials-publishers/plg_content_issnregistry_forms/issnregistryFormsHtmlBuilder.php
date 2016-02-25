<?php

/**
 * @Plugin 	"ID Registry - Serials Publishers - Forms"
 * @version 	1.0.0
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 */
defined('_JEXEC') or die('Restricted access');

class IssnregistryFormsHtmlBuilder {

    public static function getIssnApplicationFormPt1($errors = array()) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        $html .= '<div class="form_header">' . JText::_('PLG_ISSNREGISTRY_FORMS_REGISTRATION_HEADER') . '</div>';
        $html .= '<div class="plg_issnregistry_forms" id="plg_issnregistry_forms_application" >';
        $html .= '<div class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_TITLE_1') . '</div>';
        $html .= '<form action="' . JURI::getInstance()->toString() . '" method="post" name="issnApplicationForm" id="issnApplicationForm">';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLISHER_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="publisher" id="publisher" size="30" value="' . $post->get('publisher', null, 'string') . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['publisher']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_CONTACT_PERSON_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="contact_person" id="contact_person" size="30" value="' . $post->get('contact_person', null, 'string') . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['contact_person']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_EMAIL_FIELD') . ':</td>';
        // Content - Email Cloaking plugin must be disabled to get this work
        $html .= '<td><input type="text" id="email" name="email" size="30" value="' . $post->get('email', null, 'string') . '"  maxlength="100"/></td>';
        $html .= '<td class="error">* ' . JText::_($errors['email']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PHONE_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="phone" id="phone" size="10" value="' . $post->get('phone', null, 'string') . '" /></td>';
        $html .= '<td class="error">* ' . JText::_($errors['phone']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ADDRESS_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="address" id="address" size="30" value="' . $post->get('address', null, 'string') . '" /></td>';
        $html .= '<td class="error"> ' . JText::_($errors['address']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ZIP_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="zip" id="zip" size="5" value="' . $post->get('zip', null, 'string') . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['zip']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_CITY_FIELD') . ':</td>';
        $html .= '<td><input type="text" name="city" id="city" size="20" value="' . $post->get('city', null, 'string') . '" /></td>';
        $html .= '<td class="error">' . JText::_($errors['city']) . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td></td>';
        $html .= '<td class="button_row"><input type="submit" name="submit_application_pt1" value="' . JText::_('PLG_ISSNREGISTRY_FORMS_CONTINUE_BTN') . '" /></td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= JHTML::_('form.token');
        $backApplicationPt2 = $post->get('back_application_pt2', null, 'string');
        if (isset($backApplicationPt2)) {
            $html .= self::getIssnApplicationFormPt2Hidden();
            $html .= self::getIssnApplicationFormPt3Hidden();
        }
        $html .= '</form></div>';
        return $html;
    }

    public static function getIssnApplicationFormPt2($maxPublicationsCount = 1, $errors = array()) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        // Get publication count
        $publicationCount = $post->get('publication_count', 0, 'integer');
        // Sanity check for publication count
        if ($publicationCount > $maxPublicationsCount) {
            $publicationCount = $maxPublicationsCount;
        }

        $html .= '<div class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_TITLE_2') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_FORMATS_INFO') . '</div>';
        $html .= '<div class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_TITLE_3') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_LANGUAGES_INFO') . '</div>';
        $html .= '<div class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_TITLE_4') . '</div>';
        $html .= '<div>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_COUNT_FIELD') . '</div>';

        $html .= '<div class="plg_issnregistry_forms" id="plg_issnregistry_forms_application" >';
        $html .= '<form action="' . JURI::getInstance()->toString() . '" method="post" name="issnApplicationForm" id="issnApplicationForm">';
        $html .= '<div><select name="publication_count" id="publication_count">';
        $html .= '<option value="0"' . ($publicationCount == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_SELECT') . '</option>';
        for ($i = 1; $i <= $maxPublicationsCount; $i++) {
            $html .= '<option value="' . $i . '"' . ($publicationCount == $i ? ' selected' : '') . '>' . $i . '</option>';
        }
        $html .= '</select>';
        $html .= '<span class="error">* ' . JText::_($errors['publication_count']) . '</span></div>';
        $html .= '<div class="button_row"><input type="submit" name="back_application_pt2" value="' . JText::_('PLG_ISSNREGISTRY_FORMS_BACK_BTN') . '" />';
        $html .= '<input type="submit" name="submit_application_pt2" value="' . JText::_('PLG_ISSNREGISTRY_FORMS_CONTINUE_BTN') . '" /></div>';
        $html .= JHTML::_('form.token');
        $html .= self::getIssnApplicationFormPt1Hidden();
        $html .= self::getIssnApplicationFormPt3Hidden();
        $html .= '</form></div>';
        return $html;
    }

    public static function getIssnApplicationFormPt3($maxPublicationsCount = 1, $errors = array()) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        // Get publication count
        $publicationCount = $post->get('publication_count', 0, 'integer');
        // Sanity check for publication count
        if ($publicationCount > $maxPublicationsCount) {
            $publicationCount = $maxPublicationsCount;
        }

        $html .= '<div class="form_header">' . JText::_('PLG_ISSNREGISTRY_FORMS_REGISTRATION_HEADER') . '</div>';
        $html .= '<div class="plg_issnregistry_forms" id="plg_issnregistry_forms_application" >';
        $html .= '<form action="' . JURI::getInstance()->toString() . '" method="post" name="issnApplicationForm" id="issnApplicationForm">';

        // Create publication forms
        for ($i = 0; $i < $publicationCount; $i++) {
            $html .= '<div class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_TITLE_5') . ($publicationCount > 1 ? (' ' . ($i + 1)) : '') . '</div>';
            $html .= '<table>';
            $html .= '<tr>';
            $html .= '<td class="form3_col1">' . JText::_('PLG_ISSNREGISTRY_FORMS_TITLE_FIELD') . ':</td>';
            $html .= '<td class="form3_col2"><input type="text" name="title_' . $i . '" id="title_' . $i . '" size="30" value="' . $post->get('title_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['title_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PLACE_OF_PUBLICATION_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="place_of_publication_' . $i . '" id="place_of_publication_' . $i . '" size="30" value="' . $post->get('place_of_publication_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">* ' . JText::_($errors['place_of_publication_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PRINTER_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="printer_' . $i . '" id="printer_' . $i . '" size="30" value="' . $post->get('printer_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['printer_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ISSUED_FROM_YEAR_FIELD') . ':</td>';
            $html .= '<td>' . self::getIssuedFromYearMenu($i) . '</td>';
            $html .= '<td class="error">* ' . JText::_($errors['issued_from_year_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ISSUED_FROM_NUMBER_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="issued_from_number_' . $i . '" id="issued_from_number_' . $i . '" size="8" value="' . $post->get('issued_from_number_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['issued_from_number_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_FREQUENCY_FIELD') . ':</td>';
            $html .= '<td>' . self::getFrequencyMenu($i) . '</td>';
            $html .= '<td class="error">* ' . JText::_($errors['frequency_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_LANGUAGE_FIELD') . ':</td>';
            $html .= '<td>' . self::getLanguageMenu($i) . '</td>';
            $html .= '<td class="error">* ' . JText::_($errors['language_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_FIELD') . ':</td>';
            $html .= '<td>' . self::getPublicationTypeMenu($i) . '</td>';
            $html .= '<td class="error">* ' . JText::_($errors['publication_type_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td></td>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_OTHER_FIELD') . '<input type="text" name="publication_type_other_' . $i . '" id="publication_type_other_' . $i . '" size="20" class="publication_type_other" value="' . $post->get('publication_type_other_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['publication_type_other_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_MEDIUM_FIELD') . ':</td>';
            $html .= '<td>' . self::getMedium($i) . '</td>';
            $html .= '<td class="error">* ' . JText::_($errors['medium_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td colspan="2">' . JText::_('PLG_ISSNREGISTRY_FORMS_URL_FIELD') . ':</td>';
            $html .= '</tr><tr>';
            $html .= '<td></td>';
            $html .= '<td><input type="text" name="url_' . $i . '" id="url_' . $i . '" size="30" value="' . $post->get('url_' . $i, null, 'string') . '" />';
            $html .= '<span class="field_info">' . JText::_('PLG_ISSNREGISTRY_FORMS_URL_INFO') . '</span></td>';
            $html .= '<td class="error">' . JText::_($errors['url_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td colspan="2" class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_HAS_PREVIOUS_TITLE') . ':</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PREVIOUS_TITLE_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="previous_title_' . $i . '" id="previous_title_' . $i . '" size="30" value="' . $post->get('previous_title_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['previous_title_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PREVIOUS_ISSN_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="previous_issn_' . $i . '" id="previous_issn_' . $i . '" size="15" value="' . $post->get('previous_issn_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['previous_issn_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PREVIOUS_TITLE_LAST_ISSUE_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="previous_title_last_issue_' . $i . '" id="previous_title_last_issue_' . $i . '" size="15" value="' . $post->get('previous_title_last_issue_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['previous_title_last_issue_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td colspan="2" class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_IS_PART_OF_MAIN_SERIES') . ':</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_MAIN_SERIES_TITLE_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="main_series_title_' . $i . '" id="main_series_title_' . $i . '" size="30" value="' . $post->get('main_series_title_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['main_series_title_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_MAIN_SERIES_ISSN_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="main_series_issn_' . $i . '" id="main_series_issn_' . $i . '" size="15" value="' . $post->get('main_series_issn_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['main_series_issn_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td colspan="2" class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_HAS_SUBSERIES') . ':</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_SUBSERIES_TITLE_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="subseries_title_' . $i . '" id="subseries_title_' . $i . '" size="30" value="' . $post->get('subseries_title_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['subseries_title_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_SUBSERIES_ISSN_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="subseries_issn_' . $i . '" id="subseries_issn_' . $i . '" size="15" value="' . $post->get('subseries_issn_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['subseries_issn_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td colspan="2" class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_IS_ISSUED_IN_ANOTHER_MEDIUM') . ':</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ANOTHER_MEDIUM_TITLE_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="another_medium_title_' . $i . '" id="another_medium_title_' . $i . '" size="30" value="' . $post->get('another_medium_title_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['another_medium_title_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ANOTHER_MEDIUM_ISSN_FIELD') . ':</td>';
            $html .= '<td><input type="text" name="another_medium_issn_' . $i . '" id="another_medium_issn_' . $i . '" size="15" value="' . $post->get('another_medium_issn_' . $i, null, 'string') . '" /></td>';
            $html .= '<td class="error">' . JText::_($errors['another_medium_issn_' . $i]) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td class="sub_title"></td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ADDITIONAL_INFO_FIELD') . ':</td>';
            $html .= '<td><textarea name="additional_info_' . $i . '" id="additional_info_' . $i . '" class="additional_info">' . $post->get('additional_info_' . $i, null, 'string') . '</textarea></td>';
            $html .= '<td class="error">' . JText::_($errors['additional_info_' . $i]) . '</td>';
            $html .= '</tr>';
            $html .= '</table>';
            if ($i < $publicationCount - 1) {
                $html .= '<hr />';
            }
        }

        $html .= '<div class="button_row" id="button_row_form3"><input type="submit" name="back_application_pt3" value="' . JText::_('PLG_ISSNREGISTRY_FORMS_BACK_BTN') . '" />';
        $html .= '<input type="submit" name="submit_application_pt3" value="' . JText::_('PLG_ISSNREGISTRY_FORMS_CONTINUE_BTN') . '" /></div>';
        $html .= JHTML::_('form.token');
        $html .= '<input type="hidden" name="publication_count" value="' . $publicationCount . '" />';
        $html .= self::getIssnApplicationFormPt1Hidden();
        $html .= self::getIssnApplicationFormPt2Hidden();
        $html .= '</form></div>';

        return $html;
    }

    public static function getIssnApplicationFormPt4($maxPublicationsCount = 1) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        // Get publication count
        $publicationCount = $post->get('publication_count', 0, 'integer');
        // Sanity check for publication count
        if ($publicationCount > $maxPublicationsCount) {
            $publicationCount = $maxPublicationsCount;
        }

        // Information about the publisher
        $html .= '<div class="form_header">' . JText::_('PLG_ISSNREGISTRY_FORMS_APPLICATION_SUMMARY_HEADER') . '</div>';
        $html .= '<div class="plg_issnregistry_forms" id="plg_issnregistry_forms_application" >';
        $html .= '<div class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_TITLE_1') . '</div>';
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td class="form4_col1">' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLISHER_FIELD') . ':</td>';
        $html .= '<td class="form4_col2">' . $post->get('publisher', null, 'string') . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_CONTACT_PERSON_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('contact_person', null, 'string') . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_EMAIL_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('email', null, 'string') . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PHONE_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('phone', null, 'string') . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ADDRESS_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('address', null, 'string') . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ZIP_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('zip', null, 'string') . '</td>';
        $html .= '</tr><tr>';
        $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_CITY_FIELD') . ':</td>';
        $html .= '<td>' . $post->get('city', null, 'string') . '</td>';
        $html .= '</tr>';
        //$html .= '</table>';
        // Loop through publications
        for ($i = 0; $i < $publicationCount; $i++) {
            //$html .= '<div class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_TITLE_5') . ($publicationCount > 1 ? (' ' . ($i + 1)) : '') . '</div>';
            $html .= '<td><div class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_TITLE_5') . ($publicationCount > 1 ? (' ' . ($i + 1)) : '') . '</div></td>';
            //$html .= '<table>';
            $html .= '<tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_TITLE_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('title_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PLACE_OF_PUBLICATION_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('place_of_publication_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PRINTER_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('printer_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ISSUED_FROM_YEAR_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('issued_from_year_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ISSUED_FROM_NUMBER_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('issued_from_number_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_FREQUENCY_FIELD') . ':</td>';
            $html .= '<td>' . self::getFrequencyLabel($i) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_LANGUAGE_FIELD') . ':</td>';
            $html .= '<td>' . self::getLanguageLabel($i) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_FIELD') . ':</td>';
            $html .= '<td>' . self::getPublicationTypeString($post->get('publication_type_' . $i, null, 'string')) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_OTHER_SUMMARY_FIELD') . '</td>';
            $html .= '<td>' . $post->get('publication_type_other_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_MEDIUM_FIELD') . ':</td>';
            $html .= '<td>' . self::getMediumString($post->get('medium_' . $i, null, 'string')) . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_MEDIUM_OTHER_SUMMARY_FIELD') . '</td>';
            $html .= '<td>' . $post->get('medium_other_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_URL_SUMMARY_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('url_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td colspan="2" class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_HAS_PREVIOUS_TITLE') . ':</td>';
            $html .= '<td></td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PREVIOUS_TITLE_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('previous_title_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PREVIOUS_ISSN_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('previous_issn_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_PREVIOUS_TITLE_LAST_ISSUE_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('previous_title_last_issue_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td colspan="2" class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_IS_PART_OF_MAIN_SERIES') . ':</td>';
            $html .= '<td></td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_MAIN_SERIES_TITLE_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('main_series_title_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_MAIN_SERIES_ISSN_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('main_series_issn_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td colspan="2" class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_HAS_SUBSERIES') . ':</td>';
            $html .= '<td></td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_SUBSERIES_TITLE_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('subseries_title_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_SUBSERIES_ISSN_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('subseries_issn_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td colspan="2" class="sub_title">' . JText::_('PLG_ISSNREGISTRY_FORMS_IS_ISSUED_IN_ANOTHER_MEDIUM') . ':</td>';
            $html .= '<td></td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ANOTHER_MEDIUM_TITLE_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('another_medium_title_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ANOTHER_MEDIUM_ISSN_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('another_medium_issn_' . $i, null, 'string') . '</td>';
            $html .= '</tr><tr>';
            $html .= '<td class="sub_title"></td>';
            $html .= '<td></td>';
            $html .= '</tr><tr>';
            $html .= '<td>' . JText::_('PLG_ISSNREGISTRY_FORMS_ADDITIONAL_INFO_FIELD') . ':</td>';
            $html .= '<td>' . $post->get('additional_info_' . $i, null, 'string') . '</td>';
            $html .= '</tr>';
            //$html .= '</table>';
        }
        $html .= '</table>';
        $html .= '<form action = "' . JURI::getInstance()->toString() . '" method = "post" name="issnApplicationForm" id="issnApplicationForm">';
        $html .= self::getIssnApplicationFormPt1Hidden();
        $html .= self::getIssnApplicationFormPt2Hidden();
        $html .= self::getIssnApplicationFormPt3Hidden();
        $html .= '<div class="button_row" id="button_row_form4"><input type="submit" name="back_application_pt4" value="' . JText::_('PLG_ISSNREGISTRY_FORMS_BACK_BTN') . '" />';
        $html .= '<input type="submit" name="submit_application_pt4" value="' . JText::_('PLG_ISSNREGISTRY_FORMS_SEND_BTN') . '" /></div>';
        $html .= JHTML::_('form.token');
        $html .= '</form></div>';
        return $html;
    }

    private static function getIssnApplicationFormPt1Hidden() {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        $html .= '<input type="hidden" name="publisher" value="' . $post->get('publisher', null, 'string') . '" />';
        $html .= '<input type="hidden" name="contact_person" value="' . $post->get('contact_person', null, 'string') . '" />';
        $html .= '<input type="hidden" name="email" value="' . $post->get('email', null, 'string') . '" />';
        $html .= '<input type="hidden" name="phone" value="' . $post->get('phone', null, 'string') . '" />';
        $html .= '<input type="hidden" name="address" value="' . $post->get('address', null, 'string') . '" />';
        $html .= '<input type="hidden" name="zip" value="' . $post->get('zip', null, 'string') . '" />';
        $html .= '<input type="hidden" name="city" value="' . $post->get('city', null, 'string') . '" />';

        return $html;
    }

    private static function getIssnApplicationFormPt2Hidden() {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        $html .= '<input type="hidden" name="publication_count" value="' . $post->get('publication_count', 0, 'integer') . '" />';

        return $html;
    }

    private static function getIssnApplicationFormPt3Hidden() {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;

        // Get publication count
        $publicationCount = $post->get('publication_count', 0, 'integer');

        // Create publication forms
        for ($i = 0; $i < $publicationCount; $i++) {
            $html .= '<input type="hidden" name="title_' . $i . '" value="' . $post->get('title_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="place_of_publication_' . $i . '" value="' . $post->get('place_of_publication_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="printer_' . $i . '" value="' . $post->get('printer_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="issued_from_year_' . $i . '" value="' . $post->get('issued_from_year_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="issued_from_number_' . $i . '" value="' . $post->get('issued_from_number_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="frequency_' . $i . '" value="' . $post->get('frequency_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="language_' . $i . '" value="' . $post->get('language_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="publication_type_' . $i . '" value="' . $post->get('publication_type_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="publication_type_other_' . $i . '" value="' . $post->get('publication_type_other_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="medium_' . $i . '" value="' . $post->get('medium_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="medium_other_' . $i . '" value="' . $post->get('medium_other_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="url_' . $i . '" value="' . $post->get('url_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="previous_title_' . $i . '" value="' . $post->get('previous_title_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="previous_issn_' . $i . '" value="' . $post->get('previous_issn_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="previous_title_last_issue_' . $i . '" value="' . $post->get('previous_title_last_issue_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="main_series_title_' . $i . '" value="' . $post->get('main_series_title_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="main_series_issn_' . $i . '" value="' . $post->get('main_series_issn_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="subseries_title_' . $i . '" value="' . $post->get('subseries_title_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="subseries_issn_' . $i . '" value="' . $post->get('subseries_issn_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="another_medium_title_' . $i . '" value="' . $post->get('another_medium_title_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="another_medium_issn_' . $i . '" value="' . $post->get('another_medium_issn_' . $i, null, 'string') . '" />';
            $html .= '<input type="hidden" name="additional_info_' . $i . '" value="' . $post->get('additional_info_' . $i, null, 'string') . '" />';
        }

        return $html;
    }

    private static function getPublicationTypeMenu($index) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        $publicationType = $post->get('publication_type_' . $index, null, 'string');

        $html .= '<select class="publication_type" name="publication_type_' . $index . '" id="publication_type_' . $index . '">';
        $html .= '<option value=""' . (strcmp($publicationType, '') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_SELECT') . '</option>';
        $html .= '<option value="JOURNAL"' . (strcmp($publicationType, 'JOURNAL') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_JOURNAL') . '</option>';
        $html .= '<option value="NEWSLETTER"' . (strcmp($publicationType, 'NEWSLETTER') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_NEWSLETTER') . '</option>';
        $html .= '<option value="STAFF_MAGAZINE"' . (strcmp($publicationType, 'STAFF_MAGAZINE') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_STAFF_MAGAZINE') . '</option>';
        $html .= '<option value="MEMBERSHIP_BASED_MAGAZINE"' . (strcmp($publicationType, 'MEMBERSHIP_BASED_MAGAZINE') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_MEMBERSHIP_BASED_MAGAZINE') . '</option>';
        $html .= '<option value="CARTOON"' . (strcmp($publicationType, 'CARTOON') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_CARTOON') . '</option>';
        $html .= '<option value="NEWSPAPER"' . (strcmp($publicationType, 'NEWSPAPER') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_NEWSPAPER') . '</option>';
        $html .= '<option value="FREE_PAPER"' . (strcmp($publicationType, 'FREE_PAPER') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_FREE_PAPER') . '</option>';
        $html .= '<option value="MONOGRAPHY_SERIES"' . (strcmp($publicationType, 'MONOGRAPHY_SERIES') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_MONOGRAPHY_SERIES') . '</option>';
        $html .= '<option value="OTHER_SERIAL"' . (strcmp($publicationType, 'OTHER_SERIAL') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_OTHER_SERIAL') . '</option>';
        $html .= '</select>';
        return $html;
    }

    private static function getPublicationTypeString($publicationType) {
        $values = array(
            "JOURNAL" => JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_JOURNAL'),
            "NEWSLETTER" => JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_NEWSLETTER'),
            "STAFF_MAGAZINE" => JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_STAFF_MAGAZINE'),
            "MEMBERSHIP_BASED_MAGAZINE" => JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_MEMBERSHIP_BASED_MAGAZINE'),
            "CARTOON" => JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_CARTOON'),
            "NEWSPAPER" => JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_NEWSPAPER'),
            "FREE_PAPER" => JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_FREE_PAPER'),
            "MONOGRAPHY_SERIES" => JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_MONOGRAPHY_SERIES'),
            "OTHER_SERIAL" => JText::_('PLG_ISSNREGISTRY_FORMS_PUBLICATION_TYPE_OTHER_SERIAL_SUMMARY')
        );
        if (array_key_exists($publicationType, $values)) {
            return $values[$publicationType];
        }
        return "";
    }

    private static function getMedium($index) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        $medium = $post->get('medium_' . $index, null, 'string');

        $html .= '<input class="medium" type="radio" name="medium_' . $index . '" value="PRINTED"' . (strcmp($medium, 'PRINTED') == 0 ? ' checked' : '') . '/>' . JText::_('PLG_ISSNREGISTRY_FORMS_MEDIUM_PRINTED') . '<br />';
        $html .= '<input class="medium" type="radio" name="medium_' . $index . '" value="ONLINE"' . (strcmp($medium, 'ONLINE') == 0 ? ' checked' : '') . '/>' . JText::_('PLG_ISSNREGISTRY_FORMS_MEDIUM_ONLINE') . '<br />';
        $html .= '<input class="medium" type="radio" name="medium_' . $index . '" value="CDROM"' . (strcmp($medium, 'CDROM') == 0 ? ' checked' : '') . '/>' . JText::_('PLG_ISSNREGISTRY_FORMS_MEDIUM_CDROM') . '<br />';
        $html .= '<input class="medium" type="radio" name="medium_' . $index . '" value="OTHER"' . (strcmp($medium, 'OTHER') == 0 ? ' checked' : '') . '/>' . JText::_('PLG_ISSNREGISTRY_FORMS_MEDIUM_OTHER');
        $html .= '<input type="text" name="medium_other_' . $index . '" id="medium_other_' . $index . '" size="18" class="medium_other" value="' . $post->get('medium_other_' . $index, null, 'string') . '" />';
        return $html;
    }

    private static function getMediumString($medium) {
        $values = array(
            "PRINTED" => JText::_('PLG_ISSNREGISTRY_FORMS_MEDIUM_PRINTED'),
            "ONLINE" => JText::_('PLG_ISSNREGISTRY_FORMS_MEDIUM_ONLINE_SUMMARY'),
            "CDROM" => JText::_('PLG_ISSNREGISTRY_FORMS_MEDIUM_CDROM'),
            "OTHER" => JText::_('PLG_ISSNREGISTRY_FORMS_MEDIUM_OTHER_SUMMARY')
        );
        if (array_key_exists($medium, $values)) {
            return $values[$medium];
        }
        return "";
    }

    private static function getLanguageMenu($index) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        $userLanguage = $post->get('language_' . $index, null, 'string');

        $html .= '<select class="language" name="language_' . $index . '" id="language_' . $index . '">';
        $html .= '<option value=""' . (strcmp($userLanguage, '') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_SELECT') . '</option>';
        foreach (IssnregistryFormsHelper::getLanguageList() as $language) {
            $html .= '<option value="' . $language . '"' . (strcmp($userLanguage, $language) == 0 ? ' selected' : '') . '>' . JText::_("PLG_ISSNREGISTRY_FORMS_LANGUAGE_$language") . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    private static function getLanguageLabel($index) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        // Get language code
        $langCode = $post->get('language_' . $index, null, 'string');
        return JText::_("PLG_ISSNREGISTRY_FORMS_LANGUAGE_$langCode");
    }

    private static function getFrequencyMenu($index) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        $userFrequency = $post->get('frequency_' . $index, null, 'string');

        $html .= '<select class="frequency" name="frequency_' . $index . '" id="frequency_' . $index . '">';
        $html .= '<option value=""' . (strcmp($userFrequency, '') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_SELECT') . '</option>';
        foreach (IssnregistryFormsHelper::getFrequencyList() as $frequency) {
            $html .= '<option value="' . $frequency . '"' . (strcmp($userFrequency, $frequency) == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_FREQUENCY_' . strtoupper($frequency)) . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    private static function getFrequencyLabel($index) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        // Get language code
        $frequency = $post->get('frequency_' . $index, null, 'string');
        return JText::_('PLG_ISSNREGISTRY_FORMS_FREQUENCY_' . strtoupper($frequency));
    }

    private static function getIssuedFromYearMenu($index) {
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        $year = $post->get('issued_from_year_' . $index, null, 'string');
        // Get current year
        $currentYear = date("Y");

        $html .= '<select class="issued_from_year" name="issued_from_year_' . $index . '" id="issued_from_year_' . $index . '">';
        $html .= '<option value=""' . (strcmp($year, '') == 0 ? ' selected' : '') . '>' . JText::_('PLG_ISSNREGISTRY_FORMS_SELECT') . '</option>';
        for ($i = $currentYear; $i >= 1900; $i--) {
            $html .= '<option value="' . $i . '"' . (($year == $i) ? ' selected' : '') . '>' . $i . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

}

?>