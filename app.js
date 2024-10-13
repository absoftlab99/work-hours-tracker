let startTime, endTime;
let workIntervals = [];
let breakIntervals = [];
let totalWorkTime = 0;
let totalBreakTime = 0;
let isWorking = false;
let isPaused = false;
let interval;
let pauseStartTime;

document.getElementById('startButton').addEventListener('click', startWork);
document.getElementById('pauseButton').addEventListener('click', pauseWork);
document.getElementById('resumeButton').addEventListener('click', resumeWork);
document.getElementById('stopButton').addEventListener('click', stopWork);
document.getElementById('exportCSV').addEventListener('click', exportToCSV);
document.getElementById('exportImage').addEventListener('click', exportToImage);

function startWork() {
    if (!isWorking && !isPaused) {
        isWorking = true;
        startTime = new Date();
        workIntervals.push({ start: startTime });
        interval = setInterval(updateTimer, 1000);
    }
}

function pauseWork() {
    if (isWorking) {
        isWorking = false;
        isPaused = true;
        endTime = new Date();
        workIntervals[workIntervals.length - 1].end = endTime;
        clearInterval(interval);
        pauseStartTime = new Date();
        document.getElementById('resumeButton').classList.remove('hidden');
    }
}

function resumeWork() {
    if (isPaused) {
        isWorking = true;
        isPaused = false;
        const resumeTime = new Date();
        const breakDuration = (resumeTime - pauseStartTime) / 1000;
        breakIntervals.push({ start: pauseStartTime, end: resumeTime });
        interval = setInterval(updateTimer, 1000);
        document.getElementById('resumeButton').classList.add('hidden');
    }
}

function stopWork() {
    if (isWorking) {
        isWorking = false;
        endTime = new Date();
        workIntervals[workIntervals.length - 1].end = endTime;
        clearInterval(interval);
    }

    calculateTotalTimes();
    generateReport();

    // Prepare the report data
    const reportData = {
        startTime: workIntervals[workIntervals.length - 1].start, // Get the last session's start time
        endTime: endTime, // Get the end time of the last session
        workSessions: workIntervals.map(interval => ({
            start: interval.start,
            end: interval.end || new Date() // Use current time if end is not set
        })),
        breakSessions: breakIntervals.map(interval => ({
            start: interval.start,
            end: interval.end
        })),
        totalWorkTime: totalWorkTime, // Use the calculated total work time
        totalBreakTime: totalBreakTime // Use the calculated total break time
    };

    // Send the report data to the database
    sendReportData(reportData);
}


function updateTimer() {
    const now = new Date();
    const elapsed = Math.floor((now - startTime) / 1000);
    document.getElementById('timerDisplay').textContent = formatTime(elapsed);
}

function calculateTotalTimes() {
    totalWorkTime = 0;
    totalBreakTime = 0;

    workIntervals.forEach(interval => {
        const workTime = (new Date(interval.end) - new Date(interval.start)) / 1000;
        totalWorkTime += workTime;
    });

    breakIntervals.forEach(interval => {
        const breakTime = (new Date(interval.end) - new Date(interval.start)) / 1000;
        totalBreakTime += breakTime;
    });

    // Calculate total work time based on the new formula
    totalWorkTime = totalWorkTime - totalBreakTime;
}

function formatTime(seconds) {
    const h = Math.floor(seconds / 3600).toString().padStart(2, '0');
    const m = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
    const s = (seconds % 60).toString().padStart(2, '0');
    return `${h}:${m}:${s}`;
}

function generateReport() {
    const reportDate = new Date().toLocaleDateString();
    document.getElementById('reportDate').textContent = reportDate;

    // Clear previous session rows
    document.getElementById('workSessionContainer').innerHTML = '';
    document.getElementById('breakSessionContainer').innerHTML = '';

    // Populate work sessions
    workIntervals.forEach((session, index) => {
        const workRow = document.createElement('tr');
        workRow.innerHTML = `<td class="border border-gray-300 px-4 py-2">Session-${index + 1}</td>
                             <td class="border border-gray-300 px-4 py-2 flex gap-2">Start: <p class="font-bold">${formatTimeString(session.start)}</p>, End: <p class="font-bold">${formatTimeString(session.end)}</p></td>`;
        document.getElementById('workSessionContainer').appendChild(workRow);
    });

    // Populate break sessions
    breakIntervals.forEach((session, index) => {
        const breakRow = document.createElement('tr');
        breakRow.innerHTML = `<td class="border border-gray-300 px-4 py-2">Session-${index + 1}</td>
                             <td class="border border-gray-300 px-4 py-2 flex gap-2">Start: <p class="font-bold">${formatTimeString(session.start)}</p>, End: <p class="font-bold">${formatTimeString(session.end)}</p></td>`;
        document.getElementById('breakSessionContainer').appendChild(breakRow);
    });
    
    document.getElementById('totalWorkTime').textContent = `Total Work Time: ${Math.round(totalWorkTime)} seconds`;
    document.getElementById('totalBreakTime').textContent = `Total Break Time: ${Math.round(totalBreakTime)} seconds`;
}

function formatTimeString(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
}

function saveReportToDatabase() {
    // Logic to save report data to database
}

function exportToCSV() {
    const csvContent = `data:text/csv;charset=utf-8,`
        + `Date,Work Sessions,Break Sessions,Total Work Time,Total Break Time\n`
        + `${document.getElementById('reportDate').textContent},${JSON.stringify(workIntervals)},${JSON.stringify(breakIntervals)},${totalWorkTime},${totalBreakTime}`;
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', 'time_report.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function exportToImage() {
    const reportContainer = document.getElementById('full-container');
    html2canvas(reportContainer).then(canvas => {
        const link = document.createElement('a');
        link.href = canvas.toDataURL();
        link.download = 'time_report.png';
        link.click();
    });
}
