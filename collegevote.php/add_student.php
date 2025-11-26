<?php
include("../dbconnect.php");
session_start();
if(!isset($_SESSION['staff'])) {
    header("location:../index.php");
    exit();
}

$staff_id = $_SESSION['staff'];
$query = "SELECT * FROM staff WHERE register_number = '$staff_id'";
$result = mysqli_query($conn, $query);
$staff_data = mysqli_fetch_assoc($result);

if(isset($_POST['submit'])) {
    extract($_POST);
    
    // Calculate admission year (current year)
    $admission_year = date('Y');
    
    $check_query = "SELECT * FROM students WHERE register_number = '$register_number'";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Student with this register number already exists!');</script>";
    } else {
        $query = "INSERT INTO students (name, register_number, department, year, admission_year) 
                  VALUES ('$name', '$register_number', '$department', '$year', '$admission_year')";
        
        if(mysqli_query($conn, $query)) {
            echo "<script>alert('Student added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding student.');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #2c3e50;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
        }
        .sidebar a:hover {
            background-color: #34495e;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <h3 class="p-3">Staff Panel</h3>
                <a href="../../valluvar/material and question/staff.php/staffhome.php">Dashboard</a>
                <a href="../../valluvar/material and question/staff.php/add_student.php">Add Student</a>
                <a href="../../valluvar/material and question/staff.php/manage_students.php">Manage Students</a>
                <a href="../../valluvar/material and question/staff.php/add_material.php">Add Material</a>
                <a href="../../valluvar/material and question/staff.php/add_question.php">Add Question Paper</a>
                <a href="../../valluvar/material and question/staff.php/view_materials.php">View Materials</a>
                <a href="../../valluvar/material and question/staff.php/view_questions.php">View Question Papers</a>
                <a href="../../valluvar/material and question/logout.php">Logout</a>
            </div>
            <div class="col-md-10 content">
                <h2>Add New Student</h2>
                <div class="card mt-4">
                    <div class="card-body">
                        <form method="post" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label class="form-label">Student Name</label>
                                <input type="text" class="form-control" name="name" required>
                                <div class="invalid-feedback">
                                    Please enter student name.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Register Number</label>
                                <input type="text" class="form-control" name="register_number" required>
                                <div class="invalid-feedback">
                                    Please enter register number.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <select class="form-select" name="department" required>
                                    <option value="">Choose...</option>
                                    <option value="bsc">BSc</option>
                                    <option value="bca">BCA</option>
                                    <option value="ai">AI</option>
                                    <option value="msc">MSc</option>
                                    <option value="mca">MCA</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a department.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Year</label>
                                <select class="form-select" name="year" required>
                                    <option value="">Choose...</option>
                                    <option value="1st year">1st Year</option>
                                    <option value="2nd year">2nd Year</option>
                                    <option value="3rd year">3rd Year</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a year.
                                </div>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Add Student</button>
                        </form>
                    </div>
                </div>

                <!-- Recently Added Students -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Recently Added Students</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Register Number</th>
                                    <th>Department</th>
                                    <th>Year</th>
                                    <th>Admission Year</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recent_query = "SELECT * FROM students WHERE department = '{$staff_data['department']}' 
                                               ORDER BY id DESC LIMIT 5";
                                $recent_result = mysqli_query($conn, $recent_query);
                                while($row = mysqli_fetch_assoc($recent_result)) {
                                    echo "<tr>";
                                    echo "<td>{$row['name']}</td>";
                                    echo "<td>{$row['register_number']}</td>";
                                    echo "<td>".strtoupper($row['department'])."</td>";
                                    echo "<td>{$row['year']}</td>";
                                    echo "<td>{$row['admission_year']}</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
						
						     </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>