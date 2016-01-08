<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_isbnregistry
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2016 Petteri Kivimäki. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Configuration helper.
 *
 * @since  1.6
 */
class ConfigurationHelper extends JHelperContent {

    /**
     * Returns an array that contains the names of configuration parameters 
     * related to messaging settings. 
     * @return array names of configuration parameters related to messaging 
     * setting
     */
    public static function getMessageTypeParameterNames() {
        return array(
            'message_type_big_publisher_isbn',
            'message_type_big_publisher_ismn',
            'message_type_publisher_registered_isbn',
            'message_type_publisher_registered_ismn',
            'message_type_identifier_created_isbn',
            'message_type_identifier_created_ismn'
        );
    }

    /**
     * Checks if the given message type is identifier created.
     * @param string $messageType message type
     * @return boolean true if identifier created; otherwise false
     */
    public static function isPublicationIdentifierCreated($messageType) {
        if (strcmp($messageType, 'message_type_identifier_created_isbn') == 0 || strcmp($messageType, 'message_type_identifier_created_ismn') == 0) {
            return true;
        }
        return false;
    }

    /**
     * Checks if the given message type is publisher registered.
     * @param string $messageType message type
     * @return boolean true if publisher registered; otherwise false
     */
    public static function isPublisherRegistered($messageType) {
        if (strcmp($messageType, 'message_type_publisher_registered_isbn') == 0 || strcmp($messageType, 'message_type_publisher_registered_ismn') == 0) {
            return true;
        }
        return false;
    }

    /**
     * Checks if the given message type is related to isbn numbers.
     * @param type $messageType message type to be checked
     * @return boolean true if message type is related to isbn numbers;
     * otherwise false;
     */
    public static function isIsbn($messageType) {
        $type = substr($messageType, strlen($messageType) - 4);
        if (strcmp($type, 'isbn') == 0) {
            return true;
        }
        return false;
    }

    /**
     * Checks if publication identifiers should be added during message 
     * generation.
     * @param string $parameter message type parameter name
     * @return boolean true if publication identifiers must be added; otherwise 
     * false
     */
    public static function addPublicationIdentifiers($parameter) {
        if (strcmp($parameter, 'message_type_publisher_registered_isbn') == 0 || strcmp($parameter, 'message_type_publisher_registered_ismn') == 0) {
            return false;
        }
        return true;
    }

    /**
     * Checks if the given parameter name is valid.
     * @param string $parameter parameter name to be checked
     * @return bool true if name is valid, otherwise false
     */
    public static function isValidParameterName($parameter) {
        $options = self::getMessageTypeParameterNames();
        return in_array($parameter, $options);
    }

    /**
     * Checks if the message type is used in component's configuration.
     * @return boolean true if message type is used in configuration; otherwise
     * false
     */
    public static function isMessageTypeUsedInConfiguration($messageTypeId) {
        $params = JComponentHelper::getParams('com_isbnregistry');
        $options = self::getMessageTypeParameterNames();
        foreach ($options as $option) {
            $param = $params->get($option, 0);
            if ($messageTypeId === $param) {
                return true;
            }
        }
        return false;
    }

}
