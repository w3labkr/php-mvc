<?php

use App\Helpers\Session;
use App\Helpers\Cookie;
use App\Helpers\Form;
use App\Helpers\Mailer;

if (!function_exists('env')) {
    /**
     * Retrieve an environment variable from $_ENV.
     *
     * If the variable is not set, returns the provided default value.
     * If the value is a string enclosed in double quotes, the quotes are removed.
     * Additionally, converts the strings "true", "false", and "null" (case-insensitive)
     * to their respective boolean or null types.
     *
     * @param string $key     The environment variable key.
     * @param mixed  $default The default value to return if the key is not found.
     * @return mixed          The processed environment variable value, or the default value.
     */
    function env(string $key, $default = null) {
        $value = $_ENV[$key] ?? null;

        if ($value === null) {
            return $default;
        }

        // Convert string representations to appropriate types.
        switch (strtolower($value)) {
            case 'true':
                return true;
            case 'false':
                return false;
            case 'null':
                return null;
        }

        return $value;
    }
}

if (!function_exists('config')) {
    /**
     * Retrieve a configuration value using dot notation.
     *
     * The dot notation format is "filename.key.subkey". The first segment indicates the
     * configuration file (located in the config directory as filename.php), and the subsequent
     * segments indicate nested keys within that configuration array.
     *
     * Example: config('app.APP_NAME') will load the config/app.php file and return the value of the "APP_NAME" key.
     *
     * @param string $key     The configuration key in dot notation.
     * @param mixed  $default The default value to return if the key is not found.
     * @return mixed          The configuration value or the default value.
     */
    function config(string $key, $default = null) {
        // Split the key into parts using the dot notation.
        $parts = explode('.', $key);
        
        // The first part represents the file name.
        $file = array_shift($parts);

        // Cache for loaded configuration files.
        static $configs = [];

        // Load the configuration file if not already loaded.
        if (!isset($configs[$file])) {
            $path = CONFIG_PATH . DIRECTORY_SEPARATOR . "{$file}.php";
            if (file_exists($path)) {
                $configs[$file] = require $path;
            } else {
                return $default;
            }
        }

        // Retrieve the value using the remaining parts as nested keys.
        $value = $configs[$file];
        foreach ($parts as $part) {
            if (is_array($value) && array_key_exists($part, $value)) {
                $value = $value[$part];
            } else {
                return $default;
            }
        }
        return $value;
    }
}

if (!function_exists('session')) {
    /**
     * Helper function to access the Session instance.
     *
     * Usage:
     *   session()->get('key');
     *   session()->get('key', 'value');
     *   session()->set('key', 'value');
     *   session()->delete('key');
     *   session()->del('key');
     *   session()->exists('key');
     *   session()->noexists('key');
     *
     * @return Session The singleton instance of the Session helper.
     */
    function session() {
        static $sessionHelper = null;
        if ($sessionHelper === null) {
            $sessionHelper = new Session();
        }
        return $sessionHelper;
    }
}

if (!function_exists('cookie')) {
    /**
     * Helper function to access the Cookie instance.
     *
     * Usage:
     *   cookie()->get('key');
     *   cookie()->get('key', 'value');
     *   cookie()->set('key', 'value');
     *   cookie()->delete('key');
     *   cookie()->del('key');
     *   cookie()->exists('key');
     *   cookie()->noexists('key');
     *
     * @return Cookie The singleton instance of the Cookie helper.
     */
    function cookie() {
        static $cookieHelper = null;
        if ($cookieHelper === null) {
            $cookieHelper = new Cookie();
        }
        return $cookieHelper;
    }
}

if (!function_exists('form')) {
    /**
     * Retrieve the Form helper instance.
     *
     * Usage:
     *   form()->get('key', 'default');
     *   form()->post('key', 'default');
     *
     * @return Form The singleton instance of the Form helper.
     */
    function form() {
        static $formInstance = null;
        if ($formInstance === null) {
            $formInstance = new Form();
        }
        return $formInstance;
    }
}

if (!function_exists('mailer')) {
    /**
     * Retrieve the singleton instance of the Mailer helper.
     *
     * This function allows you to easily access the Mailer functionality using:
     *   mailer()->smtp();
     *
     * @return Mailer The Mailer instance.
     */
    function mailer(): Mailer {
        static $mailerInstance = null;
        if ($mailerInstance === null) {
            $mailerInstance = new Mailer();
        }
        return $mailerInstance;
    }
}

if (!function_exists('uuidv4')) {
    /**
     * Generate a UUID version 4.
     *
     * This function generates a random UUID (Universally Unique Identifier) according to the version 4 specification.
     * It uses random_bytes to generate 16 bytes of random data, sets the version and variant bits appropriately,
     * and returns the formatted UUID string.
     *
     * @return string The generated UUID v4.
     */
    function uuidv4(): string {
        $data = random_bytes(16);
    
        // Set the version to 4 (0100)
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    
        // Set the variant to RFC 4122 (10xx)
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    
        // Format the bytes into the UUID string: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

if (!function_exists('generate_csrf_token')) {
    /**
     * Generate a CSRF token.
     *
     * This function generates a random sequence of bytes of the specified length,
     * converts it to a hexadecimal string, and returns it. The resulting token can be
     * used to prevent Cross-Site Request Forgery (CSRF) attacks.
     *
     * @param int $length The number of bytes to generate (default is 32).
     * @return string The generated CSRF token as a hexadecimal string.
     */
    function generate_csrf_token(int $length = 32): string {
        return bin2hex(random_bytes($length));
    }
}

if (!function_exists('verify_csrf_token')) {
    /**
     * Verify the CSRF token against the token stored in session.
     *
     * @param string $token The CSRF token to verify.
     * @return bool Returns true if the provided token matches the session token; false otherwise.
     */
    function verify_csrf_token(string $token): bool {
        return session()->get('csrf_token') === $token;
    }
}

if (!function_exists('e')) {
    /**
     * Escape a string for safe HTML output to help prevent XSS attacks.
     *
     * @param string $string       The input string to escape.
     * @param bool   $doubleEncode Optional. Whether to encode existing HTML entities. Defaults to true.
     * @return string The escaped string.
     */
    function e(string $string, bool $doubleEncode = true): string {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8', $doubleEncode);
    }
}
