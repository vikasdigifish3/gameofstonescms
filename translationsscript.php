<?php

$file_data = file_get_contents("../portals/lang/th.json");
$json_data = json_decode($file_data,true);
//print_r($json_data);die();

/*
foreach ($json_data as $key => $value) {
	echo($key."\n");
}
*/
$language_id = 7;
$gamecafe     =$json_data['gamecafe'];
$gameterrain =$json_data['gameterrain'];
$kidszone =$json_data['kidszone'];
$mobilecafe =$json_data['mobilecafe'];
$beyondhealth =$json_data['beyondhealth'];

$mc  = mysqli_connect('localhost','laravel','laravel');
mysqli_select_db($mc,'cms');

$contents_query = "select * from contents";
$result = mysqli_query($mc,$contents_query);

while($row = mysqli_fetch_array($result)){
	$id = $row['id'];
	$name = $row['name'];
	$name_translation = isset($gamecafe[$name]) ?  $gamecafe[$name] : (isset($gameterrain[$name]) ?  $gameterrain[$name] :  (isset($kidszone[$name]) ?  $kidszone[$name] : (isset($mobilecafe[$name]) ?  $mobilecafe[$name]  : (isset($beyondhealth[$name]) ?  $beyondhealth[$name]  :''))));
	if(!empty($name_translation)){
		$insert_query = "insert into language_contents (content_id,language_id,name) values($id,$language_id,'".mysqli_real_escape_string($mc,$name_translation)."')";
		mysqli_query($mc,$insert_query);
		echo "Done for Content id $id  ".print(mysqli_error($mc))."\n"; 
	}else{
		echo "Skipped for Content   $id ................. \n";
	}
}


$contents_query = "select * from categories";
$result = mysqli_query($mc,$contents_query);

while($row = mysqli_fetch_array($result)){
	$id = $row['id'];
	$name = $row['name'];
	$name_translation = isset($gamecafe[$name]) ?  $gamecafe[$name] : (isset($gameterrain[$name]) ?  $gameterrain[$name] :  (isset($kidszone[$name]) ?  $kidszone[$name] : (isset($mobilecafe[$name]) ?  $mobilecafe[$name]  : (isset($beyondhealth[$name]) ?  $beyondhealth[$name]  :''))));
	if(!empty($name_translation)){
		$insert_query = "insert into category_language (category_id,language_id,name) values($id,$language_id,'".mysqli_real_escape_string($mc,$name_translation)."')";
		mysqli_query($mc,$insert_query)  ;
		echo "Done for Category id $id  ".print(mysqli_error($mc))."\n"; 
	}else{
		echo "Skipped for Category   $id ................. \n";
	}
}




?>