<?php

namespace App\Helpers;

/**
 * Class Cookie
 *
 * Provides helper methods to easily get, set, check, and delete cookies.
 */
class Cookie {

    /**
     * Retrieve the value of a cookie.
     *
     * @param string $name The name of the cookie.
     * @return mixed|null The cookie value if it exists, or null if not set.
     */
    public function get($name) {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    /**
     * Set a cookie with the specified parameters.
     *
     * @param string  $name     The name of the cookie.
     * @param string  $value    The value of the cookie.
     * @param int     $expire   The expiration time in seconds from now.
     *                          Default is 3600 (1 hour). Set to 0 for a session cookie.
     * @param string  $path     The path on the server where the cookie will be available. Default is "/".
     * @param string  $domain   The (sub)domain that the cookie is available to. Default is empty.
     * @param bool    $secure   If true, the cookie will only be transmitted over a secure HTTPS connection. Default is false.
     * @param bool    $httponly If true, the cookie will be accessible only through the HTTP protocol. Default is true.
     * @return bool  Returns true on success or false on failure.
     */
    public function set($name, $value, $expire = 3600, $path = '/', $domain = '', $secure = false, $httponly = true) {
        $expireTime = $expire !== 0 ? time() + $expire : 0;
        $result = setcookie($name, $value, $expireTime, $path, $domain, $secure, $httponly);

        // Update the $_COOKIE superglobal for immediate access if setcookie succeeds.
        if ($result) {
            $_COOKIE[$name] = $value;
        }
        return $result;
    }

    /**
     * Delete a cookie by setting its expiration time to the past.
     *
     * @param string  $name     The name of the cookie to delete.
     * @param string  $path     The path on the server where the cookie was available. Default is "/".
     * @param string  $domain   The (sub)domain that the cookie was available to. Default is empty.
     * @param bool    $secure   If true, the cookie will be deleted only over a secure HTTPS connection. Default is false.
     * @param bool    $httponly If true, the cookie will be deleted in a way that prevents access via JavaScript. Default is true.
     * @return bool  Returns true on success or false on failure.
     */
    public function delete($name, $path = '/', $domain = '', $secure = false, $httponly = true) {
        $result = setcookie($name, '', time() - 3600, $path, $domain, $secure, $httponly);
        unset($_COOKIE[$name]);
        return $result;
    }

    /**
     * Alias for the delete method.
     *
     * @param string  $name     The name of the cookie to delete.
     * @param string  $path     The path on the server where the cookie was available. Default is "/".
     * @param string  $domain   The (sub)domain that the cookie was available to. Default is empty.
     * @param bool    $secure   If true, the cookie will be deleted only over a secure HTTPS connection. Default is false.
     * @param bool    $httponly If true, the cookie will be deleted in a way that prevents access via JavaScript. Default is true.
     * @return bool  Returns true on success or false on failure.
     */
    public function del($name, $path = '/', $domain = '', $secure = false, $httponly = true) {
        return $this->delete($name, $path, $domain, $secure, $httponly);
    }

    /**
     * Check if a cookie exists.
     *
     * @param string $name The name of the cookie.
     * @return bool True if the cookie exists, false otherwise.
     */
    public function exists($name) {
        return isset($_COOKIE[$name]);
    }

    /**
     * Check if a cookie does not exist.
     *
     * @param string $name The name of the cookie.
     * @return bool True if the cookie does not exist, false otherwise.
     */
    public function noexists($name) {
        return !isset($_COOKIE[$name]);
    }
}
