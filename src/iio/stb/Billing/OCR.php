<?php
/**
 * This file is part of Swedish-Technical-Bureaucracy.
 *
 * Copyright (c) 2012-14 Hannes Forsgård
 *
 * Swedish-Technical-Bureaucracy is free software: you can redistribute it
 * and/or modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation, either version 3 of the License,
 * or (at your option) any later version.
 *
 * Swedish-Technical-Bureaucracy is distributed in the hope that it will be
 * useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General
 * Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with Swedish-Technical-Bureaucracy.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace iio\stb\Billing;

use iio\stb\Exception\InvalidStructureException;
use iio\stb\Exception\InvalidLengthDigitException;
use iio\stb\Exception\InvalidCheckDigitException;
use iio\stb\Utils\Modulo10;

/**
 * OCR number generation and validation
 *
 * @author Hannes Forsgård <hannes.forsgard@fripost.org>
 */
class OCR
{
    /**
     * @var string Internal ocr representation
     */
    private $ocr = '';

    /**
     * OCR number generation and validation
     * 
     * OCR must have a valid check and length digits
     *
     * @param  string                      $ocr
     * @throws InvalidStructureException   If ocr is not numerical or longer than 25 digits
     * @throws InvalidLengthDigitException If length digit is invalid
     * @throws InvalidCheckDigitException  If check digit is invalid
     */
    public function __construct($ocr)
    {
        // Validate length
        if (!is_string($ocr)
            || !ctype_digit($ocr)
            || strlen($ocr) > 25
            || strlen($ocr) < 2
        ) {
            throw new InvalidStructureException("\$ocr must be numeric and contain between 2 and 25 digits");
        }

        $arOcr = str_split($ocr);
        $check = array_pop($arOcr);
        $length = array_pop($arOcr);
        $base = implode('', $arOcr);

        // Validate length digit
        if ($length != self::calcLengthDigit($base)) {
            throw new InvalidLengthDigitException("Invalid length digit");
        }

        // Validate check digit
        if ($check != Modulo10::getCheckDigit($base . $length)) {
            throw new InvalidCheckDigitException("Invalid check digit");
        }

        $this->ocr = $ocr;
    }

    /**
     * Get OCR as string
     *
     * @return string
     */
    public function getOCR()
    {
        return $this->ocr;
    }

    /**
     * Get OCR as string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getOCR();
    }

    /**
     * Create OCR from number
     * 
     * Check and length digits are appended
     *
     * @param  string                    $nr
     * @return OCR
     * @throws InvalidStructureException If $nr is invalid
     */
    public static function create($nr)
    {
        if (!is_string($nr) || !ctype_digit($nr) || strlen($nr) > 23) {
            throw new InvalidStructureException("\$nr must be numeric and contain a maximum of 23 digits");
        }

        // Calculate and append length digit
        $nr .= self::calcLengthDigit($nr);

        // Calculate and append check digit
        $nr .= Modulo10::getCheckDigit($nr);

        return new OCR($nr);
    }

    /**
     * Calculate length digit for string
     *
     * The length of $nr plus 2 is used, to take length and check digits into
     * account.
     *
     * @param  $nr
     * @return string
     */
    private static function calcLengthDigit($nr)
    {
        return (string)(strlen($nr) + 2) % 10;
    }
}