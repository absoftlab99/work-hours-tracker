<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = getenv('DB_SERVER') ?: "localhost";
$username = getenv('DB_USERNAME') ?: "root";
$password = getenv('DB_PASSWORD') ?: "";
$dbname = getenv('DB_NAME') ?: "time_tracker";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON data from POST request
$data = json_decode(file_get_contents("php://input"), true);

// Debugging: log the incoming data
file_put_contents('php://stderr', print_r($data, TRUE));

$startTime = $data['startTime'];
$endTime = $data['endTime'];
$workSessions = json_encode($data['workSessions']);
$breakSessions = json_encode($data['breakSessions']);
$totalWorkTime = $data['totalWorkTime'];
$totalBreakTime = $data['totalBreakTime'];
$reportDate = date('Y-m-d'); // Get current date

// Insert into database
$sql = "INSERT INTO reports (start_time, end_time, work_sessions, break_sessions, total_work_time, total_break_time, report_date)
        VALUES ('$startTime', '$endTime', '$workSessions', '$breakSessions', '$totalWorkTime', '$totalBreakTime', '$reportDate')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Report saved successfully"]);
} else {
    echo json_encode(["message" => "Error: " . $conn->error]);
}

$conn->close();
?>
