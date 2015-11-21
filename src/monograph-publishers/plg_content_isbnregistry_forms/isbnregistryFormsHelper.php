<?php

/**
 * @Plugin 	"ID Registry - Monograph Publishers - Forms"
 * @version 	1.0.0
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 * */
defined('_JEXEC') or die('Restricted access');

class IsbnregistryFormsHelper {

    public static function validateRegistrationForm() {
        // Array for the error messages
        $errors = array();
        
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        
        // Official name - required
        $officialName = $post->get('official_name', null, 'string');
        if (empty($officialName) == true) {
            $errors['official_name'] = "PLG_ISBNREGISTRY_FORMS_OFFICIAL_NAME_FIELD_EMPTY";
        } else if(strlen($officialName) > 100){
			$errors['official_name'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
		// Other names - optional
        $otherNames = $post->get('other_names', null, 'string');
		if(strlen($otherNames) > 200){
			$errors['other_names'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
        // Address - required
        $address = $post->get('address', null, 'string');
        if (empty($address) == true) {
            $errors['address'] = "PLG_ISBNREGISTRY_FORMS_ADDRESS_FIELD_EMPTY";
        } else if(strlen($address) > 50){
			$errors['address'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
        // ZIP - required
        $zip = $post->get('zip', null, 'string');
        if (strlen($zip) == 0) {
            $errors['zip'] = "PLG_ISBNREGISTRY_FORMS_ZIP_FIELD_EMPTY";
        } else if (!preg_match('/^\d{5}$/i', $zip)) {
            $errors['zip'] = "PLG_ISBNREGISTRY_FORMS_ZIP_FIELD_INVALID";
        }
        // City - required
        $city = $post->get('city', null, 'string');
        if (empty($city) == true) {
            $errors['city'] = "PLG_ISBNREGISTRY_FORMS_CITY_FIELD_EMPTY";
        } else if(strlen($city) > 50){
			$errors['city'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
        // Phone number - optional (validate format)
		$phone = $post->get('phone', null, 'string');
		if (!preg_match('/^(\+){0,1}[0-9 ]{0,30}$/i', $phone)) {
            $errors['phone'] = "PLG_ISBNREGISTRY_FORMS_PHONE_FIELD_INVALID";
        }
        // Email - required
        $email = $post->get('email', null, 'string');
        if (empty($email) == true) {
            $errors['email'] = "PLG_ISBNREGISTRY_FORMS_EMAIL_FIELD_EMPTY";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "PLG_ISBNREGISTRY_FORMS_EMAIL_FIELD_INVALID";
        }
		// Www - optional (validate format)
		$www = $post->get('www', null, 'string');
		if (strlen($www) > 0 && !preg_match('/^http(s)?:\/\/(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/', $www)) {
            $errors['www'] = "PLG_ISBNREGISTRY_FORMS_WWW_FIELD_INVALID";
        } else if(strlen($www) > 100){
			$errors['www'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
		// Contact person - optional
        $contactPerson = $post->get('contact_person', null, 'string');
		if(strlen($contactPerson) > 100){
			$errors['contact_person'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
		// Question 1 - optional
        $question1 = $post->get('question_1', null, 'string');
		if(strlen($question1) > 50){
			$errors['question_1'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
		// Question 2 - optional
        $question2 = $post->get('question_2', null, 'string');
		if(strlen($question2) > 50){
			$errors['question_2'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
		// Question 3 - optional
        $question3 = $post->get('question_3', null, 'string');
		if(strlen($question3) > 50){
			$errors['question_3'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
		// Question 4 - optional
        $question4 = $post->get('question_4', null, 'string');
		if(strlen($question4) > 200){
			$errors['question_4'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
		// Question 5 - optional
        $question5 = $post->get('question_5', null, 'string');
		if(strlen($question5) > 200){
			$errors['question_5'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
		// Question 6 - optional
        $question6 = $post->get('question_6', null, 'string');
		if(strlen($question6) > 50){
			$errors['question_6'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
		// Question 7 - optional
        $question7 = $post->get('question_7', null, 'array');
		if(count($question7) > 4){
			$errors['question_7'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_MANY";
		}
		// Question 8 - optional
        $question8 = $post->get('question_8', null, 'string');
		if(strlen($question8) > 50){
			$errors['question_8'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}		
		// Confirmation - required
        $confirmation = $post->get('confirmation', null, 'string');
        if (empty($confirmation) == true) {
            $errors['confirmation'] = "PLG_ISBNREGISTRY_FORMS_CONFIRMATION_FIELD_EMPTY";
        } else if (!preg_match('/^\d{2}\.\d{2}\.\d{4} .{1,95}$/i', $confirmation)) {
            $errors['confirmation'] = "PLG_ISBNREGISTRY_FORMS_CONFIRMATION_FIELD_INVALID";
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
		$email = $post->get('email', null, 'string');
		$www = $post->get('www', null, 'string');
		$contact_person = $post->get('contact_person', null, 'string');
		$question_1 = $post->get('question_1', null, 'string');
		$question_2 = $post->get('question_2', null, 'string');
		$question_3 = $post->get('question_3', null, 'string');
		$question_4 = $post->get('question_4', null, 'string');
		$question_5 = $post->get('question_5', null, 'string');
		$question_6 = $post->get('question_6', null, 'string');
		$question_7 = $post->get('question_7', null, 'array');
		$question_8 = $post->get('question_8', null, 'string');
		$confirmation = $post->get('confirmation', null, 'string');
		$lang_code = $post->get('lang_code', null, 'string');
		$created = JFactory::getDate();
		
		// Convert question 7 from array to comma separated string
		$question_7_str = '';
		if (is_array($question_7)) {
			if (count($question_7) > 0) {
				$question_7_str = implode(',', $question_7);
			}
		}
		
        // database connection
        $db = JFactory::getDbo();
        // Insert columns
        $columns = array('official_name', 'other_names', 'address', 'zip', 'city', 'phone', 'email','www', 'contact_person', 'question_1', 'question_2', 'question_3', 'question_4', 'question_5', 'question_6', 'question_7', 'question_8', 'confirmation', 'lang_code', 'created', 'created_by');
        // Insert values
        $values = array($db->quote($official_name), $db->quote($other_names), $db->quote($address), $db->quote($zip), $db->quote($city), $db->quote($phone), $db->quote($email), $db->quote($www), $db->quote($contact_person), $db->quote($question_1), $db->quote($question_2), $db->quote($question_3), $db->quote($question_4), $db->quote($question_5), $db->quote($question_6), $db->quote($question_7_str), $db->quote($question_8), $db->quote($confirmation), $db->quote($lang_code), $db->quote($created->toSql()), $db->quote('WWW'));
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