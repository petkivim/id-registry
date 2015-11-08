<?php

/**
 * @Plugin 	"ID Registry - Monograph Publishers - Forms"
 * @version 	1.0.0
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * */
defined('_JEXEC') or die('Restricted access');

class IdRegMonoPubFormsHelper {

    public static function validateRegistrationForm() {
        // Array for the error messages
        $errors = array();
        
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        
        // Official name - required
        $officialName = $post->get('official_name', null, 'string');
        if (empty($officialName) == true) {
            $errors['official_name'] = "PLG_ID_REG_MONO_PUB_FORMS_OFFICIAL_NAME_FIELD_EMPTY";
        }
        // Address - required
        $address = $post->get('address', null, 'string');
        if (empty($address) == true) {
            $errors['address'] = "PLG_ID_REG_MONO_PUB_FORMS_ADDRESS_FIELD_EMPTY";
        }
        // ZIP - required
        $zip = $post->get('zip', null, 'string');
        if (strlen($zip) == 0) {
            $errors['zip'] = "PLG_ID_REG_MONO_PUB_FORMS_ZIP_FIELD_EMPTY";
        } else if (!preg_match('/^\d{5}$/i', $zip)) {
            $errors['zip'] = "PLG_ID_REG_MONO_PUB_FORMS_ZIP_FIELD_INVALID";
        }
        // City - required
        $city = $post->get('city', null, 'string');
        if (empty($city) == true) {
            $errors['city'] = "PLG_ID_REG_MONO_PUB_FORMS_CITY_FIELD_EMPTY";
        }
        // Phone number - optional (validate format)
		$phone = $post->get('phone', null, 'string');
		if (!preg_match('/^(\+){0,1}[0-9 ]*$/i', $phone)) {
            $errors['phone'] = "PLG_ID_REG_MONO_PUB_FORMS_PHONE_FIELD_INVALID";
        }
        // Email - required
        $email = $post->get('email', null, 'string');
        if (empty($email) == true) {
            $errors['email'] = "PLG_ID_REG_MONO_PUB_FORMS_EMAIL_FIELD_EMPTY";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "PLG_ID_REG_MONO_PUB_FORMS_EMAIL_FIELD_INVALID";
        }
		// Confirmation - required
        $confirmation = $post->get('confirmation', null, 'string');
        if (empty($confirmation) == true) {
            $errors['confirmation'] = "PLG_ID_REG_MONO_PUB_FORMS_CONFIRMATION_FIELD_EMPTY";
        } else if (!preg_match('/^\d{2}\.\d{2}\.\d{4} .+$/i', $confirmation)) {
            $errors['confirmation'] = "PLG_ID_REG_MONO_PUB_FORMS_CONFIRMATION_FIELD_INVALID";
        }
        
        return $errors;
    }
	
    public static function saveToDb($post) {
		$official_name = $post->get('official_name', null, 'string');
		$other_names = $post->get('other_names', null, 'string');
		$address = $post->get('address', null, 'string');
		$zip = $post->get('zip', null, 'string');
		$city = $post->get('city', null, 'string');
		$phone = $post->get('phone', null, 'string');
		$fax = $post->get('fax', null, 'string');
		$email = $post->get('email', null, 'string');
		$www = $post->get('www', null, 'string');
		$contact_person = $post->get('contact_person', null, 'string');
		$question_1 = $post->get('question_1', null, 'string');
		$question_2 = $post->get('question_2', null, 'string');
		$question_3 = $post->get('question_3', null, 'string');
		$question_4 = $post->get('question_4', null, 'string');
		$question_5 = $post->get('question_5', null, 'string');
		$question_6 = $post->get('question_6', null, 'string');
		$question_7 = $post->get('question_7', null, 'string');
		$question_8 = $post->get('question_8', null, 'string');
		$confirmation = $post->get('confirmation', null, 'string');
		$lang_code = $post->get('lang_code', null, 'string');
		$created = JFactory::getDate();
		
        // database connection
        $db = JFactory::getDbo();
        // Insert columns
        $columns = array('official_name', 'other_names', 'address', 'zip', 'city', 'phone', 'fax', 'email','www', 'contact_person', 'question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'confirmation', 'lang_code', 'created', 'created_by');
        // Insert values
        $values = array($db->quote($official_name), $db->quote($other_names), $db->quote($address), $db->quote($zip), $db->quote($city), $db->quote($phone), $db->quote($fax), $db->quote($email), $db->quote($www), $db->quote($contact_person), $db->quote($question_1), $db->quote($question_2), $db->quote($question_3), $db->quote($question_4), $db->quote($question_5), $db->quote($question_6), $db->quote($question_7), $db->quote($question_8), $db->quote($confirmation), $db->quote($lang_code), $db->quote($created->toSql()), $db->quote('WWW'));
        // Create a new query object.
        $query = $db->getQuery(true);
        // Prepare the insert query
        $query->insert($db->quoteName('#__isbn_registry_publisher'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
        // Set the query using our newly populated query object and execute it
        $db->setQuery($query);
        $db->execute();
        $publisherID = $db->insertid();
        return $publisherID;
    }	
}

?>