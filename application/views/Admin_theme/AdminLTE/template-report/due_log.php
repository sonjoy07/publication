<!--add header -->
<?php include_once __DIR__ . '/../header.php'; ?>

<!-- Left side column. contains the logo and sidebar -->
<?php include_once __DIR__ . '/../main_sidebar.php'; ?> <!-- main sidebar area -->
      <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?=$Title ?>
            <small> <?=$Title ?></small>
          </h1>
            <ol class="breadcrumb">
                <li><a href="<?= $base_url ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                <li><a href="<?= site_url('admin/due_management') ?>"><i class="fa fa-cog"></i> Due Management</a></li>
                <li class="active">Due log</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                <?= anchor(site_url('admin/due_payment_ledger'), "Due Payment Ledger", ' class="btn btn-success"') ?>
                <?= anchor(site_url('admin/due_total_report'), "Total Due Report", ' class="btn btn-primary"') ?>
            </div>
                
                <div class="col-md-12">

                    <div class="box">
                    
                    <?php  

                       echo $glosary->output;
                    ?>
               
                    </div>
                    
                </div>
            </div>
         


          
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- insert book -->



<?php include_once __DIR__ . '/../footer.php'; ?>