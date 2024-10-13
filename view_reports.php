<?php
// Database connection details
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

// Function to format seconds into HH:MM:SS
function formatTime($seconds)
{
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $sec = $seconds % 60; // Get the remaining seconds
    return sprintf("%02d:%02d:%02d", $hours, $minutes, $sec); // Format as HH:MM:SS
}

// Handle CSV export
if (isset($_POST['export_csv'])) {
    $sql = "SELECT * FROM reports"; // Adjust to your actual table name
    $result = $conn->query($sql);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="reports.csv"');

    $output = fopen('php://output', 'w');

    fputcsv($output, ['Start Time', 'End Time', 'Total Work Time', 'Total Break Time', 'Work Sessions', 'Break Sessions']);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['start_time'],
            $row['end_time'],
            formatTime($row['total_work_time']),
            formatTime($row['total_break_time']),
            json_encode($row['work_sessions']),
            json_encode($row['break_sessions']),
        ]);
    }

    fclose($output);
    exit();
}

// Fetch reports from the database
$sql = "SELECT * FROM reports"; // Adjust to your actual table name
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>

<body id="full_table_report" class="bg-gray-100 p-5">
    <div class="container mx-auto">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold mb-5">Reports</h1>
            <div class="flex gap-2">
                <a href="/clock" id="" class="bg-pink-700 text-white px-4 py-2 rounded"><i class="ti ti-chevron-left"></i> Back</a>
                <form method="post">
                    <button type="submit" name="export_csv" class="bg-blue-500 text-white px-4 py-2 rounded">Export to CSV</button>
                </form>
            </div>
        </div>
        <table class="min-w-full bg-white border border-gray-300 shadow-lg mb-5 text-center">
            <thead class="bg-yellow-600 text-white">
                <tr>
                    <th class="py-2 px-4 border-b">Start Time</th>
                    <th class="py-2 px-4 border-b">End Time</th>
                    <th class="py-2 px-4 border-b">Total Work Time</th>
                    <th class="py-2 px-4 border-b">Total Break Time</th>
                    <th class="py-2 px-4 border-b">Work Sessions</th>
                    <th class="py-2 px-4 border-b">Break Sessions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['start_time']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row['end_time']); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars(formatTime($row['total_work_time'])); ?></td>
                            <td class="py-2 px-4 border-b"><?php echo htmlspecialchars(formatTime($row['total_break_time'])); ?></td>
                            <td class="py-2 px-4 border-b">
                                <div class="">
                                    <?php
                                    $workSessions = json_decode($row['work_sessions'], true);
                                    if (is_array($workSessions) && !empty($workSessions)): ?>
                                        <?php foreach ($workSessions as $session): ?>
                                            <p>
                                                <?php
                                                // Extract and display only the time part
                                                if (isset($session['start']) && isset($session['end'])) {
                                                    $startTime = date("h:i A", strtotime($session['start'])); // Extract time in 12-hour format
                                                    $endTime = date("h:i A", strtotime($session['end'])); // Extract time in 12-hour format
                                                    echo "Start: " . htmlspecialchars($startTime) . " - End: " . htmlspecialchars($endTime);
                                                } else {
                                                    echo "Invalid work session data";
                                                }
                                                ?>
                                            </p>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-red-700">No work sessions found</p>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-2 px-4 border-b">
                                <div class="">
                                    <?php
                                    $breakSessions = json_decode($row['break_sessions'], true);
                                    if (is_array($breakSessions) && !empty($breakSessions)): ?>
                                        <?php foreach ($breakSessions as $session): ?>
                                            <p>
                                                <?php
                                                // Extract and display only the time part for break sessions
                                                if (isset($session['start']) && isset($session['end'])) {
                                                    $startTime = date("h:i A", strtotime($session['start'])); // Extract time in 12-hour format
                                                    $endTime = date("h:i A", strtotime($session['end'])); // Extract time in 12-hour format
                                                    echo "Start: " . htmlspecialchars($startTime) . " - End: " . htmlspecialchars($endTime);
                                                } else {
                                                    echo "Invalid break session data";
                                                }
                                                ?>
                                            </p>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-red-500">No break sessions found</p>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="py-2 px-4 border-b text-center">No reports found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>