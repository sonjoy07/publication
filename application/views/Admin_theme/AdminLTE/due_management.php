<!--add header -->
<?php include_once 'header.php'; ?>

<!-- Left side column. contains the logo and sidebar -->
<?php include_once 'main_sidebar.php'; ?> <!-- main sidebar area -->
<style>
    #sub_total_field_box .form-display-as-box {
        display: none;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $Title ?>
            <small><?= $Title ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= $base_url ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><?= $Title ?></li>
        </ol>
    </section>



    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php
                if (!$date_filter) {
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
                    <?= anchor(current_url() . '/reset_date_range', '<i class="fa fa-refresh"></i>', ' class="btn btn-success"') ?>
                    <?= form_close(); ?>
                <?php } ?>
            </div>
            <div class="col-md-12">
                <?= anchor(site_url('admin/due_log'), "Due Log", ' class="btn btn-primary"') ?>
                <?= anchor(site_url('admin/due_payment_ledger'), "Due Payment Ledger", ' class="btn btn-success"') ?>
                <?= anchor(site_url('admin/due_total_report'), "Total Due Report", ' class="btn btn-info"') ?>
            </div>
            <div class="col-md-12">

                <div class="box">

                    <?php
                    echo $glosary->output;
                    ?>

                </div>

            </div>

            <?php if (isset($total_due_section)) { ?>
                <div class="col-md-8 form-inline">

                    <div class="form-group">
                        <label class="text-left">Party Name:</label>
                        <div class="input-group">
                            <?= $contact_dropdown ?>
                        </div><!-- /.input group -->
                    </div><!-- /.form group -->

                    <div class="form-group">
                        <label>Total Due:</label>
                        <div class="input-group" id="total_due">
                        </div><!-- /.input group -->
                    </div><!-- /.form group123 -->
                </div>
            <?php } ?>
            <?php if ($date_filter == "edit") { ?>
                <div class="col-md-8">
                    <?= anchor("admin/memo_management", "Go To the Memo Management", "class='btn btn-primary' ") ?>
                </div>
            <?php } ?>
        </div>


    </section><!-- /.content -->
    <script type="text/javascript">
    </script>
</div><!-- /.content-wrapper -->

<?php include_once 'footer.php'; ?>
