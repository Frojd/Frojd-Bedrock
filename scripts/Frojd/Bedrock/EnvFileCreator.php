<?php
namespace Frojd\Bedrock;

class EnvFileCreator {

    public static function createIfNotExists() {
        define('ENV_PATH', __DIR__ . '/../../../.env');
        define('ENV_EXAMPLE_PATH', __DIR__ . '/../../../.env.example');

        if (!file_exists(ENV_PATH)) {
            echo ".env file is missing. Creating...\n";
            self::createEnvFile();
        }
    }

    public static function createEnvFile() {
        $inFile = fopen(ENV_EXAMPLE_PATH, "r");
        $outFile = fopen(ENV_PATH, "w");

        while (($line = fgets($inFile)) !== false) {

            if (($placeholder = self::getPlaceholders($line))) {
                if ($placeholder['nicename'] == '__salt') {
                    $value = self::generateSalt();
                } else {
                    $value = self::getUserAnswer($placeholder['nicename']);
                }

                $line = str_replace($placeholder['placeholder'], $value, $line);
            }

            fputs($outFile, $line);
        }


        fclose($inFile);
        fclose($outFile);
    }

    public static function getPlaceholders($line) {
        if (preg_match('|{{(.+?)}}|', $line, $matches)) {
            return [
                'placeholder' => $matches[0],
                'nicename' => trim($matches[1]),
            ];
        }

        return false;
    }


    public static $userAnswers = [];
    public static function getUserAnswer($placeholderNiceName) {
        if (isset(self::$userAnswers[$placeholderNiceName])) {
            return self::$userAnswers[$placeholderNiceName];
        }

        echo sprintf("%s:", $placeholderNiceName);
        $answer = self::stripNewlines(fgets(STDIN));

        self::$userAnswers[$placeholderNiceName] = $answer;

        return $answer;
    }

    public static function stripNewLines($string) {
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    /**
     * Slightly modified/simpler version of wp_generate_password
     * https://github.com/WordPress/WordPress/blob/cd8cedc40d768e9e1d5a5f5a08f1bd677c804cb9/wp-includes/pluggable.php#L1575
     */
    public static function generateSalt($length = 64) {
        $chars  = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $chars .= '!@#$%^&*()';
        $chars .= '-_ []{}<>~`+=,.;:/?|';
        $salt = '';
        for ($i = 0; $i < $length; $i++) {
            $salt .= substr($chars, rand(0, strlen($chars) - 1), 1);
        }
        return $salt;
    }
}
