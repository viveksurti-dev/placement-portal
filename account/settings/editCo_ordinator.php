<?php
$coordinator = $opr->getCoordinator($auth['id']);

if (isset($_POST['update'])) {
    $employeecode = trim($_POST['employeecode']);
    $designation = trim($_POST['designation']);
    $department = trim($_POST['department']);
    $joiningdate = trim($_POST['joiningdate']);
    $remarks = trim($_POST['remarks']);

    // Initialize alert array if not set
    if (!isset($_SESSION['alert'])) {
        $_SESSION['alert'] = [];
    }

    if (!empty($joiningdate) && strtotime($joiningdate) > time()) {
        $_SESSION['alert'][] = [
            'type' => 'danger',
            'message' => "Joining date cannot be in the future!"
        ];
    } elseif (!empty($employeecode) && !preg_match('/^[a-zA-Z0-9]+$/', $employeecode)) {
        $_SESSION['alert'][] = [
            'type' => 'danger',
            'message' => "Employee code must be alphanumeric!"
        ];
    } else {
        $updated = $opr->updateCoordinator(
            $coordinator['coordinatorid'],
            $employeecode,
            $designation,
            $department,
            $joiningdate,
            $remarks
        );

        if ($updated) {
            $_SESSION['alert'][] = [
                'type' => 'success',
                'message' => "Coordinator profile updated successfully!"
            ];
            $coordinator = $opr->getCoordinator($auth['id']);
            echo "<script>window.location.href = '';</script>";
            exit;
        } else {
            $_SESSION['alert'][] = [
                'type' => 'danger',
                'message' => "Failed to update coordinator profile!"
            ];
        }
    }
}
?>


<section class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div
            class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="mb-0">Edit Coordinator Profile</h4>
                <small>Update your professional information</small>
            </div>
            <i class="bi bi-person-lines-fill fs-2 mt-2 mt-sm-0"></i>
        </div>
        <div class="card-body">

            <!-- Messages -->
            <?php if (!empty($successMsg)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($successMsg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            <?php if (!empty($errorMsg)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($errorMsg) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Edit Form -->
            <form method="POST" class="p-3 bg-light border rounded">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Employee Code</label>
                        <input type="text" name="employeecode" class="form-control"
                            value="<?= htmlspecialchars($coordinator['employeecode'] ?? '') ?>">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Designation</label>
                        <select name="designation" class="form-select">
                            <option value="" selected disabled>-- Select Designation --</option>
                            <?php
                            $designations = [
                                "Placement Coordinator",
                                "Assistant Coordinator",
                                "Senior Coordinator",
                                "Training Coordinator",
                                "Technical Coordinator"
                            ];
                            foreach ($designations as $desig) {
                                $selected = ($coordinator['designation'] ?? '') === $desig ? 'selected' : '';
                                echo "<option value='" . htmlspecialchars($desig) . "' $selected>$desig</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Department</label>
                        <select name="department" class="form-select">
                            <option value="" selected disabled>-- Select Department --</option>
                            <?php
                            $departments = [
                                "CSPIT (Engineering)",
                                "CMPICA (Computer Applications)",
                                "RPCP (Pharmacy)",
                                "IIIM (Management)",
                                "PDPIAS (Sciences)",
                                "MTIN (Nursing)",
                                "Physiotherapy",
                                "Paramedical Sciences"
                            ];
                            foreach ($departments as $dept) {
                                $sel = (isset($coordinator['department']) && $coordinator['department'] === $dept) ? 'selected' : '';
                                echo "<option value=\"" . htmlspecialchars($dept) . "\" $sel>" . htmlspecialchars($dept) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Joining Date</label>
                        <input type="date" name="joiningdate" class="form-control"
                            value="<?= htmlspecialchars($coordinator['joiningdate'] ?? '') ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Remarks</label>
                        <textarea name="remarks" class="form-control"
                            rows="3"><?= htmlspecialchars($coordinator['remarks'] ?? '') ?></textarea>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" name="update" class="btn btn-primary py-2 w-100">Update Profile</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
.bg-gradient-primary {
    background: linear-gradient(90deg, #0d6efd, #6610f2);
}
</style>