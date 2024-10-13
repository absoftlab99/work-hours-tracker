function sendReportData(reportData) {
    console.log("Sending report data to server..."); // Debugging line
    console.log("Data to send:", reportData); // Debugging line
    
    fetch("save_report.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(reportData),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("Success:", data);
        // Optionally, display a success message to the user
    })
    .catch(error => {
        console.error("Error:", error);
    });
}
