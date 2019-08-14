<?php
declare (strict_types=1);

namespace Project\Utilities;

use Project\Module\GenericValueObject\Price;

/**
 * Class Converter
 * @package Project\Utilities
 */
class Converter
{
    protected const L_NUMERAL = [
        'null',
        'ein',
        'zwei',
        'drei',
        'vier',
        'fünf',
        'sechs',
        'sieben',
        'acht',
        'neun',
        'zehn',
        'elf',
        'zwölf',
        'dreizehn',
        'vierzehn',
        'fünfzehn',
        'sechzehn',
        'siebzehn',
        'achtzehn',
        'neunzehn'
    ];

    protected const L_TRENNER = ['', '', 'zwanzig', 'dreißig', 'vierzig', 'fünfzig', 'sechzig', 'siebzig', 'achtzig', 'neunzig'];

    protected const L_GROUP_SUFFIX = [['s', ''], ['tausend ', 'tausend '], ['e Million ', ' Millionen '], ['e Milliarde ', ' Milliarden '], ['e Billion ', ' Billionen '], ['e Billiarde ', ' Billiarden '], ['e Trillion ', ' Trillionen ']];

    protected const GERMAN_WEEKDAYS = [
        'long' => [
            0 => 'Sonntag',
            1 => 'Montag',
            2 => 'Dienstag',
            3 => 'Mittwoch',
            4 => 'Donnerstag',
            5 => 'Freitag',
            6 => 'Samstag'
        ],
        'short' => [
            0 => 'So',
            1 => 'Mo',
            2 => 'Di',
            3 => 'Mi',
            4 => 'Do',
            5 => 'Fr',
            6 => 'Sa'
        ]
    ];


    /**
     * @param Price|null $price
     *
     * @return string
     */
    public static function num2text(Price $price = null): string
    {
        if ($price === null) {
            return self::L_NUMERAL[0]; // null
        }

        $number = $price->getPrice();

        if ($number < 0) {
            return NUMERAL_SIGN . ' ' . self::num2text_group(abs($number));
        }

        return self::num2text_group($number);
    }

    /**
     * @param int $day
     * @return string
     */
    public static function convertIntToWeekday(int $day): string
    {
        if ($day < 0 && $day > 6) {
            return '';
        }

        return self::GERMAN_WEEKDAYS['long'][$day];
    }

    /**
     * @param int $day
     * @return string
     */
    public static function convertIntToWeekdayShort(int $day): string
    {
        if ($day < 0 && $day > 6) {
            return '';
        }

        return self::GERMAN_WEEKDAYS['short'][$day];
    }

    /**
     * @param int $seconds
     *
     * @return string
     */
    public static function convertDiff(int $seconds): string
    {
        // Hour
        $hour = floor($seconds / 3600);
        if ($hour < 10) {
            $hour = '0' . $hour;
        }

        // Minute
        $minute = floor(($seconds % 3600) / 60);
        if ($minute < 10) {
            $minute = '0' . $minute;
        }

        // Second
        $second = $seconds - ($hour * 3600 + $minute * 60);
        if ($second < 10) {
            $second = '0' . $second;
        }

        return $hour . ':' . $minute . ':' . $second;
    }


    /**
     * Wenn sortiert werden soll, muss ich die Umlaute der deutschen Sprache umwandeln, damit PHP korrekt sortieren kann.
     *
     * @param string $string $string
     *
     * @return string
     */
    public static function convertForSort(string $string): string
    {
        return str_replace(['ä', 'ö', 'ü', 'Ä', 'Ö', 'Ü', 'ß'], ['a', 'o', 'u', 'A', 'O', 'U', 'ss'], $string);
    }

    /**
     * @param     $number
     * @param int $pGroupLevel
     *
     * @return string
     */
    protected static function num2text_group($number, $pGroupLevel = 0): string
    {
        $lResult = '';
        /* Ende der Rekursion ist erreicht, wenn Zahl gleich Null ist */
        /** @noinspection TypeUnsafeComparisonInspection */
        if ($number == 0) {
            return '';
        }

        /* Zahlengruppe dieser Runde bestimmen */
        $lGroupNumber = $number % 1000;

        /* Zahl der Zahlengruppe ist Eins */
        /** @noinspection TypeUnsafeComparisonInspection */
        /** @noinspection TypeUnsafeComparisonInspection */
        if ($lGroupNumber == 1) {
            $lResult = self::L_NUMERAL[1] . self::L_GROUP_SUFFIX[$pGroupLevel][0]; // „eine Milliarde“

            /* Zahl der Zahlengruppe ist größer als Eins */
        } elseif ($lGroupNumber > 1) {
            $lResult = '';

            /* Zahlwort der Hunderter */
            $lFirstDigit = (int)floor($lGroupNumber / 100);

            if ($lFirstDigit > 0) {
                $lResult .= self::L_NUMERAL[$lFirstDigit] . NUMERAL_HUNDREDS_SUFFIX; // „fünfhundert“
            }

            /* Zahlwort der Zehner und Einer */
            $lLastDigits = $lGroupNumber % 100;
            $lSecondDigit = (int)floor($lLastDigits / 10);
            $lThirdDigit = $lLastDigits % 10;

            /** @noinspection TypeUnsafeComparisonInspection */
            if ($lLastDigits == 1) {
                $lResult .= self::L_NUMERAL[1] . 's'; // "eins"
            } elseif ($lLastDigits > 1 && $lLastDigits < 20) {
                $lResult .= self::L_NUMERAL[$lLastDigits]; // "dreizehn"
            } elseif ($lLastDigits >= 20) {
                if ($lThirdDigit > 0) {
                    $lResult .= self::L_NUMERAL[$lThirdDigit] . NUMERAL_INFIX; // "sechsund…"
                }
                $lResult .= self::L_TRENNER[$lSecondDigit]; // "…achtzig"
            }

            /* Suffix anhängen */
            $lResult .= self::L_GROUP_SUFFIX[$pGroupLevel][1]; // "Millionen"
        }

        /* Nächste Gruppe auswerten und Zahlwort zurückgeben */
        return self::num2text_group(floor($number / 1000), $pGroupLevel + 1) . $lResult;
    }
}