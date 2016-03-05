<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_issnregistry
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
            'message_type_form_handled'
        );
    }

    /**
     * Checks if the given message type is identifier created.
     * @param string $messageType message type
     * @return boolean true if identifier created; otherwise false
     */
    public static function isPublicationIdentifierCreated($messageType) {
        if (strcmp($messageType, 'message_type_form_handled') == 0) {
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
        $params = JComponentHelper::getParams('com_issnregistry');
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
