<?php

namespace App\Helpers;

class Form {

    /**
     * Retrieve a value from the $_GET superglobal.
     *
     * @param string $key     The key in the $_GET array.
     * @param mixed  $default The default value to return if the key is not set.
     * @return mixed          The trimmed value from $_GET if it's a string, or the default value.
     */
    public function get(string $key, $default = null) {
        $value = $_GET[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }

    /**
     * Retrieve a value from the $_POST superglobal.
     *
     * @param string $key     The key in the $_POST array.
     * @param mixed  $default The default value to return if the key is not set.
     * @return mixed          The trimmed value from $_POST if it's a string, or the default value.
     */
    public function post(string $key, $default = null) {
        $value = $_POST[$key] ?? $default;
        return is_string($value) ? trim($value) : $value;
    }
}
