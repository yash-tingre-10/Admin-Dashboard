<!DOCTYPE html>
<html>
<head>
    <title>Teacher Import</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h3 {
            margin-bottom: 10px;
        }

        div {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="file"] {
            margin-right: 10px;
        }

        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        #teachersList {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div>
        <h3>Import Teachers from Excel</h3>
        <input type="file" id="excelFile" accept=".xlsx, .xls">
        <button onclick="importTeachers()">Import Teachers</button>
    </div>

    <div id="teachersList">
        <!-- Display teachers here after import -->
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
    <script>
        function importTeachers() {
            const input = document.getElementById('excelFile');
            const file = input.files[0];
            if (!file) {
                alert('Please select an Excel file to import.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });

                const sheetName = workbook.SheetNames[0];
                const sheet = workbook.Sheets[sheetName];
                const csvData = XLSX.utils.sheet_to_csv(sheet);

                // Convert CSV data to Blob
                const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);

                // Send the CSV file to the server using FormData
                const formData = new FormData();
                formData.append('csvFile', blob, 'teachers.csv');

                fetch('upload_teachers.php', {
                    method: 'POST',
                    body: formData
                }).then(function(response) {
                    if (response.ok) {
                        alert('Teachers imported successfully.');
                        // After import, fetch and display teachers
                        fetchTeachers();
                    } else {
                        alert('Error importing teachers.');
                    }
                }).catch(function(error) {
                    console.error(error);
                    alert('Error importing teachers.');
                });
            };
            reader.readAsArrayBuffer(file);
        }

        function fetchTeachers() {
            fetch('get_teachers.php')
                .then(response => response.json())
                .then(data => {
                    const teachersList = document.getElementById('teachersList');
                    teachersList.innerHTML = ''; // Clear previous data

                    // Create teachers table
                    const table = document.createElement('table');

                    // Create table header row
                    const headerRow = document.createElement('tr');
                    const headers = ['Teacher Name', 'Username', 'Password'];
                    headers.forEach(headerText => {
                        const th = document.createElement('th');
                        th.textContent = headerText;
                        headerRow.appendChild(th);
                    });
                    table.appendChild(headerRow);

                    // Add teachers to the table
                    data.forEach(teacher => {
                        const tr = document.createElement('tr');
                        const tdName = document.createElement('td');
                        tdName.textContent = teacher.teacherName;
                        const tdUsername = document.createElement('td');
                        tdUsername.textContent = teacher.username;
                        const tdPassword = document.createElement('td');
                        tdPassword.textContent = teacher.password;
                        tr.appendChild(tdName);
                        tr.appendChild(tdUsername);
                        tr.appendChild(tdPassword);
                        table.appendChild(tr);
                    });

                    teachersList.appendChild(table);
                })
                .catch(error => {
                    console.error(error);
                    alert('Error fetching teachers.');
                });
        }

        // Fetch teachers after page loads
        fetchTeachers();
    </script>
</body>
</html>
