<?php

/**
 * @Plugin 	"ID Registry - Serials Publishers - Forms"
 * @version 	1.0.0
 * @author 	Petteri Kivimäki
 * @copyright	Copyright (C) 2015 Petteri Kivimäki. All rights reserved.
 */
defined('_JEXEC') or die('Restricted access');

class IssnregistryFormsLogger {

    public static function log($message, $level) {
        $logPath = JPATH_BASE . '/logs/plg_issnregistry_forms.log.php';
        $exists = file_exists($logPath);
        $file = fopen($logPath, 'a');
        // Blocks if lock is locked
        if (flock($file, LOCK_EX)) { // exclusive lock
            if(!$exists) {
                fwrite($file, "#\n#<?php die('Forbidden.'); ?>\n\n");
            }
            fwrite($file, date('Y-m-d H:i:s') . "\t" . $_SERVER['REMOTE_ADDR'] . "\t" . $level . "\t" . $message . "\n");
            fflush($file);
            // Release lock
            flock($file, LOCK_UN);
        }
        fclose($file);
    }

}

?>
