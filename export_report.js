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
    const reportContainer = document.getElementById('full_table_report');
    html2canvas(reportContainer).then(canvas => {
        const link = document.createElement('a');
        link.href = canvas.toDataURL();
        link.download = 'time_report.png';
        link.click();
    });
}