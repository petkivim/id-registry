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
     * Checks if publication language code must be used on message generation.
     * @param string $parameter message type parameter name
     * @return boolean true if publication language must be used; otherwise 
     * false
     */
    public static function usePublicationLanguage($parameter) {
        if (strcmp($parameter, 'message_type_identifier_created_isbn') == 0 || strcmp($parameter, 'message_type_identifier_created_ismn') == 0) {
            return true;
        }
        return false;
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
