<?php
declare (strict_types=1);

namespace Project\Utilities;

/**
 * Class Tools
 * @package Project\Utilities
 */
class Tools
{
    protected const STANDARD_URL = 'index.php';

    /**
     * @param string $name
     * @return bool|string|int
     */
    public static function getValue(string $name)
    {
        if (isset($_GET[$name]) && empty($_GET[$name]) === false) {
            return $_GET[$name];
        }

        if (isset($_POST[$name]) && empty($_POST[$name]) === false) {
            return $_POST[$name];
        }

        if (isset($_SESSION[$name]) && empty($_SESSION[$name]) === false) {
            return $_SESSION[$name];
        }

        return false;
    }

    /**
     * @param string $name
     * @return bool|array
     */
    public static function getFile(string $name)
    {
        if (isset($_FILES[$name]) && empty($_FILES[$name]) === false && $_FILES[$name]['error'] === 0) {
            return $_FILES[$name];
        }

        return false;
    }

    /**
     * @param string $route
     * @return string
     */
    public static function getRouteUrl(string $route = '', array $parameter = []): string
    {
        if (empty($route)) {
            return self::STANDARD_URL;
        }

        $url = self::STANDARD_URL . '?route=' . $route;

        foreach ($parameter as $key => $value) {
            $url .= '&' . $key . '=' . $value;
        }

        return $url;
    }

    /**
     * @param string $text
     * @param int    $amount
     * @param bool   $points
     * @return string
     */
    public static function shortener(string $text, int $amount = 50, bool $points = true): string
    {
        if (\strlen($text) <= $amount) {
            return $text;
        }

        $newText = substr($text, 0, $amount);

        if ($points === true) {
            $newText .= ' ...';
        }

        return $newText;
    }
}