<?php

class date {
	
	function __construct() {
		
	}

	public static function agoFormat($ptime) {
		if (!is_numeric($ptime)) {
			$ptime = strtotime($ptime);
		}
	    $etime = time() - $ptime;

	    if ($etime < 1) {
	        return language::translate('agoTime', '0', language::translate('seconds'));
	    }

	    $a = array(12*30*24*60*60 => 'year',
	               30*24*60*60    => 'month',
	               24*60*60       => 'day',
	               60*60          => 'hour',
	               60             => 'minute',
	               1              => 'second');

	    foreach ($a as $secs => $str) {
	        $d = $etime / $secs;
	        if ($d >= 1) {
	            $r = round($d);
	            return language::translate('agoTime', $r, language::translate($str.($r > 1 ? 's' : '')));
	        }
	    }
	}

	public static function getAge($dob) {
	    list($year, $month, $day) = explode('-', $dob);

	    $yearDiff  = date('Y') - $year;
	    $monthDiff = date('m') - $month;
	    $dayDiff   = date('d') - $day;

	    if ($monthDiff < 0 || ($monthDiff == 0 && $dayDiff < 0)) {
	        $yearDiff--;
	    }

	    return $yearDiff;
	}

	public static function normalFormat($date, $format = 'dd/./mm/./yyyy') {
		$date = (is_numeric($date)) ? $date : strtotime($date);
		$format = explode('/', $format);
		$return = '';
		$i = 1;

		foreach ($format as $part) {
			if ($i % 2 != 0) {
				switch ($part) {
					case 'dd':
						$return .= date('d', $date);
						break;
					
					case 'mm':
						$return .= date('m', $date);
						break;

					case 'yyyy':
						$return .= date('Y', $date);
						break;

					case 'yy':
						$return .= date('y', $date);
						break;

					case 'hh':
						$return .= date('h', $date);
						break;

					case 'min':
						$return .= date('i', $date);
						break;

					case 'dd':
						$return .= date('d', $date);
						break;

					case 'ss':
						$return .= date('s', $date);
						break;

					default:
						$return .= date($part, $date);
						break;
				}
			} else {
				$return .= $part;
			}
			$i++;
		}
		return $return;
	}
}