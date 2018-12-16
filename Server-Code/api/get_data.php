<?php
/**
 * Created by PhpStorm.
 * User: Riccardo Oppermann
 * Date: 15.07.2018
 * Time: 13:27
 */



/**
 * linear regression function
 * Quelle: https://richardathome.wordpress.com/2006/01/25/a-php-linear-regression-function/
 * @param $x array x-coords
 * @param $y array y-coords
 * @returns array() m=>slope, b=>intercept
 */
function linear_regression($x, $y) {

    // calculate number points
    $n = count($x);

    // ensure both arrays of points are the same size
    if ($n != count($y)) {

        trigger_error("linear_regression(): Number of elements in coordinate arrays do not match.", E_USER_ERROR);

    }

    // calculate sums
    $x_sum = array_sum($x);
    $y_sum = array_sum($y);

    $xx_sum = 0;
    $xy_sum = 0;

    for($i = 0; $i < $n; $i++) {

        $xy_sum+=($x[$i]*$y[$i]);
        $xx_sum+=($x[$i]*$x[$i]);

    }

    // calculate slope
    $m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));

    // calculate intercept
    $b = ($y_sum - ($m * $x_sum)) / $n;

    // return result
    return array("m"=>$m, "b"=>$b);

}


ini_set('post_max_size', '512M');
ini_set('upload_max_filesize', '512M');
ini_set('max_input_vars ', '5000');
ini_set('display_errors', 1);

date_default_timezone_set("Europe/Berlin");

error_reporting(E_ALL);

include 'config.php';

//Datenbankverbindung aufbauen
// -----------------------------------------------------------------------------------------------------------------
$connect =  mysqli_connect($config["db_host"],$config["db_user"],$config["db_passwd"],$config["db_name"]); //Verbindung zur Datenbank herstellen
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$names = mysqli_set_charset($connect, 'utf8');
// -----------------------------------------------------------------------------------------------------------------



$query = "SELECT * FROM stations";
$result = mysqli_query($connect, $query);
$stations = [];
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    array_push($stations, $row);
}

$action = isset($_GET['action']) ? mysqli_real_escape_string($connect, $_GET['action']) : '';

switch ($action) {
    case 'get_now':

        if (!isset($_GET['date']) || $_GET['date'] == null || $_GET['date'] == 'null') {
            $now = date('Y-m-d');
        } else {
            $now = date('Y-m-d', strtotime($_GET['date']));
        }
        $now = date('Y-m-d', strtotime($now . ' +1 day')); // um alles bis 23:59 abzudecken
        $one_day_ago = date('Y-m-d', strtotime($now . ' -1 day'));
        $one_month_ago = date('Y-m-d', strtotime($now . ' -1 month'));
        $one_year_ago = date('Y-m-d', strtotime($now . ' -1 year'));

        $response = [];

        foreach ($stations as $station) {
            $station_data = [];
            $station_data['station'] = $station;

            $st_id = $station['id'];

            $result_day = mysqli_query($connect,"SELECT * FROM readings WHERE (`time` BETWEEN '$one_day_ago' AND '$now') AND stationid = '$st_id'");
            $readings_day = [];
            while ($row_day = mysqli_fetch_array($result_day, MYSQLI_ASSOC)) {
                array_push($readings_day, $row_day);
            }

            $query_month = "SELECT ROUND(AVG(temperature), 2) as temperature,
                 ROUND(AVG(pressure), 2) as pressure,
                 ROUND(AVG(humidity), 2) as humidity, 
                 CONCAT(DATE(`time`),' ', DATE_FORMAT(`time`, '%H'), ':00:00') as `time`
             FROM (SELECT * FROM readings WHERE (`time` BETWEEN '$one_month_ago' AND '$now') AND stationid = '$st_id') as a 
             GROUP BY DATE(`time`), DATE_FORMAT(`time`, '%H') 
             ORDER BY `time` ASC LIMIT 1440";
            $result_month = mysqli_query($connect, $query_month);
            $readings_month = [];
            while ($row_month = mysqli_fetch_array($result_month, MYSQLI_ASSOC)) {
                array_push($readings_month, $row_month);
            }

            $query_year = "SELECT ROUND(AVG(temperature), 2) as temperature,
                 ROUND(AVG(pressure), 2) as pressure,
                 ROUND(AVG(humidity), 2) as humidity, 
                 CONCAT(DATE(`time`),' ', '00:00:00') as `time`
             FROM (SELECT * FROM readings WHERE (`time` BETWEEN '$one_year_ago' AND '$now') AND stationid = '$st_id') as a 
             GROUP BY DATE(`time`) ORDER BY `time` ASC LIMIT 720";
            $result_year = mysqli_query($connect, $query_year);
            $readings_year = [];
            while ($row_year = mysqli_fetch_array($result_year, MYSQLI_ASSOC)) {
                array_push($readings_year, $row_year);
            }

            $station_data['day'] = $readings_day;
            $station_data['month'] = $readings_month;
            $station_data['year'] = $readings_year;

            array_push($response, $station_data);

        }

        echo json_encode($response);

        break;

    case 'get_status':
        $one_hour_ago = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $now = date('Y-m-d H:i:s');

        $response = [];

        foreach ($stations as $station) {
            $station_data = [];
            $station_data['station'] = $station;

            $st_id = $station['id'];

            $query_latest = "SELECT ROUND(temperature, 2) as temperature,
                                 ROUND(pressure, 2) as pressure,
                                 ROUND(humidity, 2) as humidity,
                                 `time`
                             FROM readings
                             WHERE stationid = '$st_id'
                             ORDER BY `time` DESC LIMIT 1";

            $query_latest_hour = "SELECT ROUND(temperature, 2) as temperature,
                                 ROUND(pressure, 2) as pressure,
                                 ROUND(humidity, 2) as humidity,
                                 UNIX_TIMESTAMP(`time`) as time_unix
                             FROM readings 
                             WHERE (`time` BETWEEN '$one_hour_ago' AND '$now') AND stationid = '$st_id'
                             ORDER BY `time` DESC";

            $result = mysqli_query($connect, $query_latest);
            $result_hour = mysqli_query($connect, $query_latest_hour);

            if (!$result) {
                printf("Error: %s\n", mysqli_error($connect));
                exit();
            }
            if (!$result_hour) {
                printf("Error: %s\n", mysqli_error($connect));
                exit();
            }


            $jsonData = [];

            $latest = mysqli_fetch_array($result);
            $latest_hour = [];

            while ($row = mysqli_fetch_array($result_hour)) {
                $latest_hour[] = $row;
            }

            $time_hour = array_column($latest_hour, 'time_unix');
            $temp_hour = array_column($latest_hour, 'temperature');
            $press_hour = array_column($latest_hour, 'pressure');
            $humid_hour = array_column($latest_hour, 'humidity');


            $latest_temp = $latest['temperature'];
            $latest_press = $latest['pressure'];
            $latest_humid = $latest['humidity'];
            $latest_time = $latest['time'];

            if (sizeof($time_hour) > 0 && sizeof($temp_hour) > 0) {
                $trend_1h_temp = linear_regression($time_hour, $temp_hour)['m'] > 0 ? 'up' : 'down';
            } else {
                $trend_1h_temp = '-';
            }

            if (sizeof($time_hour) > 0 && sizeof($press_hour) > 0) {
                $trend_1h_press = linear_regression($time_hour, $press_hour)['m'] > 0 ? 'up' : 'down';
            } else {
                $trend_1h_press = '-';
            }

            if (sizeof($time_hour) > 0 && sizeof($press_hour) > 0) {
                $trend_1h_humid = linear_regression($time_hour, $humid_hour)['m'] > 0 ? 'up' : 'down';
            } else {
                $trend_1h_humid = '-';
            }


            $station_data = [];
            $station_data['station'] = $station;
            $station_data['trend'] = [
                "temp" => $trend_1h_temp,
                "press" => $trend_1h_press,
                "humid" => $trend_1h_humid
            ];
            $station_data['latest'] = [
                "time" => $latest_time,
                "temp" => $latest_temp,
                "press" => $latest_press,
                "humid" => $latest_humid,
            ];

            array_push($response, $station_data);

        }

        echo json_encode($response, JSON_NUMERIC_CHECK );
        break;

    default:
        echo '{"message": "action not found"}';
        break;
}