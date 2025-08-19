<?php
$coordinators = $opr->getCoordinators();

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
                    <th>Emp Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Gender</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coordinators as $cordinator) { ?>
                    <tr>
                        <td>
                            <?php if ($cordinator['userimage']) { ?>
                                <img src="<?php echo BASE_URL . 'uploads/auth/' . $auth['userimage']; ?>" alt="User Image"
                                    height="50" width="50" class="rounded-1 object-fit-cover">
                            <?php } else { ?>
                                <img src="<?php echo BASE_URL; ?>uploads/auth/unkown.png" alt="Default Image" height="50"
                                    width="50" class="rounded-1 object-fit-cover">
                            <?php } ?>
                        </td>
                        <td><?php echo $cordinator['employeecode']; ?></td>
                        <td><?php echo $cordinator['firstname'] . ' ' . $cordinator['middlename'] . ' ' . $cordinator['lastname']; ?>
                        </td>
                        <td><?php echo $cordinator['mail']; ?></td>
                        <td><?php echo $cordinator['contact']; ?></td>
                        <td><?php echo $cordinator['gender']; ?></td>
                        <td><?php echo $cordinator['designation']; ?></td>
                        <td><?php echo $cordinator['department']; ?></td>
                        <td>
                            <!-- Example action buttons -->
                            <a href="<?php echo BASE_URL; ?>accountdetails?u=<?php echo base64_encode(base64_encode($cordinator['mail'])) ?>&r=<?php echo $cordinator['authrole'] ?>"
                                class="btn btn-primary p-3"><i class="bi bi-view-list"></i></a>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>