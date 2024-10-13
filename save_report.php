<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "time_tracker";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON data from POST request
$data = json_decode(file_get_contents("php://input"), true);

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
    echo json_encode(["message" => "Error: " . $sql . "<br>" . $conn->error]);
}

$conn->close();
?>
