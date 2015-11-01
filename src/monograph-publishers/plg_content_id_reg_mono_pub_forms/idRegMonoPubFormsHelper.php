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
}

?>