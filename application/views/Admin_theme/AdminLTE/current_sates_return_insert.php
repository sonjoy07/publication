<!--add header -->
<?php include_once 'header.php'; ?>

<!-- Left side column. contains the logo and sidebar -->
<?php include_once 'main_sidebar.php'; ?> <!-- main sidebar area -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $Title ?>
            <small> books that are returned to the system</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= $base_url ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><?php echo anchor('admin/return_book_dashboard', '<i class="fa fa-cog"></i> Book Return Dashboard'); ?></li>
            <li><?php echo anchor($crud_ci_link, '<i class="fa fa-cog"></i> ' . $crud_ci_link_text); ?></li>
            <li class="active"><?php echo $Title ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <?php
            $action = current_url();
            $attributes = array(
                'method' => "post"
            );
            echo form_open($action, $attributes)
            ?>

            <div class="col-md-6">

                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Party Name:</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-male"></i>
                                </div>
                                <?php echo $buyer_dropdown; ?>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="col-md-6">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Return Date</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control" data-inputmask="'alias': 'mm/dd/yyyy'" required="true" data-mask="" value="<?php echo date('m/d/Y g:i:s') ?>" name='return_date'>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Select Book Quantity</label>
                            <?php echo $book_selector_table; ?>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div> 
            </div>
            <?php echo form_close(); ?>

        </div>
    </section>




</section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- insert book -->



<?php include_once 'footer.php'; ?>