<?php
/**
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace ledgr\id\Formatter;

use ledgr\id\Id;

/**
 * Id formatter
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class Formatter implements FormatTokens
{
    use FormattingFunctions;

    /**
     * @var \Closure Formatting function, takes an Id object and returns a string
     */
    private $formatter;

    /**
     * Create formatter from format string
     *
     * @param string $format
     */
    public function __construct($format = '')
    {
        // Register empty formatting function
        $this->formatter = function () {
            return '';
        };

        // Used to track escaping state
        $escape = '';

        foreach (str_split($format) as $token) {
            switch ($escape . $token) {
                case self::TOKEN_DATE_YEAR_FULL:
                case self::TOKEN_DATE_YEAR:
                case self::TOKEN_DATE_MONTH:
                case self::TOKEN_DATE_MONTH_SHORT:
                case self::TOKEN_DATE_MONTH_TEXT:
                case self::TOKEN_DATE_MONTH_TEXT_SHORT:
                case self::TOKEN_DATE_MONTH_DAYS:
                case self::TOKEN_DATE_WEEK:
                case self::TOKEN_DATE_DAY:
                case self::TOKEN_DATE_DAY_SHORT:
                case self::TOKEN_DATE_DAY_TEXT:
                case self::TOKEN_DATE_DAY_TEST_SHORT:
                case self::TOKEN_DATE_DAY_NUMERIC:
                case self::TOKEN_DATE_DAY_NUMERIC_ISO:
                case self::TOKEN_DATE_DAY_OF_YEAR:
                    $this->registerFormatter(function (Id $id) use ($token) {
                        return $id->getDate()->format($token);
                    });
                    break;
                case self::TOKEN_DATE_CENTURY:
                case self::TOKEN_SERIAL_PRE:
                case self::TOKEN_SERIAL_POST:
                case self::TOKEN_DELIMITER:
                case self::TOKEN_CHECK_DIGIT:
                case self::TOKEN_SEX:
                case self::TOKEN_AGE:
                case self::TOKEN_LEGAL_FORM:
                case self::TOKEN_BIRTH_COUNTY:
                    $this->registerFormatter([$this, self::$tokenMap[$token]]);
                    break;
                case self::TOKEN_ESCAPE:
                    $escape = $token;
                    break;
                default:
                    $escape = '';
                    $this->registerFormatter(function() use ($token) {
                        return $token;
                    });
            }
        }
    }

    /**
     * Register formatting function
     *
     * Registered function must take an Id object and return a string
     *
     * @param  callable $formatter Formatting function
     * @return void
     */
    public function registerFormatter(callable $formatter)
    {
        $oldFormatter = $this->formatter;
        $this->formatter = function (Id $id) use ($oldFormatter, $formatter) {
            return $oldFormatter($id) . $formatter($id);
        };
    }

    /**
     * Format id using registered formatting functions
     *
     * @param  Id $id 
     * @return string
     */
    public function format(Id $id)
    {
        $formatter = $this->formatter;
        return $formatter($id);
    }
}
