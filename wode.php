<?php
// getPreDay
$dayArray = array("20160101"=>"0","20160102"=>"1","20160103"=>"0","20160104"=>"0","20160105"=>"0");
function getPreDay($preDay){
	echo $preDay.'</br>';
	if ($preDay == "20160101") {
		echo 'ok</br>';
		return $preDay; 
	} else {
		$preDay = date('Ymd',strtotime('-1 day', strtotime($preDay)));
		return getPreDay($preDay);
	}
}

// getPreWorkingDay
function getPreWorkingDay($array, $preDay){
	echo 'current preDay: '.$preDay.'</br>';
	
	foreach ($array as $key => $val) {
		echo $key.' : '.$val.'</br>';
	 	if($key == $preDay) {
	 		echo $key.' :: '.$val.'</br>'; 
	 		if ($val == 1) {
	 			echo 'ok</br>';
	 			return $preDay;
	 		} else {
	 			$preDay = date('Ymd', strtotime('-1 day', strtotime($preDay)));
	 			return getPreWorkingDay($array, $preDay);
	 		}
	  	}
	}
	
	return $preDay;
}

$preDay = getPreWorkingDay($dayArray, "20160105");
// $preDay = getPreDay("20160105");
print_r($preDay);
?>
