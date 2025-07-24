<?php
class Logger {
    private static $log_file = __DIR__ . '/../logs/app.log';
    
    public static function log($message, $level = 'INFO') {
        $timestamp = date('[Y-m-d H:i:s]');
        $log_message = "$timestamp $level: $message" . PHP_EOL;
        
        // Créer le répertoire logs s'il n'existe pas
        if (!file_exists(dirname(self::$log_file))) {
            mkdir(dirname(self::$log_file), 0755, true);
        }
        
        file_put_contents(self::$log_file, $log_message, FILE_APPEND);
    }
    
    public static function error($message) {
        self::log($message, 'ERROR');
    }
    
    public static function info($message) {
        self::log($message, 'INFO');
    }
}
?>