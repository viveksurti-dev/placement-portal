<?php
$companies = $opr->getCompanies();

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
                    <th>Company Name</th>
                    <th>HR Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Industy</th>
                    <th>Website</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company) { ?>
                    <tr>
                        <td>
                            <?php if ($company['userimage']) { ?>
                                <img src="<?php echo BASE_URL . 'uploads/auth/' . $auth['userimage']; ?>" alt="User Image"
                                    height="50" width="50" class="rounded-1 object-fit-cover">
                            <?php } else { ?>
                                <img src="<?php echo BASE_URL; ?>uploads/auth/unkown.png" alt="Default Image" height="50"
                                    width="50" class="rounded-1 object-fit-cover">
                            <?php } ?>
                        </td>
                        <td><?php echo $company['cname']; ?></td>
                        <td><?php echo $company['firstname'] . ' ' . $company['middlename'] . ' ' . $company['lastname']; ?>
                        </td>
                        <td><?php echo $company['mail']; ?></td>
                        <td><?php echo $company['contact']; ?></td>
                        <td><?php echo $company['ctype']; ?></td>
                        <td><?php echo $company['ctype']; ?></td>
                        <td><?php echo $company['status']; ?></td>
                        <td>
                            <!-- Example action buttons -->
                            <a href="<?php echo BASE_URL; ?>accountdetails?u=<?php echo base64_encode(base64_encode($company['mail'])) ?>&r=<?php echo $company['authrole'] ?>"
                                class="btn btn-primary p-3"><i class="bi bi-view-list"></i></a>
                            <a href="edit.php?id=<?php echo $company['cid']; ?>" class="btn  btn-warning p-3"><i
                                    class="bi bi-pencil-square"></i></a>

                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>