<?php
if (isset($_POST['upload'])) {
    if (isset($_FILES['userimage']) && $_FILES['userimage']['error'] === 0) {
        $fileTmp = $_FILES['userimage']['tmp_name'];
        $fileName = basename($_FILES['userimage']['name']);
        $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/placementportal/uploads/auth/' . $fileName;

        if (move_uploaded_file($fileTmp, $targetDir)) {
            $opr->updateUserImage($_SESSION['mail'], $fileName);
            $_SESSION['alert'][] = [
                'type' => 'success',
                'message' => "Profile Photo Uploaded Successfully"
            ];
            echo "<script>window.location.href='';</script>";
            exit;
        }
    }
}

if (isset($_POST['update'])) {

    $firstname  = $_POST['firstname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $lastname   = $_POST['lastname'] ?? '';
    $contact    = $_POST['contact'] ?? '';
    $city       = $_POST['city'] ?? '';
    $state      = $_POST['state'] ?? '';
    $gender     = $_POST['gender'] ?? '';
    $bio        = $_POST['bio'] ?? '';

    if ($opr->updateProfile(
        $_SESSION['mail'],
        $firstname,
        $middlename,
        $lastname,
        $contact,
        $city,
        $state,
        $gender,
        $bio
    )) {
        $_SESSION['alert'][] = [
            'type' => 'success',
            'message' => "Your Basic Details Updated"
        ];
        echo "<script>window.location.href='';</script>";
        exit;
    }
}


?>
<section class="container-admin container mt-3">
    <div class="row g-3">
        <!-- Account Management -->
        <div class="col-lg-4 col-md-5 col-12">
            <div class="card p-3">
                <small class="caption text-left"><strong>Profile Image</strong></small>
                <hr>
                <div class="profile-image mb-2" style="height: auto;aspect-ratio:1;">
                    <?php if (!empty($auth['userimage'])): ?>
                    <img src="<?= BASE_URL . 'uploads/auth/' . $auth['userimage']; ?>"
                        class="img-fluid rounded auth-img" alt="User Image" loading="lazy" />
                    <?php else: ?>
                    <img src="<?= BASE_URL; ?>uploads/auth/unkown.png" class="img-fluid rounded auth-img"
                        alt="User Image">
                    <?php endif; ?>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group mt-3">
                        <small>New Image:</small>
                        <input type="file" name="userimage" class="form-control my-2">
                        <button type="submit" name="upload" class="btn btn-outline-secondary w-100">Upload</button>
                    </div>
                </form>

            </div>
        </div>

        <!-- Profile Information -->
        <div class="col-lg-8 col-md-7 col-12">
            <div class="card p-3">
                <small class="caption"><strong>Profile Information</strong></small>
                <hr>
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">First Name</label>
                            <input type="text" name="firstname" placeholder="First Name"
                                value="<?= $auth['firstname']; ?>" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Middle Name</label>
                            <input type="text" name="middlename" placeholder="Middle Name"
                                value="<?= $auth['middlename']; ?>" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Last Name</label>
                            <input type="text" name="lastname" placeholder="Last Name" value="<?= $auth['lastname']; ?>"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Email</label>
                            <input type="email" name="mail" placeholder="Email" value="<?= $auth['mail']; ?>"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Contact</label>
                            <input type="text" name="contact" placeholder="Contact" value="<?= $auth['contact']; ?>"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">City</label>
                            <input type="text" name="city" placeholder="City" value="<?= $auth['city']; ?>"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">State</label>
                            <input type="text" name="state" placeholder="State" value="<?= $auth['state']; ?>"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Gender</label>
                            <select name="gender" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="Male" <?= ($auth['gender'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male
                                </option>
                                <option value="Female" <?= ($auth['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>
                                    Female</option>
                                <option value="Other" <?= ($auth['gender'] ?? '') === 'Other' ? 'selected' : ''; ?>>
                                    Other</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Bio</label>
                            <textarea name="bio" rows="3" placeholder="Say something about yourself"
                                class="form-control"><?= $auth['bio'] ?? ''; ?></textarea>
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <button type="submit" name="update" class="btn btn-primary px-4">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>