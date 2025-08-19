<?php
$socialLinks = $opr->getSocialLinks($auth['id']);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['platform'], $_POST['link'])) {
    $platform = trim($_POST['platform']);
    $link = trim($_POST['link']);

    if (!empty($platform) && filter_var($link, FILTER_VALIDATE_URL)) {

        $existingLink = $opr->getSocialLinkByPlatform($auth['id'], $platform);

        if ($existingLink) {
            $updated = $opr->updateSocialLink($existingLink['slid'], $link);
            $_SESSION['alert'][] = [
                'type' => 'success',
                'message' => "Social link for <strong>{$platform}</strong> updated successfully!"
            ];
        } else {
            $inserted = $opr->insertSocialLink($auth['id'], $platform, $link);
            $_SESSION['alert'][] = [
                'type' => 'success',
                'message' => "Social link for <strong>{$platform}</strong> added successfully!"
            ];
        }

        $socialLinks = $opr->getSocialLinks($auth['id']);
    } else {
        $_SESSION['alert'][] = [
            'type' => 'danger',
            'message' => "Please enter a valid platform and URL."
        ];
    }
}

// Delete social link
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_slid'])) {
    $slid = $_POST['delete_slid'];
    $deleted = $opr->deleteSocialLink($slid);

    if ($deleted) {
        $_SESSION['alert'][] = [
            'type' => 'success',
            'message' => "Link deleted successfully!"
        ];
    } else {
        $_SESSION['alert'][] = [
            'type' => 'danger',
            'message' => "Failed to delete link!"
        ];
    }

    $socialLinks = $opr->getSocialLinks($auth['id']);
}
?>


<section class="container-fluid mt-4">
    <div class="card shadow-sm mb-4">
        <div
            class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4 class="mb-0">Manage Your Social Links</h4>
                <small>Keep your professional profiles up-to-date for recruiters</small>
            </div>
            <i class="bi bi-people-fill fs-2 mt-2 mt-sm-0"></i>
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

            <!-- Add  -->
            <form method="POST" class="mb-4 p-3 bg-light border rounded">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        <label class="form-label fw-bold">Platform</label>
                        <select name="platform" class="form-select" required>
                            <option value="" disabled selected>-- Select Platform --</option>
                            <?php
                            $platformOptions = ['LinkedIn', 'GitHub', 'Portfolio', 'Youtube', 'Instagram', 'Leetcode', 'HackerRank', 'Other'];
                            foreach ($platformOptions as $option) {
                                echo "<option value='$option'>$option</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-bold">Profile Link</label>
                        <input type="url" name="link" class="form-control" placeholder="https://example.com" required>
                    </div>
                    <div class="col-12 col-md-2 text-end">
                        <button type="submit" class="btn btn-gradient-primary w-100">Save</button>
                    </div>
                </div>
            </form>

            <!-- Existing Links -->
            <h5 class="text-success mb-3"><i class="bi bi-link-45deg me-2 "></i>Your Links</h5>
            <?php if (!empty($socialLinks)): ?>
            <ul class="list-group">
                <?php foreach ($socialLinks as $linkData):
                        $platform = $linkData['platform'];
                        $link = $linkData['link'];
                        $slid = $linkData['slid'];
                        $badgeClass = match (strtolower($platform)) {
                            'linkedin' => 'bg-primary',
                            'github' => 'bg-dark text-white',
                            'portfolio' => 'bg-success',
                            'youtube' => 'bg-danger text-white',
                            'instagram' => 'bg-pink text-white',
                            'leetcode' => 'bg-warning text-dark',
                            'hackerrank' => 'bg-success text-white',
                            default => 'bg-secondary text-white'
                        };
                    ?>
                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center flex-wrap py-2">
                        <span
                            class="badge <?= $badgeClass ?> me-2 mb-1 mb-sm-0"><?= htmlspecialchars($platform) ?></span>
                        <a href="<?= htmlspecialchars($link) ?>" target="_blank"
                            class="text-decoration-none"><?= htmlspecialchars($link) ?></a>
                    </div>
                    <form method="POST" class="m-0">
                        <input type="hidden" name="delete_slid" value="<?= $slid ?>">
                        <button type="submit" class="btn btn-outline-danger mt-2 mt-sm-0"
                            onclick="return confirm('Are you sure you want to delete this link?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p class="text-muted">No social media links added yet. Add your first one above!</p>
            <?php endif; ?>

        </div>
    </div>

</section>

<style>
/* Hover effect on list items */
.hover-shadow:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: 0.3s;
}

/* Gradient button */
.btn-gradient-primary {
    background: linear-gradient(45deg, #0d6efd, #6610f2);
    color: #fff;
    border: none;
}

.btn-gradient-primary:hover {
    background: linear-gradient(45deg, #6610f2, #0d6efd);
}

/* Card gradient header */
.bg-gradient-primary {
    background: linear-gradient(90deg, #0d6efd, #6610f2);
}

/* Keep form fields neutral after submit */
form select.form-select,
form input.form-control {
    border-color: #ced4da;
    box-shadow: none;
}

/* Optional focus style */
form select.form-select:focus,
form input.form-control:focus {
    border-color: #6610f2;
    box-shadow: 0 0 0 0.2rem rgba(102, 16, 242, .25);
}

/* Responsive spacing for flex-wrap */
@media (max-width: 575.98px) {
    .hover-shadow div {
        width: 100%;
    }
}

/* Instagram badge color */
.bg-pink {
    background-color: #E4405F !important;
}
</style>