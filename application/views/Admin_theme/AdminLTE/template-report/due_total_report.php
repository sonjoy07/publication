<!--add header -->
<?php include_once __DIR__ . '/../header.php'; ?>

<!-- Left side column. contains the logo and sidebar -->
<?php include_once __DIR__ . '/../main_sidebar.php'; ?> <!-- main sidebar area -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $Title ?>
            <small> <?= $subTitle ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= $base_url ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="<?= site_url('admin/due_management') ?>"><i class="fa fa-cog"></i> Due Management</a></li>
            <li class="active">Total summary</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php
                $attributes = array(
                    'clase' => 'form-inline',
                    'method' => 'get');
                echo form_open('', $attributes)
                ?>
                <div class="form-group col-md-3 text-left">
                    <label>Search with Date Range:</label>
                </div>
                <div class="form-group col-md-7">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="date_range" value="<?= isset($date_range) ? $date_range : ''; ?>" class="form-control pull-right" id="reservation"  title="This is not a date"/>
                    </div><!-- /.input group -->
                </div><!-- /.form group -->
                <button type="submit" name="btn_submit" value="true" class="btn btn-primary"><i class="fa fa-search"></i></button>
                <?= anchor(current_url(), '<i class="fa fa-refresh"></i>', ' class="btn btn-success"') ?>
                <?= form_close(); ?>
            </div>
            <div class="col-md-12">
                <?= anchor(site_url('admin/due_log'), "Due Log", ' class="btn btn-success"') ?>
                <?= anchor(site_url('admin/due_payment_ledger'), "Due Payment Ledger", ' class="btn btn-primary"') ?>
                <?= anchor(site_url('admin/due_total_report?date_range=today'), "Today Report", ' class="btn btn-default"') ?>
                <?= anchor(site_url('admin/due_total_report?date_range=this_month'), "Monthly Report", ' class="btn btn-default"') ?>
            </div>
            <div class="col-md-12">
                <?php
                echo $due_remaining_table;
                ?>
            </div>
        </div>




    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- insert book -->



<?php include_once __DIR__ . '/../footer.php'; ?>