<?php





class Logger {
    private static $logFile = __DIR__ . '/../logs/error.log';

    public static function error($message, $exception = null) {
        $date = date('Y-m-d H:i:s');
        $errorMsg = "[{$date}] ERROR: {$message}";
        if ($exception) {
            $errorMsg .= " | Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
        }
        $errorMsg .= PHP_EOL;

        
        if (!is_dir(dirname(self::$logFile))) {
            mkdir(dirname(self::$logFile), 0777, true);
        }

        error_log($errorMsg, 3, self::$logFile);
    }
}
