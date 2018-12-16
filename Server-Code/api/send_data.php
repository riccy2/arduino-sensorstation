<?php
/**
 * Created by PhpStorm.
 * User: Riccardo Oppermann
 * Date: 13.07.2018
 * Time: 17:07
 */


date_default_timezone_set("Europe/Berlin");

include 'config.php';

//Datenbankverbindung aufbauen
// -----------------------------------------------------------------------------------------------------------------
$connect =  mysqli_connect($config["db_host"],$config["db_user"],$config["db_passwd"],$config["db_name"]);
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$names = mysqli_set_charset($connect, 'utf8');
session_start();
// -----------------------------------------------------------------------------------------------------------------


if(isset($_POST['action']) && $_POST['action'] == 'update') {
    // Echtzeitverarbeitung der Werte der Station

    $response = [];

    $now = date("Y-m-d H:i:s");
    $temp = isset($_POST['temp']) ? mysqli_real_escape_string($connect, strip_tags($_POST['temp'])) : '';
    $pres = isset($_POST['pres']) ? mysqli_real_escape_string($connect, strip_tags($_POST['pres'])) : '';
    $humi = isset($_POST['humi']) ? mysqli_real_escape_string($connect, strip_tags($_POST['humi'])) : '';
    $token = isset($_POST['token']) ? mysqli_real_escape_string($connect, strip_tags($_POST['token'])) : '';

    $queryStation = "SELECT * FROM stations WHERE identifier = '$token' LIMIT 1";
    $resultStation = mysqli_query($connect, $queryStation);
    $stationdata = mysqli_fetch_array($resultStation);
    $stations = [];
    if(@mysqli_num_rows($resultStation) != 1) {
        $response['status'] = "error_token";
    } else {
        // Messwerte in Datenbank schreiben
        $queryInsert = "INSERT INTO readings (stationid, `time`, temperature, pressure, humidity) 
                        VALUES ((SELECT id FROM stations WHERE identifier='$token'),'$now','$temp','$pres', '$humi')";
        if (mysqli_query($connect, $queryInsert)) {
            $response['status'] = "success";
        } else {
            $response['status'] = "error_mysql";
        }

        $response['stationname'] = $stationdata['name'];
        $response['intervall'] = $stationdata['intervall'];
        $response['timestamp'] = time();

    }


    echo json_encode($response);

}

else if(isset($_POST['action']) && $_POST['action'] == 'send_cache') {
    // Verarbeitung von zwischengespeicherten Werten der Station nach wiederhergestellter Internetverbindung

    $response = [];

    $line = isset($_POST['line']) ? mysqli_real_escape_string($connect, strip_tags($_POST['line'])) : '';
    $linedata = explode(";", $line);

    $timestamp = $linedata[0];
    $temp = $linedata[1];
    $pres = $linedata[2];
    $humi = $linedata[3];
    $token = isset($_POST['token']) ? mysqli_real_escape_string($connect, strip_tags($_POST['token'])) : '';

    $queryStation = "SELECT * FROM stations WHERE identifier = '$token' LIMIT 1";
    $resultStation = mysqli_query($connect, $queryStation);
    $stationdata = mysqli_fetch_array($resultStation);
    $stations = [];
    if(@mysqli_num_rows($resultStation) != 1) {
        $response['status'] = "error_token";
    } else {
        // log into database
        if (isset($token) && isset($timestamp) && isset($temp) && isset($pres) && isset($humi)) {
            if (mysqli_query($connect, "INSERT INTO readings (identifier, `time`, temperature, pressure, humidity) 
				VALUES ((SELECT id FROM stations WHERE identifier='$token'),'$timestamp','$temp','$pres', '$humi')")) {
                $response['status'] = "success";
            } else {
                $response['status'] = "error_mysql";
            }
        } else {
            $response['status'] = "error_line";
        }


        $response['stationname'] = $stationdata['name'];
        $response['intervall'] = $stationdata['intervall'];
        $response['timestamp'] = time();

    }

    echo json_encode($response);
}