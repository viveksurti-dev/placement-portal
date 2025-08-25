<?php
// Handle inline update from modal for company
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_company') {
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

    $opr->updateCompanyProfile(
        $authId,
        $_POST['cname'] ?? '',
        $_POST['ctype'] ?? '',
        $_POST['cweb'] ?? '',
        $_POST['cabout'] ?? '',
        $_POST['csize'] ?? '',
        $_POST['cspecialization'] ?? '',
        $_POST['caddress'] ?? '',
        $_POST['clinkedin'] ?? ''
    );

    // Handle company image upload
    if (isset($_FILES['cimage']) && $_FILES['cimage']['error'] === UPLOAD_ERR_OK) {
        $imgName = basename($_FILES['cimage']['name']);
        $targetDir = __DIR__ . '/../uploads/company/';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }
        if (move_uploaded_file($_FILES['cimage']['tmp_name'], $targetDir . $imgName)) {
            $opr->updateCompanyImage($authId, 'uploads/company/' . $imgName);
        }
    }

    $_SESSION['alert'][] = [
        'type' => 'success',
        'message' => 'Company updated successfully.'
    ];
}

$companies = $opr->getCompanies();

?>
<style>
/* Modern Dashboard Styles */
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
.download-btn {
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

.company-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
    border: 2px solid #e9ecef;
}

.company-name {
    font-weight: 500;
    color: #2c3e50;
}

.company-email {
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
        <h1 class="dashboard-title">List of Companies</h1>

        <div class="controls-section">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" id="companySearch" placeholder="Search companies..."
                    oninput="filterTable('companyTable', this.value)">
            </div>

            <div class="dropdown">
                <button class="filter-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="fas fa-filter"></i>
                    Filter by
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('all')">All Companies</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('active')">Active</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('inactive')">Inactive</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByStatus('pending')">Pending</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#" onclick="filterByType('all')">All Types</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByType('technology')">Technology</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByType('finance')">Finance</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByType('healthcare')">Healthcare</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByType('manufacturing')">Manufacturing</a></li>
                    <li><a class="dropdown-item" href="#" onclick="filterByType('consulting')">Consulting</a></li>
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
                Add new company
            </a>
        </div>
    </div>

    <!-- Modern Table -->
    <div class="modern-table-container">
        <table id="companyTable" class="modern-table">
            <thead>
                <tr>
                    <th>COMPANY</th>
                    <th>HR NAME</th>
                    <th>INDUSTRY</th>
                    <th>CONTACT</th>
                    <th>WEBSITE</th>
                    <th>STATUS</th>
                    <th>ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company) {
                    // Status styling
                    $statusClass = 'status-active';
                    if (strtolower($company['status']) === 'inactive') {
                        $statusClass = 'status-inactive';
                    } elseif (strtolower($company['status']) === 'pending') {
                        $statusClass = 'status-pending';
                    }
                ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <?php if ($company['userimage']) { ?>
                            <img src="<?php echo BASE_URL . 'uploads/auth/' . $company['userimage']; ?>"
                                alt="Company Image" class="company-image">
                            <?php } else { ?>
                            <img src="<?php echo BASE_URL; ?>uploads/auth/unkown.png" alt="Default Image"
                                class="company-image">
                            <?php } ?>
                            <div>
                                <div class="company-name"><?php echo $company['cname']; ?></div>
                                <div class="company-email"><?php echo $company['mail']; ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?php echo $company['firstname'] . ' ' . $company['middlename'] . ' ' . $company['lastname']; ?>
                    </td>
                    <td><?php echo $company['ctype']; ?></td>
                    <td><?php echo $company['contact']; ?></td>
                    <td>
                        <?php if ($company['cweb']) { ?>
                        <a href="<?php echo $company['cweb']; ?>" target="_blank"
                            style="color: #6c63ff; text-decoration: none;">
                            <i class="fas fa-external-link-alt"></i> Visit
                        </a>
                        <?php } else { ?>
                        <span style="color: #6c757d;">-</span>
                        <?php } ?>
                    </td>
                    <td>
                        <span class="status-badge <?php echo $statusClass; ?>">
                            <?php echo $company['status']; ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="<?php echo BASE_URL; ?>accountdetails?u=<?php echo base64_encode(base64_encode($company['mail'])) ?>&r=<?php echo $company['authrole'] ?>"
                                class="action-btn action-btn-view" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="action-btn action-btn-edit" data-bs-toggle="modal"
                                data-bs-target="#editCompanyModal_<?php echo $company['authid']; ?>" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="action-btn action-btn-delete"
                                onclick="deleteCompany(<?php echo $company['authid']; ?>)" title="Delete">
                                <i class="fas fa-trash"></i>
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
    const table = document.getElementById('companyTable');
    const rows = table.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const statusCell = row.querySelector('.status-badge');
        if (statusCell) {
            const companyStatus = statusCell.textContent.toLowerCase().trim();
            if (status === 'all' || companyStatus === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}

function filterByType(type) {
    const table = document.getElementById('companyTable');
    const rows = table.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const typeCell = row.cells[2]; // Industry column
        if (typeCell) {
            const companyType = typeCell.textContent.toLowerCase().trim();
            if (type === 'all' || companyType === type) {
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

function deleteCompany(authId) {
    if (confirm('Are you sure you want to delete this company?')) {
        // Add delete functionality here
        console.log('Delete company:', authId);
    }
}
</script>

<?php foreach ($companies as $company) { ?>
<div class="modal fade" id="editCompanyModal_<?php echo $company['authid']; ?>" tabindex="-1"
    aria-labelledby="editCompanyLabel_<?php echo $company['authid']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCompanyLabel_<?php echo $company['authid']; ?>">Edit Company -
                    <?php echo htmlspecialchars($company['cname'] ?: ($company['firstname'] . ' ' . $company['lastname'])); ?>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_company" />
                <input type="hidden" name="authid" value="<?php echo (int) $company['authid']; ?>" />
                <input type="hidden" name="mail" value="<?php echo htmlspecialchars($company['mail']); ?>" />
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
                                        value="<?php echo htmlspecialchars($company['firstname']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" class="form-control" name="middlename"
                                        value="<?php echo htmlspecialchars($company['middlename']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" name="lastname"
                                        value="<?php echo htmlspecialchars($company['lastname']); ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Gender</label>
                                    <select class="form-select" name="gender">
                                        <option value="male"
                                            <?php echo strtolower($company['gender']) === 'male' ? 'selected' : ''; ?>>
                                            Male</option>
                                        <option value="female"
                                            <?php echo strtolower($company['gender']) === 'female' ? 'selected' : ''; ?>>
                                            Female</option>
                                        <option value="other"
                                            <?php echo strtolower($company['gender']) === 'other' ? 'selected' : ''; ?>>
                                            Other</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Contact</label>
                                    <input type="text" class="form-control" name="contact"
                                        value="<?php echo htmlspecialchars($company['contact']); ?>" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control" name="city"
                                        value="<?php echo htmlspecialchars($company['city']); ?>" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="state"
                                        value="<?php echo htmlspecialchars($company['state']); ?>" />
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Bio</label>
                                    <textarea class="form-control" name="bio"
                                        rows="2"><?php echo htmlspecialchars($company['bio']); ?></textarea>
                                </div>
                                <div class="col-12 mt-3">
                                    <h6 class="text-secondary">Company Details</h6>
                                    <hr class="mt-1" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" class="form-control" name="cname"
                                        value="<?php echo htmlspecialchars($company['cname']); ?>" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Type</label>
                                    <input type="text" class="form-control" name="ctype"
                                        value="<?php echo htmlspecialchars($company['ctype']); ?>" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Website</label>
                                    <input type="url" class="form-control" name="cweb"
                                        value="<?php echo htmlspecialchars($company['cweb']); ?>" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">LinkedIn</label>
                                    <input type="url" class="form-control" name="clinkedin"
                                        value="<?php echo htmlspecialchars($company['clinkedin']); ?>" />
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">About</label>
                                    <textarea class="form-control" name="cabout"
                                        rows="3"><?php echo htmlspecialchars($company['cabout']); ?></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Company Size</label>
                                    <input type="number" class="form-control" name="csize"
                                        value="<?php echo htmlspecialchars($company['csize']); ?>" />
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Specialization</label>
                                    <input type="text" class="form-control" name="cspecialization"
                                        value="<?php echo htmlspecialchars($company['cspecialization']); ?>" />
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="caddress"
                                        rows="2"><?php echo htmlspecialchars($company['caddress']); ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Company Image</label>
                                    <input type="file" class="form-control" name="cimage" accept="image/*" />
                                    <?php if (!empty($company['cimage'])) { ?>
                                    <small class="text-muted">Current: <a
                                            href="<?php echo BASE_URL . htmlspecialchars($company['cimage']); ?>"
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