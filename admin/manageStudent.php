<?php
// Handle inline update from modal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_student') {
    $authId = isset($_POST['authid']) ? (int) $_POST['authid'] : 0;
    $email = $_POST['mail'] ?? '';

    if (!empty($email)) {
        $opr->updateProfile(
            $email,
            $_POST['firstname'] ?? '',
            $_POST['middlename'] ?? '',
            $_POST['lastname'] ?? '',
            $_POST['contact'] ?? '',
            $_POST['city'] ?? '',
            $_POST['state'] ?? '',
            $_POST['gender'] ?? '',
            $_POST['bio'] ?? ''
        );
    }

    $studentFields = [
        'authid' => $authId,
        'studentid' => $_POST['studentid'] ?? '',
        'branch' => $_POST['branch'] ?? '',
        'cgpa' => $_POST['cgpa'] ?? '',
        'skills' => $_POST['skills'] ?? '',
        'pass10year' => $_POST['pass10year'] ?? '',
        'pass10board' => $_POST['pass10board'] ?? '',
        'pass10percentage' => $_POST['pass10percentage'] ?? '',
        'pass12year' => $_POST['pass12year'] ?? '',
        'pass12board' => $_POST['pass12board'] ?? '',
        'pass12percentage' => $_POST['pass12percentage'] ?? '',
        'bachelortype' => $_POST['bachelortype'] ?? '',
        'diplomainstitute' => $_POST['diplomainstitute'] ?? '',
        'diplomayear' => $_POST['diplomayear'] ?? '',
        'diplomapercentage' => $_POST['diplomapercentage'] ?? '',
        'bacheloruniversity' => $_POST['bacheloruniversity'] ?? '',
        'bacheloryear' => $_POST['bacheloryear'] ?? '',
        'bachelorgpa' => $_POST['bachelorgpa'] ?? '',
        'bachelordegree' => $_POST['bachelordegree'] ?? ''
    ];

    $opr->updateStudentProfile($authId, $studentFields, $_FILES);

    $_SESSION['alert'][] = [
        'type' => 'success',
        'message' => 'Student updated successfully.'
    ];
}

$students = $opr->getStudets();

?>
<style>
.dashboard-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 20px;
}

.dashboard-header {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.dashboard-title {
    font-size: 24px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 20px;
}

.controls-section {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.search-container {
    flex: 1;
    min-width: 300px;
    position: relative;
}

.search-input {
    width: 100%;
    padding: 12px 16px 12px 44px;
    border: 1px solid #e1e5e9;
    border-radius: 8px;
    font-size: 14px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #6c63ff;
    background: white;
    box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
}

.search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
}

.filter-btn,
.sort-btn {
    padding: 12px 16px;
    border: 1px solid #e1e5e9;
    border-radius: 8px;
    background: white;
    color: #495057;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-btn:hover,
.download-btn:hover {
    border-color: #6c63ff;
    color: #6c63ff;
}

.add-btn {
    padding: 12px 24px;
    background: #6c63ff;
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.add-btn:hover {
    background: rgba(108, 99, 255, 0.85);
    color: white;
    text-decoration: none;
}

.download-btn {
    padding: 12px 16px;
    background: white;
    color: #6c63ff;
    border: 1px solid #6c63ff;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.download-btn:hover {
    background: #6c63ff;
    color: white;
}

.dropdown-menu {
    border: 1px solid #e1e5e9;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.dropdown-item {
    padding: 8px 16px;
    color: #495057;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background: rgba(108, 99, 255, 0.1);
    color: #6c63ff;
}

/* Modern Table Styles */
.modern-table-container {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.modern-table thead th {
    background: #f8f9fa;
    padding: 16px 12px;
    text-align: left;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #e9ecef;
    position: sticky;
    top: 0;
    z-index: 10;
}

.modern-table tbody tr {
    border-bottom: 1px solid #f1f3f4;
    transition: background-color 0.2s ease;
}

.modern-table tbody tr:nth-child(even) {
    background: #f8f9fa;
}

.modern-table tbody tr:hover {
    background: rgba(108, 99, 255, 0.05);
}

.modern-table tbody td {
    padding: 16px 12px;
    vertical-align: middle;
}

.student-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
    border: 2px solid #e9ecef;
}

.student-name {
    font-weight: 500;
    color: #2c3e50;
}

.student-email {
    color: #6c757d;
    font-size: 13px;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: #e9ecef;
    border-radius: 3px;
    overflow: hidden;
    margin-top: 4px;
}

.progress-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.3s ease;
}

.progress-high {
    background: rgba(108, 99, 255, 1);
}

.progress-medium {
    background: rgba(108, 99, 255, 0.7);
}

.progress-low {
    background: rgba(108, 99, 255, 0.4);
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.action-btn-view {
    background: rgba(108, 99, 255, 1);
    color: white;
}

.action-btn-view:hover {
    background: rgba(108, 99, 255, 0.85);
    color: white;
}

.action-btn-edit {
    background: rgba(108, 99, 255, 0.7);
    color: white;
}

.action-btn-edit:hover {
    background: rgba(108, 99, 255, 0.85);
    color: white;
}

.action-btn-delete {
    background: rgba(108, 99, 255, 0.4);
    color: white;
}

.action-btn-delete:hover {
    background: rgba(108, 99, 255, 0.55);
    color: white;
}

/* Modal Styling */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-header {
    background: rgba(108, 99, 255, 0.05);
    border-bottom: 1px solid rgba(108, 99, 255, 0.1);
}

.modal-title {
    color: #6c63ff;
    font-weight: 600;
}

.modal-body {
    padding: 24px;
}

.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: rgba(108, 99, 255, 0.3);
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: rgba(108, 99, 255, 0.5);
}

/* Responsive Design */
@media (max-width: 768px) {
    .controls-section {
        flex-direction: column;
        align-items: stretch;
    }

    .search-container {
        min-width: auto;
    }

    .modern-table {
        font-size: 12px;
    }

    .modern-table thead th,
    .modern-table tbody td {
        padding: 12px 8px;
    }
}
</style>

<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">List of Students</h1>

        <div class="controls-section">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="studentSearch" placeholder="Search students..."
                    oninput="filterTable('studentTable', this.value)">
            </div>

            <div class="dropdown">
                <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-filter"></i>
                    Filter by
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('all')">All Students</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('active')">Active</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('inactive')">Inactive</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('pending')">Pending</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#" onclick="filterByBranch('all')">All Branches</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByBranch('cse')">CSE</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByBranch('it')">IT</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByBranch('ece')">ECE</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByBranch('eee')">EEE</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByBranch('mech')">MECH</a></li>
                </ul>
            </div>

            <div class="dropdown">
                <button class="download-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-download"></i>
                    Download Data
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="downloadData('csv')">
                            <i class="fas fa-file-csv"></i> CSV Format
                        </a></li>
                    <li><a class="dropdown-item" href="#" onclick="downloadData('excel')">
                            <i class="fas fa-file-excel"></i> Excel Format
                        </a></li>
                </ul>
            </div>

            <a href="#" class="add-btn">
                <i class="fas fa-plus"></i>
                Add new student
            </a>
        </div>
    </div>

    <!-- Modern Table -->
    <div class="modern-table-container">
        <table id="studentTable" class="modern-table">
            <thead>
                <tr>
                    <th>STUDENT</th>
                    <th>STUDENT ID</th>
                    <th>BRANCH</th>
                    <th>CGPA</th>
                    <th>CONTACT</th>
                    <th>STATUS</th>
                    <th>PROGRESS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student) {
                    // Calculate progress based on CGPA
                    $cgpa = floatval($student['cgpa']);
                    $progress = 0;
                    $progressClass = 'progress-low';

                    if ($cgpa >= 8.0) {
                        $progress = 90;
                        $progressClass = 'progress-high';
                    } elseif ($cgpa >= 7.0) {
                        $progress = 75;
                        $progressClass = 'progress-high';
                    } elseif ($cgpa >= 6.0) {
                        $progress = 60;
                        $progressClass = 'progress-medium';
                    } elseif ($cgpa >= 5.0) {
                        $progress = 45;
                        $progressClass = 'progress-medium';
                    } else {
                        $progress = 30;
                        $progressClass = 'progress-low';
                    }

                    // Status styling
                    $statusClass = 'status-active';
                    if (strtolower($student['status']) === 'inactive') {
                        $statusClass = 'status-inactive';
                    } elseif (strtolower($student['status']) === 'pending') {
                        $statusClass = 'status-pending';
                    }
                ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <?php if ($student['userimage']) { ?>
                            <img src="<?php echo BASE_URL . 'uploads/auth/' . $student['userimage']; ?>"
                                alt="Student Image" class="student-image">
                            <?php } else { ?>
                            <img src="<?php echo BASE_URL; ?>uploads/auth/unkown.png" alt="Default Image"
                                class="student-image">
                            <?php } ?>
                            <div>
                                <div class="student-name">
                                    <?php echo $student['firstname'] . ' ' . $student['middlename'] . ' ' . $student['lastname']; ?>
                                </div>
                                <div class="student-email"><?php echo $student['mail']; ?></div>
                            </div>
                        </div>
                    </td>
                    <td><strong><?php echo $student['studentid']; ?></strong></td>
                    <td><?php echo $student['branch']; ?></td>
                    <td><strong><?php echo $student['cgpa']; ?></strong></td>
                    <td><?php echo $student['contact']; ?></td>
                    <td>
                        <span class="status-badge <?php echo $statusClass; ?>">
                            <?php echo $student['status']; ?>
                        </span>
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 12px; color: #6c757d;"><?php echo $progress; ?>%</span>
                            <div class="progress-bar">
                                <div class="progress-fill <?php echo $progressClass; ?>"
                                    style="width: <?php echo $progress; ?>%"></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?php echo BASE_URL; ?>accountdetails?u=<?php echo base64_encode(base64_encode($student['mail'])) ?>&r=<?php echo $student['authrole'] ?>"
                                class="action-btn action-btn-view" title="View Profile">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button type="button" class="action-btn action-btn-edit" data-bs-toggle="modal"
                                data-bs-target="#editStudentModal_<?php echo $student['authid']; ?>" title="Edit">
                                <i class="bi bi-pencil"></i>

                            </button>
                            <button type="button" class="action-btn action-btn-delete"
                                onclick="deleteStudent(<?php echo $student['authid']; ?>)" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function filterTable(tableId, query) {
    const q = (query || '').toLowerCase();
    const table = document.getElementById(tableId);
    if (!table) return;
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
    });
}

function filterByStatus(status) {
    const table = document.getElementById('studentTable');
    const rows = table.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const statusCell = row.querySelector('.status-badge');
        if (statusCell) {
            const studentStatus = statusCell.textContent.toLowerCase().trim();
            if (status === 'all' || studentStatus === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}

function filterByBranch(branch) {
    const table = document.getElementById('studentTable');
    const rows = table.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const branchCell = row.cells[2]; // Branch column
        if (branchCell) {
            const studentBranch = branchCell.textContent.toLowerCase().trim();
            if (branch === 'all' || studentBranch === branch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}

function downloadData(format) {
    // Add download functionality here
    if (format === 'csv') {
        console.log('Downloading CSV...');
        // Implement CSV download
    } else if (format === 'excel') {
        console.log('Downloading Excel...');
        // Implement Excel download
    }
}

function deleteStudent(authId) {
    if (confirm('Are you sure you want to delete this student?')) {
        // Add delete functionality here
        console.log('Delete student:', authId);
    }
}
</script>
<?php foreach ($students as $student) { ?>
<div class="modal fade" id="editStudentModal_<?php echo $student['authid']; ?>" tabindex="-1"
    aria-labelledby="editStudentLabel_<?php echo $student['authid']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStudentLabel_<?php echo $student['authid']; ?>">Edit Student -
                    <?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_student" />
                <input type="hidden" name="authid" value="<?php echo (int) $student['authid']; ?>" />
                <input type="hidden" name="mail" value="<?php echo htmlspecialchars($student['mail']); ?>" />
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <h6 class="text-secondary">Basic Information</h6>
                                    <hr class="mt-1" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" name="firstname"
                                        value="<?php echo htmlspecialchars($student['firstname']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" name="middlename"
                                        value="<?php echo htmlspecialchars($student['middlename']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="lastname"
                                        value="<?php echo htmlspecialchars($student['lastname']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select" name="gender">
                                        <option value="male"
                                            <?php echo strtolower($student['gender']) === 'male' ? 'selected' : ''; ?>>
                                            Male</option>
                                        <option value="female"
                                            <?php echo strtolower($student['gender']) === 'female' ? 'selected' : ''; ?>>
                                            Female</option>
                                        <option value="other"
                                            <?php echo strtolower($student['gender']) === 'other' ? 'selected' : ''; ?>>
                                            Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Contact</label>
                                    <input type="text" class="form-control" name="contact"
                                        value="<?php echo htmlspecialchars($student['contact']); ?>" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city"
                                        value="<?php echo htmlspecialchars($student['city']); ?>" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="state"
                                        value="<?php echo htmlspecialchars($student['state']); ?>" />
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Bio</label>
                                    <textarea class="form-control" name="bio"
                                        rows="2"><?php echo htmlspecialchars($student['bio']); ?></textarea>
                                </div>
                                <div class="col-12 mt-3">
                                    <h6 class="text-secondary">Academic Details</h6>
                                    <hr class="mt-1" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Student ID</label>
                                    <input type="text" class="form-control" name="studentid"
                                        value="<?php echo htmlspecialchars($student['studentid']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Branch</label>
                                    <input type="text" class="form-control" name="branch"
                                        value="<?php echo htmlspecialchars($student['branch']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">CGPA</label>
                                    <input type="number" step="0.01" class="form-control" name="cgpa"
                                        value="<?php echo htmlspecialchars($student['cgpa']); ?>" />
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Skills (comma separated)</label>
                                    <input type="text" class="form-control" name="skills"
                                        value="<?php echo htmlspecialchars($student['skills']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">10th Year</label>
                                    <input type="number" class="form-control" name="pass10year"
                                        value="<?php echo htmlspecialchars($student['pass10year']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">10th Board</label>
                                    <input type="text" class="form-control" name="pass10board"
                                        value="<?php echo htmlspecialchars($student['pass10board']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">10th %</label>
                                    <input type="number" step="0.01" class="form-control" name="pass10percentage"
                                        value="<?php echo htmlspecialchars($student['pass10percentage']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">12th Year</label>
                                    <input type="number" class="form-control" name="pass12year"
                                        value="<?php echo htmlspecialchars($student['pass12year']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">12th Board</label>
                                    <input type="text" class="form-control" name="pass12board"
                                        value="<?php echo htmlspecialchars($student['pass12board']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">12th %</label>
                                    <input type="number" step="0.01" class="form-control" name="pass12percentage"
                                        value="<?php echo htmlspecialchars($student['pass12percentage']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Bachelor Type</label>
                                    <input type="text" class="form-control" name="bachelortype"
                                        value="<?php echo htmlspecialchars($student['bachelortype']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Diploma Institute</label>
                                    <input type="text" class="form-control" name="diplomainstitute"
                                        value="<?php echo htmlspecialchars($student['diplomainstitute']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Diploma Year</label>
                                    <input type="number" class="form-control" name="diplomayear"
                                        value="<?php echo htmlspecialchars($student['diplomayear']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Diploma %</label>
                                    <input type="number" step="0.01" class="form-control" name="diplomapercentage"
                                        value="<?php echo htmlspecialchars($student['diplomapercentage']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Bachelor University</label>
                                    <input type="text" class="form-control" name="bacheloruniversity"
                                        value="<?php echo htmlspecialchars($student['bacheloruniversity']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Bachelor Year</label>
                                    <input type="number" class="form-control" name="bacheloryear"
                                        value="<?php echo htmlspecialchars($student['bacheloryear']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Bachelor GPA</label>
                                    <input type="number" step="0.01" class="form-control" name="bachelorgpa"
                                        value="<?php echo htmlspecialchars($student['bachelorgpa']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Bachelor Degree</label>
                                    <input type="text" class="form-control" name="bachelordegree"
                                        value="<?php echo htmlspecialchars($student['bachelordegree']); ?>" />
                                </div>
                                <div class="col-12 mt-3">
                                    <h6 class="text-secondary">Documents</h6>
                                    <hr class="mt-1" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Resume (PDF)</label>
                                    <input type="file" class="form-control" name="resume" accept="application/pdf" />
                                    <?php if (!empty($student['resume'])) { ?>
                                    <small class="text-muted">Current: <a
                                            href="<?php echo BASE_URL . htmlspecialchars($student['resume']); ?>"
                                            target="_blank">View</a></small>
                                    <?php } ?>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Certificate (PDF)</label>
                                    <input type="file" class="form-control" name="certificate"
                                        accept="application/pdf" />
                                    <?php if (!empty($student['certificate'])) { ?>
                                    <small class="text-muted">Current: <a
                                            href="<?php echo BASE_URL . htmlspecialchars($student['certificate']); ?>"
                                            target="_blank">View</a></small>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>