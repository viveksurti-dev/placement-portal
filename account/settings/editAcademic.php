<?php
$studentData = $opr->getStudentProfile($auth['id']);

// Redirect if not student
if ($auth['authrole'] !== 'student') {
    header("Location: " . BASE_URL . "account/settings/");
    exit();
}

// Handle form submission
if (isset($_POST['update'])) {
    $fields = [];
    foreach ($_POST as $key => $val) {
        if (!empty($val)) $fields[$key] = $val;
    }

    if (isset($fields['skills'])) {
        $fields['skills'] = implode(',', array_map('trim', explode(',', $fields['skills'])));
    }

    if (!empty($_FILES['certificate']['name'][0])) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/placementportal/uploads/certificates/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $certPaths = [];
        foreach ($_FILES['certificate']['name'] as $index => $originalName) {
            $tmpName = $_FILES['certificate']['tmp_name'][$index];
            $newName = $uploadDir . basename($originalName);


            while (file_exists($newName)) {
                $fileInfo = pathinfo($originalName);
                $newName = $uploadDir . $fileInfo['filename'] . $fileInfo['extension'];
            }

            if (move_uploaded_file($tmpName, $newName)) {
                $certPaths[] = 'uploads/certificates/' . basename($newName);
            }
        }

        // Merge with existing certificates
        if (!empty($studentData['certificate'])) {
            $existing = explode(',', $studentData['certificate']);
            $certPaths = array_merge($existing, $certPaths);
        }

        $fields['certificate'] = implode(',', $certPaths);
    }

    $result = $opr->updateStudentProfile($auth['id'], $fields, $_FILES);
    if ($result) {
        echo '<div class="alert alert-success">Profile updated successfully!</div>';
        $studentData = $opr->getStudentProfile($auth['id']);
    } else {
        echo '<div class="alert alert-danger">No changes made or update failed.</div>';
    }
}

// Handle resume deletion
if (!empty($_POST['delete_resume'])) {
    $opr->deleteResume($auth['id']);
    echo "<script>window.location.href='';</script>";
    exit;
}

// Handle certificate deletion
if (!empty($_POST['delete_certificate'])) {
    $opr->deleteCertificate($auth['id'], $_POST['delete_certificate']);
    echo "<script>window.location.href='';</script>";
    exit;
}

?>

<section class="container mt-3">
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">

                <!-- Personal Information -->
                <div class="card mb-3 border-info">
                    <div class="card-header bg-info text-white">Personal Information</div>
                    <div class="card-body row g-3">
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
                        <div class="col-12">
                            <label class="form-label">Skills</label>
                            <input type="text" name="skills" class="form-control" placeholder="Python, React, SQL"
                                value="<?= htmlspecialchars($studentData['skills'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- Education Information -->
                <div class="card mb-3 border-success">
                    <div class="card-header bg-success text-white">Education Details</div>
                    <div class="card-body row g-3">

                        <!-- 10th Details -->
                        <div class="col-md-6">
                            <h5>10th Details</h5>
                            <label class="form-label">Passing Year</label>
                            <input type="number" name="pass10year" class="form-control"
                                value="<?= htmlspecialchars($studentData['pass10year'] ?? '') ?>">
                            <label class="form-label mt-2">Board</label>
                            <input type="text" name="pass10board" class="form-control"
                                value="<?= htmlspecialchars($studentData['pass10board'] ?? '') ?>">
                            <label class="form-label mt-2">Percentage</label>
                            <input type="number" step="0.01" name="pass10percentage" class="form-control"
                                value="<?= htmlspecialchars($studentData['pass10percentage'] ?? '') ?>">
                        </div>

                        <!-- 12th Details -->
                        <div class="col-md-6">
                            <h5>12th Details</h5>
                            <label class="form-label">Passing Year</label>
                            <input type="number" name="pass12year" class="form-control"
                                value="<?= htmlspecialchars($studentData['pass12year'] ?? '') ?>">
                            <label class="form-label mt-2">Board</label>
                            <input type="text" name="pass12board" class="form-control"
                                value="<?= htmlspecialchars($studentData['pass12board'] ?? '') ?>">
                            <label class="form-label mt-2">Percentage</label>
                            <input type="number" step="0.01" name="pass12percentage" class="form-control"
                                value="<?= htmlspecialchars($studentData['pass12percentage'] ?? '') ?>">
                        </div>

                        <!-- Degree Type -->
                        <div class="col-12 mt-3">
                            <label class="form-label">Degree Type</label>
                            <select name="bachelortype" id="degree_type" class="form-select">
                                <option value="" selected disabled>-- Select Type --</option>
                                <option value="diploma"
                                    <?= ($studentData['bachelortype'] ?? '') === 'diploma' ? 'selected' : '' ?>>Diploma
                                </option>
                                <option value="bachelor"
                                    <?= ($studentData['bachelortype'] ?? '') === 'bachelor' ? 'selected' : '' ?>>
                                    Bachelor's</option>
                            </select>
                        </div>

                        <!-- Diploma Fields -->
                        <div id="diploma_fields" class="col-12 mt-3 p-3 border rounded bg-light" style="display:none;">
                            <h5>Diploma Details</h5>
                            <label class="form-label">Institute</label>
                            <input type="text" name="diplomainstitute" class="form-control"
                                value="<?= htmlspecialchars($studentData['diplomainstitute'] ?? '') ?>">
                            <label class="form-label mt-2">Year</label>
                            <input type="number" name="diplomayear" class="form-control"
                                value="<?= htmlspecialchars($studentData['diplomayear'] ?? '') ?>">
                            <label class="form-label mt-2">Percentage</label>
                            <input type="number" step="0.01" name="diplomapercentage" class="form-control"
                                value="<?= htmlspecialchars($studentData['diplomapercentage'] ?? '') ?>">
                        </div>

                        <!-- Bachelor Fields -->
                        <div id="bachelor_fields" class="col-12 mt-3 p-3 border rounded bg-light" style="display:none;">
                            <h5>Bachelor Details</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Bachelor Degree</label>
                                    <select name="bachelordegree" class="form-select">
                                        <option value="">Select Degree</option>
                                        <?php
                                        $degrees = ['B.A', 'B.Sc', 'B.Com', 'B.E', 'B.Tech', 'BCA', 'BBA', 'Other'];
                                        foreach ($degrees as $deg) {
                                            $selected = ($studentData['bachelordegree'] ?? '') === $deg ? 'selected' : '';
                                            echo "<option value='$deg' $selected>$deg</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">University</label>
                                    <input type="text" name="bacheloruniversity" class="form-control"
                                        value="<?= htmlspecialchars($studentData['bacheloruniversity'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mt-2">Passing Year</label>
                                    <input type="number" name="bacheloryear" class="form-control"
                                        value="<?= htmlspecialchars($studentData['bacheloryear'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mt-2">CGPA</label>
                                    <input type="number" step="0.01" name="bachelorgpa" class="form-control"
                                        value="<?= htmlspecialchars($studentData['bachelorgpa'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Documents -->
                <div class="card mb-3 border-warning">
                    <div class="card-header bg-warning text-dark">Documents</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Resume (PDF)</label>
                            <input type="file" name="resume" accept=".pdf" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Certificates (PDF/JPG/PNG) - Multiple allowed</label>
                            <input type="file" name="certificate[]" multiple accept=".pdf,.jpg,.png"
                                class="form-control">

                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" name="update" class="btn btn-primary">Save Changes</button>
                    <button type="reset" class="btn btn-outline-secondary ms-2">Reset</button>
                </div>

            </form>
        </div>
    </div>
    <!-- Documents -->
    <div class="row mt-3">
        <!-- Resumes-->
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">Resume Preview</h5>
                    <hr>
                    <?php if (!empty($studentData['resume'])): ?>
                    <div class="mb-2">
                        <a href="<?= BASE_URL . htmlspecialchars($studentData['resume']) ?>" target="_blank"
                            class="btn btn-outline-primary col-9 mt-1">
                            <?= htmlspecialchars(basename($studentData['resume'])) ?>
                        </a>

                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_resume" value="1">
                            <button type="submit" class="btn btn-outline-danger col-2"
                                onclick="return confirm('Are you sure you want to delete the resume?')">
                                Delete
                            </button>
                        </form>
                        <br>
                        <!-- <iframe src="<?= BASE_URL . htmlspecialchars($studentData['resume']) ?>" width="200"
                                height="250" style="border:1px solid #ccc; overflow:hidden; padding: 10px;">
                            </iframe> -->
                    </div>
                    <?php else: ?>
                    <p class="text-muted">No resume uploaded.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Certificates -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success">Certificates</h5>
                    <hr>
                    <?php if (!empty($studentData['certificate'])): ?>
                    <ul class="list-group">
                        <?php
                            $certs = explode(',', $studentData['certificate']);
                            foreach ($certs as $cert):
                                $cert = trim($cert);
                                if (empty($cert)) continue;
                                $certName = htmlspecialchars(basename($cert));
                                $certLink = htmlspecialchars($cert);
                                $deleteLink = '?delete_certificate=' . urlencode($cert) . '&user=' . urlencode($auth['id']);
                            ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="<?= BASE_URL . $certLink ?>" target="_blank"><?= $certName ?></a>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_certificate" value="<?= htmlspecialchars($cert) ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Are you sure you want to delete this certificate?')">
                                    Delete
                                </button>
                            </form>

                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php else: ?>
                    <p class="text-muted">No certificates uploaded.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function toggleDegreeFields() {
    const degree = document.getElementById('degree_type').value;
    document.getElementById('diploma_fields').style.display = (degree === 'diploma') ? 'block' : 'none';
    document.getElementById('bachelor_fields').style.display = (degree === 'bachelor') ? 'block' : 'none';
}
document.addEventListener('DOMContentLoaded', () => {
    toggleDegreeFields();
    document.getElementById('degree_type').addEventListener('change', toggleDegreeFields);
});
</script>