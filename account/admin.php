<!-- Total Info -->
<div class="container d-flex flex-wrap">
    <div class="col-12 col-md-6 p-2 container-total-info">
        <div class="card py-2 px-3 mb-4 shadow-sm">
            <div class="container">
                <div class="row g-3">
                    <!-- First Column -->
                    <div class="col-12 col-sm-6 col-md-4 border-md-end border-gray">
                        <div class="main-info d-flex flex-column pt-3 pb-3 text-center">
                            <div class="d-flex flex-column flex-sm-row mt-3 mb-3 align-items-center">
                                <div class="info-icon me-sm-2">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-numbers">
                                        <?php $totalStudents = count($opr->totalStudent()) ?>
                                        <strong><?php echo $totalStudents ?></strong>
                                    </div>
                                    <div class="info-title caption">
                                        <small>students</small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row mt-3 mb-3 align-items-center">
                                <div class="info-icon me-sm-2">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="info-content">
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
                    <div class="col-12 col-sm-6 col-md-4 border-md-end border-gray">
                        <div class="main-info d-flex flex-column pt-3 pb-3 text-center">
                            <div class="d-flex flex-column flex-sm-row mt-3 mb-3 align-items-center">
                                <div class="info-icon me-sm-2">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-numbers">
                                        <strong>00</strong>
                                    </div>
                                    <div class="info-title caption">
                                        <small>students</small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row mt-3 mb-3 align-items-center">
                                <div class="info-icon me-sm-2">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="info-content">
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
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="main-info d-flex flex-column pt-3 pb-3 text-center">
                            <div class="d-flex flex-column flex-sm-row mt-3 mb-3 align-items-center">
                                <div class="info-icon me-sm-2">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-numbers">
                                        <strong>00</strong>
                                    </div>
                                    <div class="info-title caption">
                                        <small>students</small>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-sm-row mt-3 mb-3 align-items-center">
                                <div class="info-icon me-sm-2">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="info-content">
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
        <div class="container my-4">
            <div class="row g-2">
                <!-- Card 1: Placement Rate -->
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm p-4 h-100">
                        <h4 class="fw-bold">78%</h4>
                        <h6 class="text-primary">Placement Rate</h6>
                        <p class="text-muted small mb-4">
                            Percentage of students placed out of total registered.
                        </p>
                        <div style="height: 150px;">
                            <canvas id="lineChart"></canvas>
                        </div>
                        <div class="d-flex justify-content-around mt-3">
                            <div class="text-center">
                                <div class="fw-bold">85%</div>
                                <small class="text-muted">2023</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold">80%</div>
                                <small class="text-muted">2022</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold">75%</div>
                                <small class="text-muted">2021</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Companies Visited -->
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm p-4 h-100">
                        <h4 class="fw-bold">65</h4>
                        <h6 class="text-primary">Companies Visited</h6>
                        <p class="text-muted small mb-4">
                            Number of companies participating in campus recruitment.
                        </p>
                        <div style="height: 150px;">
                            <canvas id="barChart"></canvas>
                        </div>
                        <div class="d-flex justify-content-around mt-3">
                            <div class="text-center">
                                <div class="fw-bold">18</div>
                                <small class="text-muted">May</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold">22</div>
                                <small class="text-muted">June</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold">25</div>
                                <small class="text-muted">July</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Line chart (Placement Rate Trend)
                new Chart(document.getElementById('lineChart'), {
                    type: 'line',
                    data: {
                        labels: ["", "", "", "", "", ""],
                        datasets: [{
                            data: [72, 75, 78, 80, 82, 78],
                            borderColor: '#6c63ff',
                            backgroundColor: 'rgba(108, 99, 255, 0.2)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                display: false
                            },
                            y: {
                                display: false
                            }
                        }
                    }
                });

                // Bar chart (Companies Visited per Month)
                new Chart(document.getElementById('barChart'), {
                    type: 'bar',
                    data: {
                        labels: ["", "", "", "", "", "", "", "", ""],
                        datasets: [{
                            data: [10, 12, 15, 18, 20, 22, 23, 25, 24],
                            backgroundColor: '#6c63ff',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                display: false
                            },
                            y: {
                                display: false
                            }
                        }
                    }
                });
            });
        </script>


    </div>

    <!-- Graph Section -->
    <div class="col-12 col-md-6 p-2">
        <div class="card shadow-sm p-3" style="height: 618px;">
            <strong class="mb-2">Monthly Placement Report</strong>
            <hr>
            <div style="height: 100%; position: relative;">
                <canvas id="comboChart"></canvas>
            </div>
        </div>
    </div>



    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('comboChart').getContext('2d');
            var myComboChart = new Chart(ctx, {
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'], // months
                    datasets: [{
                            type: 'bar',
                            label: 'Students Placed',
                            data: [35, 45, 28, 41, 60, 47, 54], // total students placed
                            backgroundColor: '#6b63ff8d',
                            borderColor: '#6c63ff',
                            borderWidth: 1,
                            borderRadius: 5,
                            yAxisID: 'y'
                        },
                        {
                            type: 'line',
                            label: 'Average Package (LPA)',
                            data: [4.2, 4.5, 4.0, 4.8, 5.0, 4.7, 5.2], // average package
                            borderColor: 'rgba(255, 0, 34, 1)',
                            backgroundColor: 'rgba(255, 0, 34, 0.1)',
                            fill: false,
                            tension: 0.1,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Students Placed'
                            }
                        },
                        y1: {
                            beginAtZero: false,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Average Package (LPA)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        });
    </script>

    <div class="row mb-4">
        <!-- Pie Chart -->
        <div class="col-md-6">
            <div class="card shadow-sm p-3 h-100">
                <h6 class="mb-1">Department Placement Ratio</h6>
                <p class="text-muted small mb-3">Percentage of students placed from each department</p>
                <canvas id="placementPie" height="300px"></canvas>
            </div>
        </div>
        <!-- Summary Cards -->
        <div class="col-md-6">
            <div class="row g-3">
                <div class="col-6">
                    <div class="card shadow-sm p-3 text-center" style="border-top: 4px solid #6c63ff;">
                        <h6 class="mb-2">Total Students Placed</h6>
                        <h3 class="fw-bold text-success">342</h3>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card shadow-sm p-3 text-center" style="border-top: 4px solid #6c63ff;">
                        <h6 class="mb-2">Total Companies</h6>
                        <h3 class="fw-bold text-primary">120</h3>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card shadow-sm p-3 text-center" style="border-top: 4px solid #6c63ff;">
                        <h6 class="mb-2">Highest Package</h6>
                        <h3 class="fw-bold text-warning">₹18 LPA</h3>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card shadow-sm p-3 text-center" style="border-top: 4px solid #6c63ff;">
                        <h6 class="mb-2">Total Offers</h6>
                        <h3 class="fw-bold text-info">540</h3>
                    </div>
                </div>
            </div>
            <div class="row ">
                <!-- Feeds List -->
                <div class="col-md-12 mt-3">
                    <div class="card shadow-sm p-3 h-100">
                        <h6 class="mb-3">Latest Updates</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>New company registered – Infosys</span>
                                <small class="text-muted">2 mins ago</small>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Placement drive scheduled – TCS</span>
                                <small class="text-muted">10 mins ago</small>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Highest package offered – ₹18 LPA</span>
                                <small class="text-muted">1 hr ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>



    <script>
        const ctxPlacement = document.getElementById('placementPie').getContext('2d');
        new Chart(ctxPlacement, {
            type: 'pie',
            data: {
                labels: ['CSE', 'IT', 'ECE', 'EEE', 'MECH'],
                datasets: [{
                    data: [40, 25, 15, 10, 10],
                    backgroundColor: [
                        'rgba(108, 99, 255, 1)', // Darkest
                        'rgba(108, 99, 255, 0.85)',
                        'rgba(108, 99, 255, 0.7)',
                        'rgba(108, 99, 255, 0.55)',
                        'rgba(108, 99, 255, 0.4)' // Lightest
                    ]
                }]

            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    </script>
</div>