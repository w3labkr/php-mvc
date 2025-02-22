<?php

if (!function_exists('e')) {
    /**
     * 문자열을 HTML 이스케이프 처리하여 XSS 공격을 방어합니다.
     *
     * @param string $string
     * @return string
     */
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
