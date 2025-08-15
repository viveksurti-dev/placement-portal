<?php
$company = $opr->getCompany($auth['id'] ?? 0);

if (isset($_POST['upload'])) {
    if (isset($_FILES['cimage']) && $_FILES['cimage']['error'] === 0) {
        $fileTmp = $_FILES['cimage']['tmp_name'];
        $fileName = basename($_FILES['cimage']['name']);
        $targetDir =  '../../uploads/company/' . $fileName;

        if (move_uploaded_file($fileTmp, $targetDir)) {
            $opr->updateCompanyImage($auth['id'], $fileName);
            $_SESSION['alert'][] = [
                'type' => 'success',
                'message' => "Company Logo Uploaded Successfully"
            ];
            echo "<script>window.location.href='';</script>";
            exit;
        } else {
            $_SESSION['alert'][] = [
                'type' => 'danger',
                'message' => "Company Logo Not Uploaded "
            ];
            echo "<script>window.location.href='';</script>";
            exit;
        }
    }
    echo "<script>window.location.href='';</script>";
    exit;
}

if (isset($_POST['update_company'])) {
    $cname           = $_POST['cname'] ?? '';
    $ctype           = $_POST['ctype'] ?? '';
    $cweb            = $_POST['cweb'] ?? '';
    $cabout          = $_POST['cabout'] ?? '';
    $csize           = $_POST['csize'] ?? '';
    $cspecialization = $_POST['cspecialization'] ?? '';
    $caddress        = $_POST['caddress'] ?? '';
    $clinkedin       = $_POST['clinkedin'] ?? '';

    if ($opr->updateCompanyProfile(
        $auth['id'],
        $cname,
        $ctype,
        $cweb,
        $cabout,
        $csize,
        $cspecialization,
        $caddress,
        $clinkedin
    )) {
        $_SESSION['alert'][] = [
            'type' => 'success',
            'message' => "Company Details Updated Successfully"
        ];
        echo "<script>window.location.href='';</script>";
        exit;
    }
}
?>
<section class="container-admin container mt-1">
    <div class="row g-3">
        <!-- Company Logo -->
        <div class="col-lg-4 col-md-5 col-12">
            <div class="card p-3">
                <small class="caption text-left"><strong>Company Logo</strong></small>
                <hr>
                <div class="profile-image mb-2" style="height: auto; aspect-ratio:1;">
                    <?php if (!empty($company['cimage'])): ?>
                    <img src="<?= BASE_URL . 'uploads/company/' . $company['cimage']; ?>" class="img-fluid rounded"
                        alt="Company Logo" loading="lazy" />
                    <?php else: ?>

                    <img src="<?= BASE_URL; ?>uploads/company/default_company.png" class="img-fluid rounded"
                        alt="Default Logo">
                    <?php endif; ?>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group mt-3">
                        <small>Upload New Logo:</small>
                        <input type="file" name="cimage" class="form-control my-2">
                        <button type="submit" name="upload" class="btn btn-outline-secondary w-100">Upload</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Company Information -->
        <div class="col-lg-8 col-md-7 col-12">
            <div class="card p-3">
                <small class="caption"><strong>Company Information</strong></small>
                <hr>
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Company Name</label>
                            <input type="text" name="cname" placeholder="Company Name"
                                value="<?= $company['cname'] ?? ''; ?>" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">Company Type</label>
                            <input type="text" name="ctype" placeholder="e.g., IT, Manufacturing"
                                value="<?= $company['ctype'] ?? ''; ?>" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">Website</label>
                            <input type="url" name="cweb" placeholder="https://" value="<?= $company['cweb'] ?? ''; ?>"
                                class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">LinkedIn</label>
                            <input type="url" name="clinkedin" placeholder="LinkedIn Profile"
                                value="<?= $company['clinkedin'] ?? ''; ?>" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">Company Size</label>
                            <input type="text" name="csize" placeholder="e.g., 200-500"
                                value="<?= $company['csize'] ?? ''; ?>" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small">Specialization</label>
                            <input type="text" name="cspecialization" placeholder="Key Expertise"
                                value="<?= $company['cspecialization'] ?? ''; ?>" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label small">Address</label>
                            <textarea name="caddress" rows="2" placeholder="Complete Address"
                                class="form-control"><?= $company['caddress'] ?? ''; ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label small">About Company</label>
                            <textarea name="cabout" rows="3" placeholder="Brief company introduction"
                                class="form-control"><?= $company['cabout'] ?? ''; ?></textarea>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" name="update_company" class="btn btn-primary px-4">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>