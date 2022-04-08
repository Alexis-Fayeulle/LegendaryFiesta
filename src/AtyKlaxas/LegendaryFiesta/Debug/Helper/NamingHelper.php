<?php

namespace AtyKlaxas\LegendaryFiesta\Debug\Helper;

class NamingHelper
{

    const PASCAL_CASE = 'pascal_case';
    const CAMEL_CASE = 'camel_case';
    const SHOUT_CASE = 'shout_case';
    const SNAKE_CASE = 'snake_case';

    public static function convert_manual(string $from, string $to, string $string): string
    {
        if (
            !in_array($from, [self::PASCAL_CASE, self::CAMEL_CASE, self::SHOUT_CASE, self::SNAKE_CASE]) ||
            !in_array($to, [self::PASCAL_CASE, self::CAMEL_CASE, self::SHOUT_CASE, self::SNAKE_CASE])
        ) {
            return '';
        }

        return self::convertArrayToString($to, self::convertStringToArray($from, $string));
    }

    private static function detectCase(string $string): string
    {
        $have_upper_char = false;
        $have_lower_char = false;
        $have_underscore = false;
        $start_by = '';

        foreach (str_split($string) as $i => $char) {
            if ($char !== '_' && $char === strtoupper($char)) {
                if ($i === 0) {
                    $start_by = 'upper';
                }

                $have_upper_char = true;
            }

            if ($char !== '_' && $char === strtolower($char)) {
                if ($i === 0) {
                    $start_by = 'lower';
                }

                $have_lower_char = true;
            }

            if ($char === '_') {
                $have_underscore = true;
            }
        }

        $have_upper_and_lower = $have_lower_char && $have_upper_char;

        if ($have_upper_and_lower && $start_by === 'lower' && !$have_underscore) {
            return self::CAMEL_CASE;
        }

        if ($have_upper_and_lower && $start_by === 'upper' && !$have_underscore) {
            return self::PASCAL_CASE;
        }

        if ($have_upper_char && !$have_lower_char && $have_underscore) {
            return self::SHOUT_CASE;
        }

        if (!$have_upper_char && $have_lower_char && $have_underscore) {
            return self::SNAKE_CASE;
        }

        return '';
    }

    public static function convert_auto(string $to, string $string): string
    {
        if (!in_array($to, [self::PASCAL_CASE, self::CAMEL_CASE, self::SHOUT_CASE, self::SNAKE_CASE])) {
            return '';
        }

        return self::convert_manual(self::detectCase($string), $to, $string);
    }

    public static function convertStringToArray(string $case, string $string): array
    {
        $return = [];

        if (strlen($string) === 0) {
            return $return;
        }

        $index_word = 0;

        foreach (str_split($string) as $char) {
            if ($char === '_' && ($case === self::SHOUT_CASE || $case === self::SNAKE_CASE)) {
                $return[$index_word] = implode('', $return[$index_word] ?? []);
                $index_word++;
                continue;
            }

            if ($char === strtoupper($char) && ($case === self::PASCAL_CASE || $case === self::CAMEL_CASE)) {
                $return[$index_word] = implode('', $return[$index_word] ?? []);
                $index_word++;
            }

            $return[$index_word][] = $char;
        }

        $return[$index_word] = implode('', $return[$index_word]);

        return array_filter($return);
    }

    public static function convertArrayToString(string $case, array $string_list): string
    {
        $string_list = array_values($string_list);
        $return = [];

        if (empty($string_list)) {
            return '';
        }

        foreach ($string_list as $i => $word)
        {
            if ($case === self::SHOUT_CASE) {
                $word = strtoupper($word);
            } elseif ($case === self::SNAKE_CASE || $case === self::PASCAL_CASE || self::CAMEL_CASE) {
                $word = strtolower($word);
            }

            if ($case === self::PASCAL_CASE) {
                $word = ucfirst($word);
            } elseif ($case === self::CAMEL_CASE && $i !== 0) {
                $word = ucfirst($word);
            }

            $return[] = $word;
        }

        $separator = '';

        if ($case === self::SHOUT_CASE || $case === self::SNAKE_CASE) {
            $separator = '_';
        }

        return implode($separator, $return);
    }

}
