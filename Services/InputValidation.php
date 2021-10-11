<?php


abstract class InputValidation
{
    public function __construct()
    {

    }

    public function streamlineString($string)
    {
        $streamlinedString = str_ireplace(" ", "", $string);
        $trimmedString = trim($streamlinedString);
        $upperLetterString = strtoupper($trimmedString);
        return $upperLetterString;
    }

    public function sanitizeString($string)
    {
        $xssSanitizedString = htmlspecialchars($string);
        $sqlInjectionSanitizedString = htmlentities($xssSanitizedString, ENT_QUOTES, "UTF-8");
        return $sqlInjectionSanitizedString;
    }
}