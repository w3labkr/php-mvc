<?php

namespace App\Helpers;

/**
 * Class Session
 *
 * Provides helper methods to easily get, set, check, and delete session variables.
 */
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
     * Retrieve the value of a session variable.
     *
     * @param string $key The session key.
     * @return mixed|null The value of the session variable if it exists, or null if not set.
     */
    public function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * Set a session variable.
     *
     * @param string $key The session key.
     * @param mixed  $value The value to set.
     * @return void
     */
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    /**
     * Delete a session variable.
     *
     * @param string $key The session key.
     * @return bool True if the session variable was deleted, false if it didn't exist.
     */
    public function delete($key) {
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
     * @return bool True if the session variable was deleted, false if it didn't exist.
     */
    public function del($key) {
        return $this->delete($key);
    }

    /**
     * Check if a session variable exists.
     *
     * @param string $key The session key.
     * @return bool True if the session variable exists, false otherwise.
     */
    public function exists($key) {
        return isset($_SESSION[$key]);
    }

    /**
     * Check if a session variable does not exist.
     *
     * @param string $key The session key.
     * @return bool True if the session variable does not exist, false otherwise.
     */
    public function noexists($key) {
        return !isset($_SESSION[$key]);
    }
}
