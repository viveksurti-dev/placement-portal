<?php
require_once '../config.php';
require_once '../components/navbar.php';

if ($isLoggedIn === false || !isset($_SESSION['mail'])) {
    echo "<script>window.location.href = '" . BASE_URL . "auth/login.php';</script>";
    exit;
}


$authId = $_SESSION['authid'] ?? $auth['id'] ?? null;
$auths = base64_encode($auth['mail']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL ?>styles/main.css">
    <style>
    .profile-image {
        width: 180px;
        height: 180px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .profile-image .auth-img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }

    .card {
        border-radius: 10px;
    }

    .form-label {
        font-weight: 500;
    }
    </style>
</head>

<body>
    <section class="container">
        <section class="container-admin container mt-3">
            <div class="row">
                <!-- Left Column: Account Management -->
                <div class="col-lg-4 col-md-5 col-12 mb-3 ">
                    <div class="card p-3">
                        <small class="caption text-left"><strong>Account Management</strong></small>
                        <hr>
                        <div class="profile-image mb-3">
                            <?php if (!empty($auth['userimage'])) { ?>
                            <img src="<?php echo BASE_URL . 'uploads/auth/' . $auth['userimage']; ?>"
                                class="auth-img img-fluid rounded" alt="User Image">
                            <?php } else { ?>
                            <img src="<?php echo BASE_URL; ?>uploads/auth/unkown.png" class="auth-img img-fluid rounded"
                                alt="User Image">
                            <?php } ?>
                        </div>
                        <form method="POST">
                            <div class="form-group mt-3">
                                <small>New Image:</small>
                                <input type="file" name="userimage" class="form-control">
                                <input type="submit" value="Upload" name="upload"
                                    class="btn btn-outline-secondary mt-2 w-100">
                            </div>
                            <div class="form-group mt-3">
                                <small>Bio:</small><br />
                                <textarea name="bio" rows="5" placeholder="Say about yourself"
                                    class="form-control"></textarea>
                            </div>
                            <input type="submit" value="Add" name="upload" class="btn btn-outline-primary w-100 mt-2">
                        </form>

                    </div>
                </div>

                <!-- Right Column: Edit Profile Form -->
                <div class="col-lg-8 col-md-7 col-12">
                    <div class="card p-3">
                        <small class="caption"><strong>Profile Information</strong></small>
                        <hr>
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small">First Name</label>
                                    <input type="text" name="firstname" placeholder="First Name"
                                        value="<?php echo $auth['firstname']; ?>" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small">Middle Name</label>
                                    <input type="text" name="middlename" placeholder="Middle Name"
                                        value="<?php echo $auth['middlename']; ?>" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small">Last Name</label>
                                    <input type="text" name="lastname" placeholder="Last Name"
                                        value="<?php echo $auth['lastname']; ?>" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small">Email</label>
                                    <input type="email" name="mail" placeholder="Email"
                                        value="<?php echo $auth['mail']; ?>" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small">Contact</label>
                                    <input type="text" name="contact" placeholder="Contact"
                                        value="<?php echo $auth['contact']; ?>" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small">City</label>
                                    <input type="text" name="city" placeholder="City"
                                        value="<?php echo $auth['city']; ?>" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small">State</label>
                                    <input type="text" name="state" placeholder="State"
                                        value="<?php echo $auth['state']; ?>" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small">Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary px-4">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- admin section -->
        <?php if ($auth['authrole'] === 'admin') { ?>



        <?php } else if ($auth['authrole'] === 'co-ordinator') { ?>
        <?php } else if ($auth['authrole'] === 'comapany') { ?>
        <div class=" container">
        </div>
        <?php } else { ?>
        <?php

            $studentData = $obj->getStudentProfile($authId);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $fields = [];
                foreach ($_POST as $key => $val) {
                    if (!empty($val)) $fields[$key] = $val;
                }
                if (isset($fields['skills'])) {
                    $fields['skills'] = array_map('trim', explode(',', $fields['skills']));
                }
                $result = $obj->updateStudentProfile($authId, $fields, $_FILES);
                if ($result) {
                    echo '<div class="alert alert-success">Profile updated successfully!</div>';
                    $studentData = $obj->getStudentProfile($authId);
                } else {
                    echo '<div class="alert alert-danger">No changes made or update failed.</div>';
                }
            }

            // Handle delete actions
            if (isset($_GET['delete_resume']) && $_GET['delete_resume'] == 1) {
                $obj->deleteResume($authId);
                header("Location: editProfile.php?user=" . $auths);
                exit;
            }
            if (isset($_GET['delete_certificate'])) {
                $obj->deleteCertificate($authId, $_GET['delete_certificate']);
                header("Location: editProfile.php?user=" . $auths);
                exit;
            }
            ?>
        <section>
            <div class="container mt-3">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="mb-0">Edit Student Profile</h4>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            <ul class="nav nav-tabs mb-3" id="formTabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#personal"
                                        type="button">Personal</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#education"
                                        type="button">Education</button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#documents"
                                        type="button">Documents</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="formTabsContent">
                                <!-- Personal Tab -->
                                <div class="tab-pane fade show active" id="personal">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Student ID</label>
                                            <input type="text" name="studentid" class="form-control"
                                                value="<?= htmlspecialchars($studentData['studentid'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Branch</label>
                                            <input type="text" name="branch" class="form-control"
                                                value="<?= htmlspecialchars($studentData['branch'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">CGPA</label>
                                            <input type="number" step="0.01" name="cgpa" class="form-control"
                                                value="<?= htmlspecialchars($studentData['cgpa'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Skills</label>
                                            <input type="text" name="skills" class="form-control"
                                                placeholder="e.g., Python, React, SQL"
                                                value="<?= htmlspecialchars($studentData['skills'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>
                                <!-- Education Tab -->
                                <div class="tab-pane fade" id="education">
                                    <div class="row g-3 mt-3">
                                        <h5>10th Details</h5>
                                        <div class="col-md-4">
                                            <label class="form-label">Passing Year</label>
                                            <input type="number" name="pass10year" class="form-control"
                                                value="<?= htmlspecialchars($studentData['pass10year'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Board</label>
                                            <input type="text" name="pass10board" class="form-control"
                                                value="<?= htmlspecialchars($studentData['pass10board'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Percentage</label>
                                            <input type="number" step="0.01" name="pass10percentage"
                                                class="form-control"
                                                value="<?= htmlspecialchars($studentData['pass10percentage'] ?? '') ?>">
                                        </div>
                                        <h5 class="mt-4">12th Details</h5>
                                        <div class="col-md-4">
                                            <label class="form-label">Passing Year</label>
                                            <input type="number" name="pass12year" class="form-control"
                                                value="<?= htmlspecialchars($studentData['pass12year'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Board</label>
                                            <input type="text" name="pass12board" class="form-control"
                                                value="<?= htmlspecialchars($studentData['pass12board'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Percentage</label>
                                            <input type="number" step="0.01" name="pass12percentage"
                                                class="form-control"
                                                value="<?= htmlspecialchars($studentData['pass12percentage'] ?? '') ?>">
                                        </div>
                                        <!-- Degree Type Selector -->
                                        <div class="col-md-4">
                                            <label class="form-label">Degree Type</label>
                                            <select name="degree_type" id="degree_type" class="form-select">
                                                <option value="">Select</option>
                                                <option value="diploma"
                                                    <?= ($studentData['degree_type'] ?? '') === 'diploma' ? 'selected' : '' ?>>
                                                    Diploma</option>
                                                <option value="bachelor"
                                                    <?= ($studentData['degree_type'] ?? '') === 'bachelor' ? 'selected' : '' ?>>
                                                    Bachelor</option>
                                            </select>
                                        </div>

                                        <!-- Diploma Fields -->
                                        <div id="diploma_fields" style="display:none;">
                                            <h5 class="mt-4">Diploma</h5>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Institute</label>
                                                    <input type="text" name="diplomainstitute" class="form-control"
                                                        value="<?= htmlspecialchars($studentData['diplomainstitute'] ?? '') ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Year</label>
                                                    <input type="number" name="diplomayear" class="form-control"
                                                        value="<?= htmlspecialchars($studentData['diplomayear'] ?? '') ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Percentage</label>
                                                    <input type="number" step="0.01" name="diplomapercentage"
                                                        class="form-control"
                                                        value="<?= htmlspecialchars($studentData['diplomapercentage'] ?? '') ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bachelor Fields -->
                                        <div id="bachelor_fields" style="display:none;">
                                            <h5 class="mt-4">Bachelor’s Degree</h5>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Degree</label>
                                                    <select name="bachelortype" class="form-select">
                                                        <option value="">Select</option>
                                                        <option value="B.E"
                                                            <?= ($studentData['bachelortype'] ?? '') === 'B.E' ? 'selected' : '' ?>>
                                                            B.E</option>
                                                        <option value="B.Tech"
                                                            <?= ($studentData['bachelortype'] ?? '') === 'B.Tech' ? 'selected' : '' ?>>
                                                            B.Tech</option>
                                                        <option value="B.Sc"
                                                            <?= ($studentData['bachelortype'] ?? '') === 'B.Sc' ? 'selected' : '' ?>>
                                                            B.Sc</option>
                                                        <option value="Other"
                                                            <?= ($studentData['bachelortype'] ?? '') === 'Other' ? 'selected' : '' ?>>
                                                            Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">University</label>
                                                    <input type="text" name="bacheloruniversity" class="form-control"
                                                        value="<?= htmlspecialchars($studentData['bacheloruniversity'] ?? '') ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Passing Year</label>
                                                    <input type="number" name="bacheloryear" class="form-control"
                                                        value="<?= htmlspecialchars($studentData['bacheloryear'] ?? '') ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">CGPA</label>
                                                    <input type="number" step="0.01" name="bachelorgpa"
                                                        class="form-control"
                                                        value="<?= htmlspecialchars($studentData['bachelorgpa'] ?? '') ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Branch</label>
                                                    <input type="text" name="bachelorbranch" class="form-control"
                                                        value="<?= htmlspecialchars($studentData['bachelorbranch'] ?? '') ?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Course</label>
                                                    <input type="text" name="bachelorcourse" class="form-control"
                                                        value="<?= htmlspecialchars($studentData['bachelorcourse'] ?? '') ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Documents Tab -->
                                <div class="tab-pane fade" id="documents">
                                    <div class="row g-3 mt-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Upload Resume (PDF)</label>
                                            <?php if (!empty($studentData['resume'])): ?>
                                            <div>
                                                <a href="../<?= htmlspecialchars($studentData['resume']) ?>"
                                                    target="_blank">View
                                                    Uploaded Resume</a>
                                            </div>
                                            <?php endif; ?>
                                            <input type="file" name="resume" accept=".pdf" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Upload Certificates</label>

                                            <input type="file" name="certificate[]" multiple class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <?php
                                            if (!empty($studentData['certificate'])) {
                                                $certs = explode(',', $studentData['certificate']);
                                                foreach ($certs as $cert) {
                                                    echo '<a href="../' . htmlspecialchars($cert) . '" target="_blank">' . basename($cert) . '</a><br>';
                                                }
                                            }
                                            ?>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="reset" class="btn btn-outline-secondary ms-2">Reset</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </section>
        <section class="container mt-4">
            <div class="row">
                <!-- Resume Preview Section -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Resume Preview</h5>
                            <?php if (!empty($studentData['resume'])): ?>
                            <div class="mb-2">
                                <strong>Uploaded Resume:</strong><br>
                                <a href="../<?= htmlspecialchars($studentData['resume']) ?>" target="_blank"
                                    class="btn btn-outline-primary btn-sm mt-1">
                                    <?= htmlspecialchars(basename($studentData['resume'])) ?>
                                </a>
                                <a href="?delete_resume=1&user=<?= urlencode($_GET['user']) ?>"
                                    class="btn btn-outline-danger btn-sm ms-2"
                                    onclick="return confirm('Are you sure you want to delete the resume?')">
                                    Delete
                                </a>
                            </div>
                            <?php else: ?>
                            <p class="text-muted">No resume uploaded.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Certificate List Section -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-success">Certificates</h5>
                            <?php if (!empty($studentData['certificate'])): ?>
                            <ul class="list-group">
                                <?php
                                        $certs = explode(',', $studentData['certificate']);
                                        foreach ($certs as $cert) {
                                            $cert = trim($cert);
                                            $certName = htmlspecialchars(basename($cert));
                                            $certLink = htmlspecialchars($cert);
                                            $deleteLink = '?delete_certificate=' . urlencode($cert) . '&user=' . urlencode($_GET['user']);

                                            echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                            echo '<a href="../' . $certLink . '" target="_blank">' . $certName . '</a>';
                                            echo '<a href="' . $deleteLink . '" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Delete this certificate?\')">Delete</a>';
                                            echo '</li>';
                                        }
                                        ?>
                            </ul>
                            <?php else: ?>
                            <p class="text-muted">No certificates uploaded.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php } ?>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        function toggleDegreeFields() {
            var degreeType = document.getElementById('degree_type').value;
            document.getElementById('diploma_fields').style.display = (degreeType === 'diploma') ? 'block' :
                'none';
            document.getElementById('bachelor_fields').style.display = (degreeType === 'bachelor') ?
                'block' : 'none';
        }

        // On page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleDegreeFields();
            document.getElementById('degree_type').addEventListener('change', toggleDegreeFields);
        });
        </script>
    </section>
</body>

</html>