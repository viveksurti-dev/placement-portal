<?php
$students = $opr->getStudets();

?>
<div class="container-fluid ">

    <div class="container-newstudent"></div>
    <div class="mb-3 d-flex flex-nowrap">
        <div class="col-md-2">
            <select class="form-select " onchange="handleExport(this.value)">
                <option selected disabled>-- Download Report --</option>
                <option value="csv"><i class="fas fa-file-csv"></i> 📄 CSV Format</option>
                <option value="excel"><i class="fas fa-file-excel"></i> 📊 Excel Format</option>
            </select>
        </div>
        <div class="ms-2">

        </div>



    </div>

    <div class="table-responsive rounded-3 shadow-sm overflow-hidden">
        <table class="table table-bordered table-striped text-center align-middle mb-0">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Branch</th>
                    <th>CGPA</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student) { ?>
                    <tr>
                        <td>
                            <?php if ($auth['userimage']) { ?>
                                <img src="<?php echo BASE_URL . 'uploads/auth/' . $auth['userimage']; ?>" alt="User Image"
                                    height="50" width="50" class="rounded-1 object-fit-cover">
                            <?php } else { ?>
                                <img src="<?php echo BASE_URL; ?>uploads/auth/unkown.png" alt="Default Image" height="50"
                                    width="50" class="rounded-1 object-fit-cover">
                            <?php } ?>
                        </td>
                        <td><?php echo $student['studentid']; ?></td>
                        <td><?php echo $student['firstname'] . ' ' . $student['middlename'] . ' ' . $student['lastname']; ?>
                        </td>
                        <td><?php echo $student['gender']; ?></td>
                        <td><?php echo $student['mail']; ?></td>
                        <td><?php echo $student['contact']; ?></td>
                        <td><?php echo $student['branch']; ?></td>
                        <td><?php echo $student['cgpa']; ?></td>
                        <td><?php echo $student['status']; ?></td>
                        <td>
                            <!-- Example action buttons -->
                            <a href="view.php?id=<?php echo $student['studentid']; ?>" class="btn btn-primary p-3"><i
                                    class="bi bi-view-list"></i></a>
                            <a href="edit.php?id=<?php echo $student['studentid']; ?>" class="btn  btn-warning p-3"><i
                                    class="bi bi-pencil-square"></i></a>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>