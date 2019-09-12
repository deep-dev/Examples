<?php

   $format = 'd.m.Y';
   //$format = 'H.i';
   //$format = "H.i d.m.Y";
   $str = "01.01.2019";
   //$str = "25.54 01.01.2019";
   $date = date_parse_from_format($format, $str);
   
   if ($date['warning_count'] == 0 && $date['error_count'] == 0) {
       echo "Valid Date";
   } else
       echo "Not valid Date";
?>