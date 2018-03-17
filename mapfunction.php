<?php 
error_reporting(0);

if(session_id()){}else{session_start();}

if(isset($_POST['action']) && !empty($_POST['action']))
{

	$action = $_POST['action'];
	switch ($action) {
		case 'addNewMarker':
		addNewMarker();
		break;


		case 'getAllMarkers':
		getAllMarkers();
		break;


	}
}


function getAllMarkers(){
	include 'connect.php';
	$markers = array();

	$markersInDb = $conn->query("SELECT
		`markerid`,
		`longitude`,
		`latitude` FROM tblmarkers ORDER BY markerid ASC");

	while($r = $markersInDb->fetch()){
		array_push($markers, array(
			"markerid" => $r['markerid'], 
			"longitude" => $r['longitude'], 
			"latitude" => $r['latitude']
		));
	}
	echo json_encode($markers);
}

function addNewMarker(){

	$time = time();
	$long =$_POST['longitude'];
	$lat = $_POST['latitude'];

	include 'connect.php';
	$stmt = $conn->prepare("INSERT INTO `tblmarkers`(`longitude`, `latitude`, `dateadded`) VALUES (:long, :lat, :da)");

	$stmt->bindParam(':long', $long);
	$stmt->bindParam(':lat', $lat);
	$stmt->bindParam(':da', $time);
	$stmt->execute();

	echo json_encode(array('longitude' => $long, 'latitude' => $lat ));
}


?>