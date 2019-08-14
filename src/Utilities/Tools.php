<?php
declare (strict_types=1);

namespace Project\Utilities;

use Exception;
use Project\Module\DefaultModel;
use function is_bool;
use function strlen;

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
        $value = false;

        if (isset($_GET[$name]) && empty($_GET[$name]) === false) {
            return $_GET[$name];
        }

        if (isset($_POST[$name]) && empty($_POST[$name]) === false) {
            return $_POST[$name];
        }

        if (isset($_SESSION[$name]) && empty($_SESSION[$name]) === false) {
            return $_SESSION[$name];
        }

        return $value;
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
     * @param string $default
     *
     * @return string
     */
    public static function getRefererRoute(string $default = ''): string
    {
        if (isset($_SERVER['HTTP_REFERER']) === false) {
            return $default;
        }

        $referer = $_SERVER['HTTP_REFERER'];

        $pos = strpos($referer, 'route=');

        if ($pos === false) {
            return $default;
        }

        return substr($referer, $pos + 6);
    }

    /**
     * @param string $route
     * @param array  $parameter
     *
     * @return string
     */
    public static function getRouteUrl(string $route = '', array $parameter = []): string
    {
        if (empty($route)) {
            return self::STANDARD_URL;
        }

        $url = self::STANDARD_URL . '?route=' . $route;

        foreach ($parameter as $key => $value) {
            if (is_bool($value) === true) {
                $value = (int)$value;
            }

            $url .= '&' . $key . '=' . $value;
        }

        return $url;
    }

    /**
     * @param string $text
     * @param int    $amount
     * @param bool   $points
     *
     * @return string
     */
    public static function shortener(string $text, int $amount = 50, bool $points = true): string
    {
        if (strlen($text) <= $amount) {
            return $text;
        }

        $newText = substr($text, 0, $amount);

        if ($points === true) {
            $newText .= ' ...';
        }

        return $newText;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public static function serializeObjectsInArray(array $array): array
    {
        $outputArray = [];

        /** @var DefaultModel $entry */
        foreach ($array as $key => $entry) {
            $outputArray[$key] = $entry->toArray();
        }

        return $outputArray;
    }

    /**
     * @param int $percentage
     *
     * @return bool
     * @throws Exception
     */
    public static function shallWeRefresh(int $percentage = 10): bool
    {
        $randomNumber = random_int(1, 100);

        return $randomNumber <= $percentage;
    }
}