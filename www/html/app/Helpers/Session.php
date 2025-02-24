<?php

namespace App\Helpers;

class Session {

    /**
     * Session constructor.
     * Starts a session if one hasn't been started already.
     */
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Retrieve a session value.
     *
     * @param string $key The session key.
     * @param mixed  $default The default value to return if the key doesn't exist. Default is null.
     * @return mixed The session value or the default value.
     */
    public function get(string $key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set a session value.
     *
     * @param string $key   The session key.
     * @param mixed  $value The value to set.
     * @return void
     */
    public function set(string $key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Check if a session key exists.
     *
     * @param string $key The session key.
     * @return bool True if the key exists, false otherwise.
     */
    public function exists(string $key) {
        return isset($_SESSION[$key]);
    }

    /**
     * Check if a session key does not exist.
     *
     * @param string $key The session key.
     * @return bool True if the key does not exist, false otherwise.
     */
    public function noexists(string $key) {
        return !isset($_SESSION[$key]);
    }

    /**
     * Delete a session key.
     *
     * @param string $key The session key.
     * @return bool True if the key was deleted, false if it didn't exist.
     */
    public function delete(string $key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }

    /**
     * Alias for the delete method.
     *
     * @param string $key The session key.
     * @return bool True if the key was deleted, false otherwise.
     */
    public function del(string $key) {
        return $this->delete($key);
    }
}
