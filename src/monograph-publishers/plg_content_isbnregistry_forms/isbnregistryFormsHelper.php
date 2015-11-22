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
	
    public static function validateApplicationFormPt1() {
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
        $publisherId = $post->get('publisher_id', null, 'string');
		if(strlen($publisherId) > 20){
			$errors['publisher_id'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
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
		// Contact person - required
        $contactPerson = $post->get('contact_person', null, 'string');
		if (empty($contactPerson) == true) {
            $errors['contact_person'] = "PLG_ISBNREGISTRY_FORMS_CONTACT_PERSON_FIELD_EMPTY";
        } elseif(strlen($contactPerson) > 100){
			$errors['contact_person'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}		
        // Phone number - required (validate format)
		$phone = $post->get('phone', null, 'string');
		if (empty($phone) == true) {
            $errors['phone'] = "PLG_ISBNREGISTRY_FORMS_PHONE_FIELD_EMPTY";
        } else if (!preg_match('/^(\+){0,1}[0-9 ]{0,30}$/i', $phone)) {
            $errors['phone'] = "PLG_ISBNREGISTRY_FORMS_PHONE_FIELD_INVALID";
        }
        // Email - required
        $email = $post->get('email', null, 'string');
        if (empty($email) == true) {
            $errors['email'] = "PLG_ISBNREGISTRY_FORMS_EMAIL_FIELD_EMPTY";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "PLG_ISBNREGISTRY_FORMS_EMAIL_FIELD_INVALID";
        }
		// Published before - requried
        $publishedBefore = $post->get('published_before', null, 'string');
		if (strlen($publishedBefore) == 0) {
            $errors['published_before'] = "PLG_ISBNREGISTRY_FORMS_PUBLISHED_BEFORE_FIELD_EMPTY";
        } else if (!preg_match('/^(0|1)$/i', $publishedBefore)) {
            $errors['published_before'] = "PLG_ISBNREGISTRY_FORMS_PUBLISHED_BEFORE_FIELD_INVALID";
        }
		// Publications public - requried
        $publicationsPublic = $post->get('publications_public', null, 'string');
		if (strlen($publicationsPublic) == 0) {
            $errors['publications_public'] = "PLG_ISBNREGISTRY_FORMS_PUBLICATIONS_PUBLIC_FIELD_EMPTY";
        } else if (!preg_match('/^(0|1)$/i', $publicationsPublic)) {
            $errors['publications_public'] = "PLG_ISBNREGISTRY_FORMS_PUBLICATIONS_PUBLIC_FIELD_INVALID";
        }
		// Publications intra - requried
        $publicationsIntra = $post->get('publications_intra', null, 'string');
		if (strlen($publicationsIntra) == 0) {
            $errors['publications_intra'] = "PLG_ISBNREGISTRY_FORMS_PUBLICATIONS_INTRA_FIELD_EMPTY";
        } else if (!preg_match('/^(0|1)$/i', $publicationsIntra)) {
            $errors['publications_intra'] = "PLG_ISBNREGISTRY_FORMS_PUBLICATIONS_INTRA_FIELD_INVALID";
        }	
		// Publishing activity - requried
        $publishingActivity = $post->get('publishing_activity', null, 'string');
		if (empty($publishingActivity) == true) {
            $errors['publishing_activity'] = "PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_FIELD_EMPTY";
        } else if (!preg_match('/^(OCCASIONAL|CONTINUOUS)$/', $publishingActivity)) {
            $errors['publishing_activity'] = "PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_FIELD_INVALID";
        }		
		// Publishing activity amount - optional
        $publishingActivityAmount = $post->get('publishing_activity_amount', null, 'string');
		if (!preg_match('/^\d{0,5}$/i', $publishingActivityAmount)) {
            $errors['publishing_activity_amount'] = "PLG_ISBNREGISTRY_FORMS_PUBLISHING_ACTIVITY_AMOUNT_FIELD_INVALID";
        }
		// Publication type - requried
        $publicationType = $post->get('publication_type', null, 'string');
		if (empty($publicationType) == true) {
            $errors['publication_type'] = "PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_FIELD_EMPTY";
        } else if (!preg_match('/^(BOOK|DISSERTATION|SHEET_MUSIC|MAP|OTHER)$/', $publicationType)) {
            $errors['publication_type'] = "PLG_ISBNREGISTRY_FORMS_PUBLICATION_TYPE_FIELD_INVALID";
        }	
		// Publication format - required
        $publicationFormat = $post->get('publication_format', null, 'string');
		if (empty($publicationFormat) == true) {
            $errors['publication_format'] = "PLG_ISBNREGISTRY_FORMS_PUBLICATION_FORMAT_FIELD_EMPTY";
        } else if (!preg_match('/^(PRINT|ELECTRONICAL|PRINT_ELECTRONICAL)$/', $publicationFormat)) {
            $errors['publication_format'] = "PLG_ISBNREGISTRY_FORMS_PUBLICATION_FORMAT_FIELD_INVALID";
        }		        
        return $errors;
    }	
	
	public static function validateApplicationFormPt2() {
        // Array for the error messages
        $errors = array();
        
        // Get the post variables
        $post = JFactory::getApplication()->input->post;
        
        // 1st first name - required
        $firstName = $post->get('first_name_1', null, 'string');
        if (empty($firstName) == true) {
            $errors['first_name_1'] = "PLG_ISBNREGISTRY_FORMS_FIRST_NAME_FIELD_EMPTY";
        } else if(strlen($firstName) > 50){
			$errors['first_name_1'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
        // 1st last name - required
        $lastName = $post->get('last_name_1', null, 'string');
        if (empty($lastName) == true) {
            $errors['last_name_1'] = "PLG_ISBNREGISTRY_FORMS_LAST_NAME_FIELD_EMPTY";
        } else if(strlen($lastName) > 50){
			$errors['last_name_1'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}	
		// 1st role - required
        $roles = $post->get('role_1', null, 'array');
		if(count($roles) == 0){
			$errors['role_1'] = "PLG_ISBNREGISTRY_FORMS_PUBLICATION_ROLE_FIELD_EMPTY";
		} else if(count($roles) > 4){
			$errors['role_1'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_MANY";
		} else if(!IsbnregistryFormsHelper::validateRoles($roles)) {
			$errors['role_1'] = "PLG_ISBNREGISTRY_FORMS_PUBLICATION_ROLE_FIELD_INVALID";
		}		
		// Loop through other authors
		for ($x = 2; $x <= 4; $x++) {
			// 2-4 first name - optional
			$firstName = $post->get("first_name_$x", null, 'string');
			if(strlen($firstName) > 50){
				$errors["first_name_$x"] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
			}
			// 2-4 last name - optional
			$lastName = $post->get("last_name_$x", null, 'string');
			if(strlen($lastName) > 50){
				$errors["last_name_$x"] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
			}	
			// 2-4 role - optional
			$roles = $post->get("role_$x", null, 'array');
			if(count($roles) > 4){
				$errors["role_$x"] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_MANY";
			} else if(isset($roles) && !IsbnregistryFormsHelper::validateRoles($roles)) {
				$errors["role_$x"] = "PLG_ISBNREGISTRY_FORMS_PUBLICATION_ROLE_FIELD_INVALID";
			}			
		}
		// Title - required
        $title = $post->get('title', null, 'string');
        if (empty($title) == true) {
            $errors['title'] = "PLG_ISBNREGISTRY_FORMS_TITLE_FIELD_EMPTY";
        } else if(strlen($title) > 200){
			$errors['title'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}
		// Subtitle - optional
        $subtitle = $post->get('subtitle', null, 'string');
        if(strlen($subtitle) > 200){
			$errors['subtitle'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}		
		// Language - required
        $language = $post->get('language', null, 'string');
		if (strlen($language) == 0) {
            $errors['language'] = "PLG_ISBNREGISTRY_FORMS_LANGUAGE_FIELD_EMPTY";
        } else if (!IsbnregistryFormsHelper::validateLanguage($language)) {
            $errors['language'] = "PLG_ISBNREGISTRY_FORMS_LANGUAGE_FIELD_INVALID";
        }
        // Year - required
        $year = $post->get('year', null, 'string');
        if (strlen($year) == 0) {
            $errors['published'] = "PLG_ISBNREGISTRY_FORMS_YEAR_FIELD_EMPTY";
        } else if (!preg_match('/^\d{4}$/i', $year)) {
            $errors['published'] = "PLG_ISBNREGISTRY_FORMS_YEAR_FIELD_INVALID";
        }
        // Month - required
        $month = $post->get('month', null, 'string');
        if (strlen($month) == 0) {
            $errors['published'] = "PLG_ISBNREGISTRY_FORMS_MONTH_FIELD_EMPTY";
        } else if (!preg_match('/^(0[1-9]{1}|1[0-2]{1})$/i', $month)) {
            $errors['published'] = "PLG_ISBNREGISTRY_FORMS_MONTH_FIELD_INVALID";
        }	
		// Series - optional
		$series = $post->get("series", null, 'string');
		if(strlen($series) > 200){
			$errors["series"] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}	
		// ISSN - optional
		$issn = $post->get("issn", null, 'string');
		if(strlen($issn) > 9){
			$errors["issn"] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		} else if (strlen($issn) > 0 && !preg_match('/^(\d){4}\-(\d){3}[0-9X]{1}$/i', $issn)) {
            $errors['issn'] = "PLG_ISBNREGISTRY_FORMS_ISSN_FIELD_INVALID";
        }
		// Volume - optional
		$volume = $post->get("volume", null, 'string');
		if(strlen($volume) > 20){
			$errors["volume"] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}	
		$publicationFormat = $post->get('publication_format', null, 'string');
		if(preg_match('/^(PRINT|PRINT_ELECTRONICAL)$/', $publicationFormat)) {
			// Printing house - optional
			$printingHouse = $post->get("printing_house", null, 'string');
			if(strlen($printingHouse) > 100){
				$errors["printing_house"] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
			}	
			// Printing house city - optional
			$printingHouseCity = $post->get("printing_house_city", null, 'string');
			if(strlen($printingHouseCity) > 50){
				$errors["printing_house_city"] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
			}
			// Copies - optional
			$copies = $post->get("copies", null, 'string');
			if(strlen($copies) > 10){
				$errors["copies"] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
			}			
			// Edition - optional
			$edition = $post->get("edition", null, 'string');
			if(!preg_match('/^([0-9]{1}|10|-)$/i', $edition)){
				$errors["edition"] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
			}
			// Type - required
			$types = $post->get('type', null, 'array');
			if(count($types) == 0){
				$errors['type'] = "PLG_ISBNREGISTRY_FORMS_TYPE_FIELD_EMPTY";
			} else if(count($types) > 3){
				$errors['type'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_MANY";
			} else if(!IsbnregistryFormsHelper::validateTypes($types)) {
				$errors['type'] = "PLG_ISBNREGISTRY_FORMS_TYPE_FIELD_INVALID";
			}	
		}
		// Comments - optional
        $comments = $post->get('comments', null, 'string');
		if(strlen($comments) > 200){
			$errors['comments'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_LONG";
		}		
		if(preg_match('/^(ELECTRONICAL|PRINT_ELECTRONICAL)$/', $publicationFormat)) {		
			// File format - required
			$fileFormats = $post->get('fileformat', null, 'array');
			if(count($fileFormats) == 0){
				$errors['fileformat'] = "PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_FIELD_EMPTY";
			} else if(count($fileFormats) > 4){
				$errors['fileformat'] = "PLG_ISBNREGISTRY_FORMS_FIELD_TOO_MANY";
			} else if(!IsbnregistryFormsHelper::validateFileFormats($fileFormats)) {
				$errors['fileformat'] = "PLG_ISBNREGISTRY_FORMS_FILE_FORMAT_FIELD_INVALID";
			}
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
	
	public static function getLanguageList() {
		$languages = array(
			'FIN', 'SWE', 'ENG', 'SMI', 'SPA', 'FRE', 'RUS', 'GER', 'MUL'
		);
		return $languages;
	}
	
	private static function validateRoles($roles) {
		if(!isset($roles)) {
			return false;
		}
		$validValues = array(
			'AUTHOR', 'ILLUSTRATOR', 'TRANSLATOR', 'EDITOR'
		);		
		foreach($roles as $role) {
			if(!in_array($role, $validValues)) {
				return false;
			}
		}
		return true;
	}
	
	private static function validateLanguage($language) {
		$languages = IsbnregistryFormsHelper::getLanguageList();
		if(!in_array($language, $languages)) {
			return false;
		}		
		return true;
	}
	
	private static function validateTypes($types) {
		if(!isset($types)) {
			return false;
		}
		$validValues = array(
			'PAPERBACK', 'HARDBACK', 'SPIRAL_BINDING'
		);		
		foreach($types as $type) {
			if(!in_array($type, $validValues)) {
				return false;
			}
		}
		return true;
	}	
	
	private static function validateFileFormats($formats) {
		if(!isset($formats)) {
			return false;
		}
		$validValues = array(
			'PDF', 'EPUB', 'CD-ROM', 'OTHER'
		);		
		foreach($formats as $format) {
			if(!in_array($format, $validValues)) {
				return false;
			}
		}
		return true;
	}	
}

?>