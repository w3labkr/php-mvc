<?php

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

if (!function_exists('session')) {
    /**
     * Helper function to access the Session instance.
     *
     * Usage:
     *   session()->get('key');
     *   session()->set('key', 'value');
     *   session()->delete('key');
     *   session()->del('key');
     *   session()->exists('key');
     *   session()->noexists('key');
     *
     * @return App\Helpers\Session The singleton instance of the Session helper.
     */
    function session() {
        static $sessionHelper = null;
        if ($sessionHelper === null) {
            $sessionHelper = new App\Helpers\Session();
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
     *   cookie()->set('key', 'value');
     *   cookie()->delete('key');
     *   cookie()->del('key');
     *   cookie()->exists('key');
     *   cookie()->noexists('key');
     *
     * @return App\Helpers\Cookie The singleton instance of the Cookie helper.
     */
    function cookie() {
        static $cookieHelper = null;
        if ($cookieHelper === null) {
            $cookieHelper = new App\Helpers\Cookie();
        }
        return $cookieHelper;
    }
}
