<?php

if(! function_exists('slugify')) {
    function slugify($string, $separator = '-'): string
    {
        if (is_null($string)) {
            return "";
        }

        // Remove spaces from the beginning and from the end of the string
        $string = trim($string);

        $string = str_replace('/', $separator, $string);
        $string = str_replace('\\', $separator, $string);

        // Lower case everything
        // using mb_strtolower() function is important for non-Latin UTF-8 string | more info: https://www.php.net/manual/en/function.mb-strtolower.php
        $string = mb_strtolower($string, "UTF-8");;

        // Make alphanumeric (removes all other characters)
        // this makes the string safe especially when used as a part of a URL
        // this keeps latin characters and arabic charactrs as well
        $string = preg_replace("/[^a-z0-9_\s\-ءاأإآؤئبتثجحخدذرزسشصضطظعغفقكلمنهويةى]#u/", "", $string);

        // Remove multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);

        // Convert whitespaces and underscore to the given separator
        return preg_replace("/[\s_]/", $separator, $string);
    }
}
