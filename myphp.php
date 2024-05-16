<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management System</title>
    <style>
        .login-card {
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            overflow: hidden;
            width: 400px; 
            margin: 0 auto; 
            padding: 20px; 
        }

        .login-card-description {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-btn:hover {
            background-color: #0056b3;
        }
        .container{
            display: block;
            width: 400px;
            margin: auto;
        }
        .container{
        display: block;
        margin: auto;
        width: 400px;
        }
        .container2{
            display: flex;
            margin-left: 20px;
            margin-right: 30px;
            width: 400px;
        }
        h1{
            text-align: center;
        }
        label{
            display: block;
            text-align: center;
            width: 400px;
        }
    </style>
</head>
<body onload="loadStudentData()">
    <div id="teacher-section">
        <img src="download.png" width="200px">
        <h1>Teacher Dashboard</h1>
        <form id="teacher-form">
            <label for="email">Email:</label>
            <div class="container2"><input type="email" id="email" name="email" class="form-control" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <br></div>
            <div class="container"><button type="button" class="login-btn" onclick="generateCode()">Generate Code</button>
        </form>
        <div id="code-display"></div>

        <button id="toggle-list-button" class="login-btn" onclick="toggleAttendanceList()">Show Attendance List</button></div>

        <div id="attendance-list" style="display: none;">
            <h2>Attendance List</h2>
            <ul id="student-list"></ul>
        </div>
    </div>

    <div id="student-section" style="display: none;">
        <h1>Student Attendance</h1>
        <form id="student-form">
            <label for="code">Enter Attendance Code:</label>
            <input type="text" id="code" name="code" class="form-control" required>
            <button type="button" class="login-btn" onclick="showAttendanceForm()">Enter Code</button>
            <br>
            <div id="attendance-fields" style="display: none;">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
                <br>
                <label for="surname">Surname:</label>
                <input type="text" id="surname" name="surname" class="form-control" required>
                <br>
                <label>
                    <input type="radio" name="status" value="Present" required> Present
                </label>
                <br>
                <button type="submit" class="login-btn" id="attendance-submit" disabled>Submit</button>
            </div>
        </form>
    </div>

    <script>
        let students = JSON.parse(localStorage.getItem('students')) || [];

        function generateCode() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (email === 'prof@example.com' && password === 'password') {
                const code = Math.floor(1000 + Math.random() * 9000);
                document.getElementById('code-display').innerText = "Your attendance code: " + code;
                sessionStorage.setItem('attendanceCode', code);
            } else {
                alert("Incorrect email or password. Please try again.");
            }
        }

        function showAttendanceForm() {
            const enteredCode = document.getElementById('code').value;
            const storedCode = sessionStorage.getItem('attendanceCode');

            if (enteredCode === storedCode) {
                document.getElementById('attendance-fields').style.display = 'block';
                document.getElementById('code').setAttribute('disabled', 'disabled');
                document.querySelector('button[onclick="showAttendanceForm()"]').setAttribute('disabled', 'disabled');
                document.getElementById('attendance-submit').removeAttribute('disabled');
            } else {
                alert("Incorrect code. Please try again.");
            }
        }

        document.getElementById('student-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const surname = document.getElementById('surname').value;
            const status = document.querySelector('input[name="status"]:checked').value;
            const timestamp = new Date().getTime();

            students.push({ name, surname, status, timestamp });
            localStorage.setItem('students', JSON.stringify(students));
            updateStudentList();

            document.getElementById('student-form').reset();
            document.getElementById('attendance-fields').style.display = 'none';
            document.getElementById('code').removeAttribute('disabled');
            document.querySelector('button[onclick="showAttendanceForm()"]').removeAttribute('disabled');
            alert("Attendance marked successfully!");
        });

        function updateStudentList() {
            const studentList = document.getElementById('student-list');
            studentList.innerHTML = '';

            const now = new Date().getTime();
            students = students.filter(student => now - student.timestamp < 86400000);

            students.forEach(student => {
                const date = new Date(student.timestamp);
                const listItem = document.createElement('li');
                listItem.innerText = `${student.name} ${student.surname} - ${student.status} - ${date.toLocaleString()}`;
                studentList.appendChild(listItem);
            });

            localStorage.setItem('students', JSON.stringify(students));
        }

        function loadStudentData() {
            updateStudentList();
        }

        function toggleAttendanceList() {
            const attendanceList = document.getElementById('attendance-list');
            const button = document.getElementById('toggle-list-button');
            if (attendanceList.style.display === 'none') {
                attendanceList.style.display = 'block';
                button.innerText = 'Hide Attendance List';
            } else {
                attendanceList.style.display = 'none';
                button.innerText = 'Show Attendance List';
            }
        }

        function toggleView(view) {
            if (view === 'teacher') {
                document.getElementById('teacher-section').style.display = 'block';
                document.getElementById('student-section').style.display = 'none';
            } else if (view === 'student') {
                document.getElementById('teacher-section').style.display = 'none';
                document.getElementById('student-section').style.display = 'block';
            }
        }
    </script>

    <div class="container"><button class="login-btn" onclick="toggleView('teacher')">Teacher View</button>
    <button class="login-btn" onclick="toggleView('student')">Student View</button></div>
</body>
</html>
