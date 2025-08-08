<!-- Total Info -->
<div class="col-md-6 p-3 container-total-info">
    <div class="card p-3 mb-4 shadow-sm">
        <div class="container">
            <div class="row">
                <!-- First Column -->
                <div class="col-md-4 border-end border-gray">
                    <div class="main-info d-flex flex-column pt-3 pb-3 text-center">
                        <div class="d-flex mt-3 mb-3">
                            <div class="info-icon col-2">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="info-content col-10 d-flex flex-column">
                                <div class="info-numbers">
                                    <?php $totalStudents = count($opr->totalStudent()) ?>
                                    <strong><?php echo $totalStudents ?></strong>
                                </div>
                                <div class="info-title caption">
                                    <small>students</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex mt-3 mb-3">
                            <div class="info-icon col-2">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="info-content col-10 d-flex flex-column">
                                <div class="info-numbers">
                                    <strong>00</strong>
                                </div>
                                <div class="info-title caption">
                                    <small>students</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Column -->
                <div class="col-md-4 border-end border-gray">
                    <div class="main-info d-flex flex-column pt-3 pb-3 text-center">
                        <div class="d-flex mt-3 mb-3">
                            <div class="info-icon col-2">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="info-content col-10 d-flex flex-column">
                                <div class="info-numbers">
                                    <strong>00</strong>
                                </div>
                                <div class="info-title caption">
                                    <small>students</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex mt-3 mb-3">
                            <div class="info-icon col-2">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="info-content col-10 d-flex flex-column">
                                <div class="info-numbers">
                                    <strong>00</strong>
                                </div>
                                <div class="info-title caption">
                                    <small>students</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Third Column -->
                <div class="col-md-4">
                    <div class="main-info d-flex flex-column pt-3 pb-3 text-center">
                        <div class="d-flex mt-3 mb-3">
                            <div class="info-icon col-2">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="info-content col-10 d-flex flex-column">
                                <div class="info-numbers">
                                    <strong>00</strong>
                                </div>
                                <div class="info-title caption">
                                    <small>students</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex mt-3 mb-3">
                            <div class="info-icon col-2">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="info-content col-10 d-flex flex-column">
                                <div class="info-numbers">
                                    <strong>00</strong>
                                </div>
                                <div class="info-title caption">
                                    <small>students</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- section : 2 -->
    <div class="d-flex flex-wrap">
        <div class="col-md-6 p-1">
            <div class="card p-2">
                data
            </div>
        </div>
        <div class="col-md-6 p-1">
            <div class="card p-2">
                data
            </div>
        </div>
    </div>
</div>
<!-- Graph of Info -->
<div class="col-12 col-md-6 mt-3 container-info-graph">
    <div class="card p-3 mb-4 shadow-sm">
        <strong>Departwise Monthly Report</strong>
        <hr>

        <!-- Graph Container -->
        <div class="graph-container">
            <canvas id="comboChart" height="400"></canvas>
        </div>
    </div>
</div>