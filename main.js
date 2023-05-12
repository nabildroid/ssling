const scanForm = document.getElementById("scanForm");
const loadingIndicator = document.getElementById("loadingIndicator");
const resultContainer = document.getElementById("resultContainer");
const resultTitle = document.getElementById("resultTitle");
const resultTable = document.getElementById("resultTable");
const resultRows = document.getElementById("resultRows");
const sslExpirationContainer = document.getElementById(
  "sslExpirationContainer"
);
const sslDomain = document.getElementById("sslDomain");
const sslExpirationDate = document.getElementById("sslExpirationDate");
const calendarLink = document.getElementById("calendarLink");

scanForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const target = document.getElementById("target").value;
  const scanType = document.querySelector(
    'input[name="scanType"]:checked'
  ).value;

  showLoadingIndicator();

  try {
    const response = await fetch("./api.php", {
      method: "POST",
      body: JSON.stringify({ target, scanType }),
      headers: {
        "Content-Type": "application/json",
      },
    });

    const data = await response.json();

    if (scanType === "port") {
      displayPortScanResult(data);
    } else if (scanType === "ssl") {
      displaySSLScanResult(data);
    }
  } catch (error) {
    console.error(error);
  }

  hideLoadingIndicator();
});

function showLoadingIndicator() {
  loadingIndicator.classList.remove("hidden");
  resultContainer.classList.add("hidden");
}

function hideLoadingIndicator() {
  loadingIndicator.classList.add("hidden");
}

function displayPortScanResult(data) {
  resultTitle.textContent = "Port Scan Result";

  // Clear previous result rows
  resultRows.innerHTML = "";

  if (data.length === 0) {
    resultRows.innerHTML =
      '<tr><td colspan="2" class="text-center">No open ports found</td></tr>';
  } else {
    data.forEach((row) => {
      const newRow = document.createElement("tr");
      const portCell = document.createElement("td");
      portCell.textContent = row.port;
      const statusCell = document.createElement("td");
      statusCell.textContent = row.status;

      newRow.appendChild(portCell);
      newRow.appendChild(statusCell);
      resultRows.appendChild(newRow);
    });
  }

  resultContainer.classList.remove("hidden");
}

function displaySSLScanResult(data) {
  resultTitle.textContent = "SSL Scan Result";

  resultRows.innerHTML = "";

  sslDomain.textContent = data.domain;
  sslExpirationDate.textContent = data.expirationDate;

  calendarLink.value = `your_calendar_api_url?date=${encodeURIComponent(
    data.expirationDate
  )}`;

  sslExpirationContainer.classList.remove("hidden");
  resultContainer.classList.remove("hidden");
}
