<?php

// checks whether the string is unset, or exists at all.
function isStringSet ($array_key, $empty_ok = false) {
    
    switch ($_SERVER["REQUEST_METHOD"]) {
        case "POST":

            //checks whether array key exists, and if its value is an empty string or not.
            $is_string_set = array_key_exists($array_key, $_POST) ? ( ( empty($_POST[$array_key]) ? true : false ) ? false : true ) : false;

            if(!array_key_exists($array_key, $_POST) || (array_key_exists($array_key, $_POST) && !$is_string_set && !$empty_ok) ) {
                //returns that the key is unset, and therefor the entire array.
                return false;
            } else if(array_key_exists($array_key, $_POST) && $empty_ok) {
                return true;
            }
            return $is_string_set;
            break;

        case "GET":
            $is_string_set = array_key_exists($array_key, $_GET) ? ( ( empty($_GET[$array_key]) ? true : false ) ? false : true ) : false;

            if(!array_key_exists($array_key, $_GET) || (array_key_exists($array_key, $_GET) && !$is_string_set && !$empty_ok) ) {
                //returns that the key is unset, and therefor the entire array.
                return false;
            } else if(array_key_exists($array_key, $_GET) && $empty_ok) {
                return true;
            }
            return $is_string_set;
            break;
    }
}


//grabs, checks, and sanitizes entry.
function getValue($key_or_array, $empty_ok = false) {

    //Method for grabbing if array of keys is sent.
    if (isset($key_or_array)) {

        // if the variable is an array.
        if(is_array($key_or_array)) { 

            $variables = array();
            $variables["missing_variables"] = false;
            $variables["variables_missing"] = "";
            foreach ($key_or_array as $variable) { //parsing each index.

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    if(!isStringSet($variable, $empty_ok)) {
                        //returns that the key is unset, and therefor the entire array.
                        $variables[$variable] = false;
                        $variables["missing_variables"] = true;
                        $variables["variables_missing"] .= $variable . ", ";
                    } else {
                        $variables[$variable] = trimEntry($_POST[$variable]);
                    }
                } else if ($_SERVER["REQUEST_METHOD"] == "GET") {

                    if(!isStringSet($variable, $empty_ok)) {

                        //returns that the key is unset, and therefor the entire array.
                        $variables[$variable] = false;
                        $variables["missing_variables"] = true;
                        $variables["variables_missing"] .= $variable . ", ";
                    } else {
                        $variables[$variable] = trimEntry($_GET[$variable]);
                    }
                }
            }

            return $variables;

        // if the variable is not an array.
        } else { 

            $variable = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    
                if(!isStringSet($variable, $empty_ok)) {
                    //returns that the key is unset, and therefor the entire array.
                    $variable = false;
                } else {
                    $variable = trimEntry($_POST[$key_or_array]);
                }
            } else if ($_SERVER["REQUEST_METHOD"] == "GET") {

                if(!isStringSet($variable, $empty_ok)) {
                    //returns that the key is unset, and therefor the entire array.
                    $variable = false;
                } else {
                    $variable = trimEntry($_GET[$key_or_array]);
                }
            }
            return $variable;
        }
    } else {
        return;
    }
}
?>