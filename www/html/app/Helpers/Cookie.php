<?php

namespace App\Helpers;

class Cookie {

    /**
     * Retrieve the value of a cookie, or return a default value if not set.
     *
     * @param string $name    The name of the cookie.
     * @param mixed  $default Optional default value to return if the cookie is not found. Default is null.
     * @return mixed          The cookie value if it exists, otherwise the default value.
     */
    public function get(string $name, $default = null) {
        return $_COOKIE[$name] ?? $default;
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
    public function set(string $name, string $value, int $expire = 3600, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = true): bool {
        $expireTime = $expire !== 0 ? time() + $expire : 0;
        $result = setcookie($name, $value, $expireTime, $path, $domain, $secure, $httponly);

        // Update the $_COOKIE superglobal for immediate access if setcookie succeeds.
        if ($result) {
            $_COOKIE[$name] = $value;
        }
        return $result;
    }

    /**
     * Check if a cookie exists.
     *
     * @param string $name The name of the cookie.
     * @return bool True if the cookie exists, false otherwise.
     */
    public function exists(string $name): bool {
        return isset($_COOKIE[$name]);
    }

    /**
     * Check if a cookie does not exist.
     *
     * @param string $name The name of the cookie.
     * @return bool True if the cookie does not exist, false otherwise.
     */
    public function noexists(string $name): bool {
        return !isset($_COOKIE[$name]);
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
    public function delete(string $name, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = true): bool {
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
    public function del(string $name, string $path = '/', string $domain = '', bool $secure = false, bool $httponly = true): bool {
        return $this->delete($name, $path, $domain, $secure, $httponly);
    }
}
