<?PHP 
class DateTimeFunction{
    function changeDateFormat($date,$char = '-'){
        $dt = explode(' ',$date);
        $dt = explode($char,$dt[0]);
        return $dt[2].$char.$dt[1].$char.$dt[0];
    }
}

?>