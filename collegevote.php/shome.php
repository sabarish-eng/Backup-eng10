<?php
include("dbconnect.php");
session_start();

// Process form submission
if(isset($_POST['btn'])) {
    // Sanitize inputs
    function sanitize($data) {
        global $conn;
        return mysqli_real_escape_string($conn, trim($data));
    }

    // Collect all form data
    $name = sanitize($_POST['name']);
    $regno = sanitize($_POST['regno']);
    $age = (int)$_POST['age'];
    $dob = sanitize($_POST['dob']);
    $nationality = sanitize($_POST['nation']);
    $religion = sanitize($_POST['religion']);
    $community = sanitize($_POST['community']);
    $caste = sanitize($_POST['caste']);
    $marital_status = sanitize($_POST['status']);
    $father_name = sanitize($_POST['father']);
    $father_occupation = sanitize($_POST['occupation']);
    $mother_name = sanitize($_POST['mother']);
    $mother_occupation = sanitize($_POST['occu']);
    $hostel_status = sanitize($_POST['status']); // Hostel/Day scholar
    $blood_group = sanitize($_POST['blood']);
    $govt_scholarship = sanitize($_POST['government']);
    $capsa_scholarship = sanitize($_POST['capsa']);
    $nri_status = sanitize($_POST['nri']);
    $aadhar = sanitize($_POST['aadhar']);
    $father_phone = sanitize($_POST['phone']);
    $mother_phone = sanitize($_POST['mo']);
    $student_phone = sanitize($_POST['st']);
    $permanent_address = sanitize($_POST['per']);
    $present_address = sanitize($_POST['pre']);
    $pincode = sanitize($_POST['pincode']);
    $email = sanitize($_POST['email']);
    $communication = sanitize($_POST['communication']);
    $physically_challenged = sanitize($_POST['status']); // Yes/No
    $board_type = sanitize($_POST['Me']); // CBSC/State Board
    $emis = sanitize($_POST['emis']);
    $academic_past = sanitize($_POST['aca']);
    $academic_present = sanitize($_POST['aca']);

    // Check for existing registration number
    $check_query = mysqli_query($conn, "SELECT * FROM student_profile WHERE reg_number = '$regno'");
    if(mysqli_num_rows($check_query) > 0) {
        echo "<script>alert('Registration number already exists!');</script>";
    } else {
        // Handle file uploads
        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $student_photo = '';
        $father_photo = '';
        $mother_photo = '';

        if(isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] == 0) {
            $student_photo = $upload_dir . 'student_' . time() . '_' . basename($_FILES['student_photo']['name']);
            move_uploaded_file($_FILES['student_photo']['tmp_name'], $student_photo);
        }
        if(isset($_FILES['father_photo']) && $_FILES['father_photo']['error'] == 0) {
            $father_photo = $upload_dir . 'father_' . time() . '_' . basename($_FILES['father_photo']['name']);
            move_uploaded_file($_FILES['father_photo']['tmp_name'], $father_photo);
        }
        if(isset($_FILES['mother_photo']) && $_FILES['mother_photo']['error'] == 0) {
            $mother_photo = $upload_dir . 'mother_' . time() . '_' . basename($_FILES['mother_photo']['name']);
            move_uploaded_file($_FILES['mother_photo']['tmp_name'], $mother_photo);
        }

        // Insert data into database
        $query = "INSERT INTO student_profile (
            name, reg_number, age, dob, nationality, religion, community, caste, 
            marital_status, father_name, father_occupation, mother_name, mother_occupation,
            hostel_status, blood_group, govt_scholarship, capsa_scholarship, nri_status,
            aadhar_number, father_phone, mother_phone, student_phone, permanent_address,
            present_address, pincode, email, communication_address, physically_challenged,
            board_type, emis_number, student_photo, father_photo, mother_photo,
            academic_excellence_past, academic_excellence_present
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssisssssssssssssssssssssssssssssss",
            $name, $regno, $age, $dob, $nationality, $religion, $community, $caste,
            $marital_status, $father_name, $father_occupation, $mother_name, $mother_occupation,
            $hostel_status, $blood_group, $govt_scholarship, $capsa_scholarship, $nri_status,
            $aadhar, $father_phone, $mother_phone, $student_phone, $permanent_address,
            $present_address, $pincode, $email, $communication, $physically_challenged,
            $board_type, $emis, $student_photo, $father_photo, $mother_photo,
            $academic_past, $academic_present
        );

        if(mysqli_stmt_execute($stmt)) {
            $student_id = mysqli_insert_id($conn);

            // Process semester marks
            foreach(['first', 'sec', 'thir', 'four'] as $sem_key => $sem) {
                if(isset($_POST[$sem])) {
                    foreach($_POST[$sem] as $mark) {
                        $mark_query = "INSERT INTO semester_marks (student_id, semester, marks) VALUES (?, ?, ?)";
                        $mark_stmt = mysqli_prepare($conn, $mark_query);
                        $semester_num = $sem_key + 1;
                        $mark_value = (int)$mark;
                        mysqli_stmt_bind_param($mark_stmt, "iii", $student_id, $semester_num, $mark_value);
                        mysqli_stmt_execute($mark_stmt);
                    }
                }
            }
            
            echo "<script>alert('Student profile created successfully!');window.location.href='view_profile.php?id=".$student_id."';</script>";
        } else {
            echo "<script>alert('Error creating student profile!');</script>";
        }
    }
}

// Get current UTC time
$current_time = gmdate('Y-m-d H:i:s');
$current_user = 'pamilafantazy';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cauvery college - Student Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0033CC;
            --secondary-color: #17a2b8;
            --success-color: #28a745;
            --warning-color: #ffc107;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: white !important;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .header-info {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 10px 20px;
            color: white;
        }

        .form-container {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin: 20px auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .section-title {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0,51,204,0.25);
        }

        .btn-submit {
            background: var(--primary-color);
            color: white;
            padding: 10px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .photo-upload {
            border: 2px dashed var(--primary-color);
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .photo-upload:hover {
            background: #f8f9fa;
        }

        .semester-card {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .semester-header {
            background: var(--primary-color);
            color: white;
            padding: 15px;
            border-radius: 10px 10px 0 0;
        }

        .required-field::after {
            content: "*";
            color: red;
            margin-left: 4px;
        }

        .marks-table {
            width: 100%;
            margin-top: 15px;
        }

        .marks-table th {
            background-color: #f8f9fa;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-university me-2"></i>
                Cauvery College
            </a>
            <div class="header-info ms-auto">
                <div class="d-flex flex-column">
                    <span><i class="far fa-clock me-2"></i><?php echo $current_time; ?> UTC</span>
                    <span><i class="far fa-user me-2"></i><?php echo htmlspecialchars($current_user); ?></span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container form-container">
        <h1 class="text-center mb-4">Student Profile</h1>
<!-- After the navbar, add this section for time and user display -->
<div class="container-fluid bg-light py-2 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="system-info">
                    <h5 class="text-primary mb-0">
                        <i class="fas fa-clock me-2"></i>
                        <span id="current-time"><?php echo $current_time; ?></span>
                    </h5>
                    <small class="text-muted">UTC Time</small>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="user-info">
                    <h5 class="text-success mb-0">
                        <i class="fas fa-user me-2"></i>
                        <?php echo htmlspecialchars($current_user); ?>
                    </h5>
                    <small class="text-muted">Current User</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Form Container -->
<div class="container">
    <form id="f1" name="f1" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
        <!-- Personal Information Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Name</label>
                            <input name="name" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Register Number</label>
                            <input name="regno" type="number" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label required-field">Age</label>
                            <input name="age" type="number" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label required-field">Date of Birth</label>
                            <input name="dob" type="date" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label required-field">Nationality</label>
                            <input name="nation" type="text" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Religious Information Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-pray me-2"></i>Religious Information</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label required-field">Religion</label>
                            <input name="religion" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label required-field">Community</label>
                            <input name="community" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label required-field">Caste</label>
                            <input name="caste" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label required-field">Marital Status</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="status" id="married" value="Married">
                                <label class="btn btn-outline-primary" for="married">Married</label>
                                <input type="radio" class="btn-check" name="status" id="unmarried" value="Unmarried">
                                <label class="btn btn-outline-primary" for="unmarried">Unmarried</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Family Information Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-users me-2"></i>Family Information</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Father's Name</label>
                            <input name="father" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Father's Occupation</label>
                            <input name="occupation" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Mother's Name</label>
                            <input name="mother" type="text" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Mother's Occupation</label>
                            <input name="occu" type="text" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Details Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>Additional Details</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Hostel Status -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Hostel/Day scholar</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="hostel_status" id="hostel" value="Hostel">
                                <label class="btn btn-outline-primary" for="hostel">Hostel</label>
                                <input type="radio" class="btn-check" name="hostel_status" id="day" value="Day scholar">
                                <label class="btn btn-outline-primary" for="day">Day Scholar</label>
                            </div>
                        </div>
                    </div>
                    <!-- Blood Group -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Blood Group</label>
                            <input name="blood" type="text" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
		
		
		<!-- Time and User Display -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0"><i class="fas fa-clock me-2"></i>System Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt fa-2x text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0">Current Date and Time (UTC)</h6>
                                <span id="current-time" class="h5 text-primary">
                                    <?php echo date('Y-m-d H:i:s'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-circle fa-2x text-success me-3"></i>
                            <div>
                                <h6 class="mb-0">Current User</h6>
                                <span class="h5 text-success"><?php echo htmlspecialchars($current_user); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scholarship Details Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-secondary text-white">
                <h4 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Scholarship Details</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Government Scholarship</label>
                            <input name="government" type="text" class="form-control">
                            <small class="text-muted">Enter government scholarship details if applicable</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Capsa/Other Scholarship</label>
                            <input name="capsa" type="text" class="form-control">
                            <small class="text-muted">Enter other scholarship details if applicable</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="fas fa-address-card me-2"></i>Contact Information</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Phone Numbers -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label required-field">Father's Phone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input name="phone" type="tel" class="form-control" pattern="[0-9]{10}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label required-field">Mother's Phone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input name="mo" type="tel" class="form-control" pattern="[0-9]{10}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label required-field">Student's Phone</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                <input name="st" type="tel" class="form-control" pattern="[0-9]{10}" required>
                            </div>
                        </div>
                    </div>
                    <!-- Email -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input name="email" type="email" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <!-- Aadhar -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Aadhar Number</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                <input name="aadhar" type="text" class="form-control" 
                                       pattern="[0-9]{4} [0-9]{4} [0-9]{4}"
                                       placeholder="xxxx xxxx xxxx" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information Card -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-home me-2"></i>Address Information</h4>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Permanent Address</label>
                            <textarea name="per" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Present Address</label>
                            <textarea name="pre" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Pincode</label>
                            <input name="pincode" type="number" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label required-field">Communication Address</label>
                            <select name="communication" class="form-select" required>
                                <option value="">Select Address Type</option>
                                <option value="Present Address">Present Address</option>
                                <option value="Permanent Address">Permanent Address</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
		
		
		<!-- System Info Bar -->
<div class="bg-dark text-white py-2 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <i class="fas fa-clock me-2"></i>
                    <span id="current-time">
                        <?php 
                            echo date('Y-m-d H:i:s'); 
                        ?>
                    </span>
                    <span class="ms-2">(UTC)</span>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="d-flex align-items-center justify-content-end">
                    <i class="fas fa-user me-2"></i>
                    <span><?php echo htmlspecialchars($current_user); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Upload Section -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-camera me-2"></i>Photo Upload</h4>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="photo-upload-container">
                    <label class="form-label required-field">Student Photo</label>
                    <div class="photo-preview mb-2" id="studentPhotoPreview">
                        <img src="placeholder.jpg" class="img-fluid d-none" alt="Student Photo">
                    </div>
                    <input type="file" class="form-control" name="student_photo" 
                           accept="image/*" required onchange="previewImage(this, 'studentPhotoPreview')">
                </div>
            </div>
            <div class="col-md-4">
                <div class="photo-upload-container">
                    <label class="form-label">Father's Photo</label>
                    <div class="photo-preview mb-2" id="fatherPhotoPreview">
                        <img src="placeholder.jpg" class="img-fluid d-none" alt="Father's Photo">
                    </div>
                    <input type="file" class="form-control" name="father_photo" 
                           accept="image/*" onchange="previewImage(this, 'fatherPhotoPreview')">
                </div>
            </div>
            <div class="col-md-4">
                <div class="photo-upload-container">
                    <label class="form-label">Mother's Photo</label>
                    <div class="photo-preview mb-2" id="motherPhotoPreview">
                        <img src="placeholder.jpg" class="img-fluid d-none" alt="Mother's Photo">
                    </div>
                    <input type="file" class="form-control" name="mother_photo" 
                           accept="image/*" onchange="previewImage(this, 'motherPhotoPreview')">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Semester Marks Section -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-info text-white">
        <h4 class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Semester Marks</h4>
    </div>
    <div class="card-body">
        <!-- Semester Tabs -->
        <ul class="nav nav-tabs" id="semesterTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="sem1-tab" data-bs-toggle="tab" href="#sem1">
                    <i class="fas fa-book me-1"></i>1st Semester
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sem2-tab" data-bs-toggle="tab" href="#sem2">
                    <i class="fas fa-book me-1"></i>2nd Semester
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sem3-tab" data-bs-toggle="tab" href="#sem3">
                    <i class="fas fa-book me-1"></i>3rd Semester
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="sem4-tab" data-bs-toggle="tab" href="#sem4">
                    <i class="fas fa-book me-1"></i>4th Semester
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="semesterTabContent">
            <!-- First Semester -->
            <div class="tab-pane fade show active" id="sem1">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Subject</th>
                                <th>Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Algorithm</td>
                                <td><input type="number" class="form-control" name="first[]" min="0" max="100"></td>
                            </tr>
                            <!-- Add other subjects -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Other semester tabs similar structure -->
        </div>
    </div>
</div>

<!-- Submit Button -->
<div class="text-center mb-5">
    <button type="submit" name="btn" class="btn btn-primary btn-lg px-5 py-3">
        <i class="fas fa-save me-2"></i>Submit Profile
    </button>
</div>

<!-- Add this before closing body tag -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Function to format date with leading zeros
function padZero(num) {
    return num.toString().padStart(2, '0');
}

// Function to update time with specified format
function updateTime() {
    const now = new Date();
    
    // Format: YYYY-MM-DD HH:MM:SS
    const formatted = now.getUTCFullYear() + '-' + 
                     padZero(now.getUTCMonth() + 1) + '-' + 
                     padZero(now.getUTCDate()) + ' ' + 
                     padZero(now.getUTCHours()) + ':' + 
                     padZero(now.getUTCMinutes()) + ':' + 
                     padZero(now.getUTCSeconds());

    // Update the display with formatted text
    document.getElementById('timeDisplay').innerHTML = 
        `Current Date and Time (UTC - YYYY-MM-DD HH:MM:SS formatted): ${formatted}<br>` +
        `Current User's Login: ${currentUser}`;
}



// Initialize Bootstrap tabs
document.addEventListener('DOMContentLoaded', function() {
    var triggerTabList = [].slice.call(document.querySelectorAll('#semesterTabs a'));
    triggerTabList.forEach(function(triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl);
        triggerEl.addEventListener('click', function(event) {
            event.preventDefault();
            tabTrigger.show();
        });
    });

    // Mark validation for all number inputs
    document.querySelectorAll('input[type="number"]').forEach(input => {
        input.addEventListener('input', function() {
            let value = parseInt(this.value);
            if (value > 100) this.value = 100;
            if (value < 0) this.value = 0;
        });
    });

    // Image preview functionality
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const previewId = this.getAttribute('data-preview');
            if (previewId) {
                previewImage(this, previewId);
            }
        });
    });
});

// Image preview function
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const previewImg = preview.querySelector('img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.classList.remove('d-none');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Form validation
const form = document.getElementById('f1');
if (form) {
    form.addEventListener('submit', function(event) {
        if (!validateForm()) {
            event.preventDefault();
        }
    });
}

function validateForm() {
    let isValid = true;
    
    // Required fields validation
    const requiredFields = document.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });

    // Phone number validation
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        if (input.value && !input.value.match(/^\d{10}$/)) {
            isValid = false;
            input.classList.add('is-invalid');
        }
    });

    // Aadhar number validation
    const aadharInput = document.querySelector('input[name="aadhar"]');
    if (aadharInput && aadharInput.value && !aadharInput.value.match(/^\d{4}\s\d{4}\s\d{4}$/)) {
        isValid = false;
        aadharInput.classList.add('is-invalid');
    }

    // Mark validation
    const markInputs = document.querySelectorAll('input[type="number"]');
    markInputs.forEach(input => {
        const value = parseInt(input.value);
        if (input.value && (isNaN(value) || value < 0 || value > 100)) {
            isValid = false;
            input.classList.add('is-invalid');
        }
    });

    return isValid;
}

// Add this to your HTML
document.write(`
<style>
    .time-display {
        background: linear-gradient(135deg, #0033CC 0%, #0056b3 100%);
        color: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-family: 'Consolas', monospace;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .time-display .time {
        font-size: 1.2em;
        font-weight: bold;
    }

    .time-display .user {
        font-size: 1em;
        opacity: 0.9;
        margin-top: 5px;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 80%;
        margin-top: 0.25rem;
    }

    .is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
</style>

<div class="time-display">
    <div class="time" id="timeDisplay"></div>
</div>
`);
</script>

<!-- Add this in your HTML where you want to display the time -->
<div class="time-display">
    <div class="time" id="timeDisplay"></div>
</div>

</form>
</div>
</div>
</body>
</html>