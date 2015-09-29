<?php

function JsonDb_is_date($s, $format='yyyy-mm-dd'){
    switch ($format){
        case 'yyyy-mm-dd' :
            if(preg_match('/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/', $s, $matches)){
                return checkdate ($matches[2], $matches[3], $matches[1]);
            }
            return false;
        case 'mm/dd/yyyy' :
            if(preg_match('@^([0-9]{2})/([0-9]{2})/([0-9]{4})$@', $s, $matches)){
                return checkdate ($matches[1], $matches[2], $matches[3]);
            }
            return false;
        case 'dd/mm/yyyy' :
            if(preg_match('@^([0-9]{2})/([0-9]{2})/([0-9]{4})$@', $s, $matches)){
                return checkdate ($matches[2],$matches[1], $matches[3]);
            }
            return false;
    }
    return false;
}