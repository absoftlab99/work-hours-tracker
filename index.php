<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time Tracker</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sixtyfour+Convergence&display=swap" rel="stylesheet">


    <script src="app.js" defer></script>
    <script src="send_report.js" defer></script>
</head>
<body class="bg-gray-100 p-14 ubuntu-regular">
    <div id="full-container" class="max-w-4xl mx-auto bg-white p-10 rounded-lg shadow-lg">
        <h1 class="text-5xl font-bold text-center">Work Hours Tracker</h1>
        <div id="timerDisplay" class="text-4xl text-center font-bold my-4 sixtyfour-convergence-clockfont my-10">00:00:00</div>
        <div class="flex justify-around my-10">
            <button id="startButton" class="bg-blue-600 text-white px-4 py-2 rounded"><i class="ti ti-player-play"></i> Start</button>
            <button id="pauseButton" class="bg-yellow-600 text-white px-4 py-2 rounded"><i class="ti ti-clock-pause"></i> Pause</button>
            <button id="resumeButton" class="bg-green-600 text-white px-4 py-2 rounded hidden"><i class="ti ti-clock-check"></i> Resume</button>
            <button id="stopButton" class="bg-red-600 text-white px-4 py-2 rounded"><i class="ti ti-stopwatch"></i> Stop</button>
        </div>

        <div class="mt-5" id="reportContainer">
            <h2 class="text-lg font-semibold text-center">Work Hours Report</h2>
            <table class="min-w-full border-collapse border border-gray-300 mt-4">
                <thead>
                    <tr class="bg-yellow-600 text-white">
                        <th class="border border-gray-300 px-4 py-2 w-1/3">Description</th>
                        <th class="border border-gray-300 px-4 py-2 w-2/3">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-bold"><i class="ti ti-calendar-event"></i> Date</td>
                        <td class="border border-gray-300 px-4 py-2" id="reportDate"></td>
                    </tr>
                    <tr id="workSessionRow">
                        <td class="border border-gray-300 px-4 py-2 text-green-700 font-bold"><i class="ti ti-clock-hour-3"></i> Work Sessions</td>
                        <td class="border border-gray-300 px-4 py-2 text-green-700" id="workSessionContainer"></td>
                    </tr>
                    <tr id="breakSessionRow">
                        <td class="border border-gray-300 px-4 py-2 text-red-700 font-bold"><i class="ti ti-clock-hour-3"></i> Break Sessions</td>
                        <td class="border border-gray-300 px-4 py-2 text-red-700" id="breakSessionContainer"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 text-green-700 font-bold"><i class="ti ti-clock-hour-3"></i> Total Work Time</td>
                        <td class="border border-gray-300 px-4 py-2 text-green-700" id="totalWorkTime"></td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 text-red-700 font-bold"><i class="ti ti-clock-hour-3"></i> Total Break Time</td>
                        <td class="border border-gray-300 px-4 py-2 text-red-700" id="totalBreakTime"></td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-4 flex justify-between">
                <button id="exportCSV" class="bg-green-600 text-white px-4 py-2 rounded"><i class="ti ti-file-type-csv"></i> Export CSV</button>
                <button id="exportImage" class="bg-purple-600 text-white px-4 py-2 rounded"><i class="ti ti-photo-down"></i> Export Image</button>
                <button id="submitReportButton" class="bg-blue-700 text-white px-4 py-2 rounded"><i class="ti ti-send"></i> Submit Report</button>
            </div>
        </div>
    </div>
</body>
</html>
