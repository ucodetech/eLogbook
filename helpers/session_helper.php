<?php


//Flash message helper
		function flash($name = '', $message = '', $class = 'alert alert-success'){
			if (!empty($name)) {
				if (!empty($message) && empty($_SESSION[$name])) {
					if (!empty($_SESSION[$name])) {
						unset($_SESSION[$name]);
					}
					if (!empty($_SESSION[$name . '_class'])) {
						unset($_SESSION[$name. '_class']);
					}

					$_SESSION[$name] = $message;
					$_SESSION[$name . '_class'] = $class;

				}else if (empty($message) && !empty($_SESSION[$name])) {
					$class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
					echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';
					unset($_SESSION[$name]);
					unset($_SESSION[$name. '_class']);
				}
			}

			}

 function isLoggedInUser(){
        if (isset( $_SESSION[Config::get('session/session_nameUr')])) {
            return true;
            }else{
                return false;
            }

     }

function generateKey(){
		 	$keyLength = 32;
		 	$str = "1234567890abcdefghijklmnopqrstuvwxyz()@#$";
		 	$randStr = substr(str_shuffle($str), 0, $keyLength);
		 	return $randStr;
		 }

function generateKey8(){
		 	$keyLength = 8;
		 	$str = "1234567890abcdefghijklmnopqrstuvwxyz()/\@#$[]";
		 	$randStr = substr(str_shuffle($str), 0, $keyLength);
		 	return $randStr;
		 }

function generateKey2(){
		 	$randStr = uniqid('watch');
		 	return $randStr;
		 }
function endOFMonth($date){
    $date = new DateTime($date);
    $date->modify('last day of this month');
    return $date->format('Y-m-d');
}


function pretty_days($date)
{
    return date('t', strtotime($date));
}

function pretty_dated($date){
		 		return date("Y-m-d", strtotime($date));
		 	}


function pretty_date($date){
		 		return date("M d, Y h:i A", strtotime($date));
		 	}
function pretty_dates($dates){
		 		return date("M d, Y ", strtotime($dates));
		 	}
function pretty_datee($dates){
		 		return date("h:i A ", strtotime($dates));
		 	}

function    pretty_num($m){
    return date('m', strtotime($m));
}
function    pretty_numD($d){
    return date('d', strtotime($d));
}
function pretty_year($year)
{
	return date("Y", strtotime($year));
}

function pretty_day($day)
{
	return date("d", strtotime($day));
}
function pretty_dayLetterd($day)
{
    return date("D", strtotime($day));
}
function pretty_monthLetter($month)
{
    return date("M", strtotime($month));
}
function pretty_monthNumber($month)
{
    return date("m", strtotime($month));
}

function pretty_time($time){
		 		return date("h:i a");
		 	}
function pretty_time1($time){
		 		return date("h:i");
		 	}
function pretty_nameDay($day){
	return date('D d  M, Y');
}
function timeAgo($time){
	date_default_timezone_set('Africa/Lagos');
	$time = strtotime($time) ? strtotime($time) : $time;
	$timed = time() - $time;

	switch ($timed) {
    case $timed <= 60:
        return 'Just Now!';
        break;
    case $timed >= 60 && $timed < 3600:
        return (round($timed/60) == 1) ? 'a minute ago' : round($timed/60). ' minutes ago';
        break;
    case $timed >= 3600 && $timed < 86400:
        return (round($timed/3600) == 1) ? 'an hour ago' : round($timed/3600). '  hours ago';
        break;
		case $timed >= 86400 && $timed < 604800:
        return (round($timed/86400 ) == 1) ? 'a day ago' : round($timed/86400 ). '  days ago';
        break;

		case $timed >= 604800 && $timed < 2600640:
				return (round($timed/604800 ) == 1) ? 'a week ago' : round($timed/604800 ). '  weeks ago';
				break;
		case $timed >=  2600640 && $timed < 31207680:
				return (round($timed/604800 ) == 1) ? 'a month ago' : round($timed/604800 ). '  months ago';
				break;
		case $timed >= 31207680:
				return (round($timed/31207680 ) == 1) ? 'a year ago' : round($timed/31207680 ). '  years ago';
				break;
}

}
function dateDiffInDays($date1, $date2)
{
    // Calculating the difference in timestamps
    $diff = strtotime($date2) - strtotime($date1);

    // 1 day = 24 hours
    // 24 * 60 * 60 = 86400 seconds
    return abs(round($diff / 86400));
}

function wrap($string) {
	   $wstring = explode("\n", wordwrap($string, 30, "\n") );
	   return $wstring[0];
	}
function wrap3($string) {
	   $wstring = explode("\n", wordwrap($string, 100, "\n") );
	   return $wstring[0];
	}

function sizeFilter( $bytes )
{
    $label = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
    for( $i = 0; $bytes >= 1024 && $i < ( count( $label ) -1 ); $bytes /= 1024, $i++ );
    return( round( $bytes, 2 ) . " " . $label[$i] );
}


function weekOfMonth($date) {
    //Get the first day of the month.
    $firstOfMonth = strtotime(date("Y-m-01", $date));
    //Apply above formula.
    return weekOfYear($date) - weekOfYear($firstOfMonth) + 1;
}

function weekOfYear($date) {
    $weekOfYear = intval(date("W", $date));
    if (date('n', $date) == "1" && $weekOfYear > 51) {
        // It's the last week of the previous year.
        return 0;
    }
    else if (date('n', $date) == "12" && $weekOfYear == 1) {
        // It's the first week of the next year.
        return 53;
    }
    else {
        // It's a "normal" week.
        return $weekOfYear;
    }
}
