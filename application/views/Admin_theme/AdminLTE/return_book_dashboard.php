<!--add header -->
<?php include_once 'header.php';  ?>

<!-- Left side column. contains the logo and sidebar -->
<?php include_once 'main_sidebar.php'; ?> <!-- main sidebar area -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $Title ?>
            <small>Manage <?= $Title ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= $base_url ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active"><?= $Title ?></li>
        </ol>
       <div class="row">           

            <div class="col-md-12" style="margin-top:100px">
                
                
                <!-- small box -->
                <div class="box">
                    <div class="inner">
                        <div class="header" style="padding:50px 20px">
                            <?php echo anchor('admin/book_return','<span class="btn btn-primary" style="width:300px">Receive Returned Book</span>'); ?>
                            <br><br>
                             <?php echo anchor('admin/send_book_rebind', '<span class="btn btn-primary" style="width:300px">Send Book to Re-bind</span>'); ?>
                    
                        </div>
                       </div>
                    
                
            </div><!-- ./col -->
  
           
            </div>
           
           <div class="col-md-12">
    
                <div class="box only_print">
                            <div class="box-body">
                                <p>Total Returned Book: <?php echo $total_book_returned ?> </p>
                                <br>
                                <p>Total Send Book to Re-bind:  <?php echo $total_book_send ?></p>
                                <br>
                                <p>Remaining Returned Book in store:  <?php echo $remining_book ?></p>
                            </div>
                        </div>
               <div class="box">
                   <h3>Full Report</h3><hr>
                        <input class="only_print pull-right btn-primary" style="padding:5px;margin:5px" type="button"  onClick="window.print()"  value="Print This Page"/>
                   <?php echo $report ?>
               </div>
           </div>
           
       </div>









    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

               <div class="box-body report-logo-for-print" style="background:#fff;margin: 5px">
                        <div class="row">
                   <h3>Full Report</h3><hr>
                   <?php echo $report ?>
               </div>
               </div>
<?php include_once 'footer.php'; ?>
