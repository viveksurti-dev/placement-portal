<?php
$studentData = $opr->getStudentProfile($auth['id']);

$totalFields = count($studentData);
$filledFields = 0;

foreach ($studentData as $key => $value) {
    if (in_array(strtolower($key), ['sid', 'authid', 'status'])) {
        $totalFields--;
        continue;
    }

    if (!is_null($value) && trim($value) !== '') {
        $filledFields++;
    }
}

$progress = $totalFields > 0 ? round(($filledFields / $totalFields) * 100) : 0;
?>



<section>
    <div class="container-fluid mt-1">
        <div class="row">
            <div class="col-12">
                <div class="card shadow" style="display: <?= $progress < 100 ? 'block' : 'none' ?>">
                    <div class="card-body">
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-info progress-bar-striped progress-bar-animated"
                                role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>"
                                aria-valuemin="0" aria-valuemax="100">
                                <?= $progress ?>%
                            </div>
                        </div>

                        <?php if ($progress < 100): ?>
                            <div class="alert alert-warning mt-3">
                                <strong>Note:</strong> Complete missing fields to reach 100%.
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success mt-3">
                                Your profile is fully complete!
                            </div>
                        <?php endif; ?>

                        <h6 class="mt-4">Filled <?= $filledFields ?> out of <?= $totalFields ?> fields</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>