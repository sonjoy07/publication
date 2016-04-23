

<?php include_once 'header.php'; ?>

<!-- Left side column. contains the logo and sidebar -->
<?php include_once 'main_sidebar.php'; ?> <!-- main sidebar area -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper only_print">
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
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
             
             <div class="col-md-12">
              <div class="only_print">
                <?php
                
                    $attributes = array(
                        'clase' => 'form-inline',
                        'method' => 'post');
                    echo form_open('', $attributes)
                    ?>
                    <div class="form-group col-md-4 text-left">
                        <label>Search Report With Date Range:</label>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" name="date_range" value="<?= isset($date_range) ? $date_range : ''; ?>" class="form-control pull-right" id="reservation"  title="This is not a date"/>
                            <br>
                        </div><!-- /.input group -->
                    </div><!-- /.form group -->
                    <button type="submit" name="btn_submit" value="true" class="btn btn-primary"><i class="fa fa-search"></i></button>
                    <?= anchor(current_url() . '/reset_date_range', '<i class="fa fa-refresh"></i>', ' class="btn btn-success"') ?>
                    <?= form_close(); ?>
                <?php  ?>
              </div>
            
        <?php if(isset($today_detail_table)){ ?>    
        <div class="row">           

            <div class="col-md-12" >
                                
                <p class="pull-left" style="margin-left:20px"> <strong>Search Range: (From - To) </strong> <?php echo $date_range; ?></p>
                                
                <input class="only_print pull-right btn btn-primary" type="button"  onClick="window.print()"  value="Print Report"/>
            </div>
            <div class="col-md-12">
                <p class="pull-right">Report Date: <?php echo date('Y-m-d'); ?>  </p>
                <?=$today_detail_table ?>
            </div>
        </div>
            
       <?php  }else{?>
        </div>
            

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>TK <?= $this->Common->taka_format($account_today['todaysell']) ?></h3>
                        <p><strong>Today sell </strong><br>after subtract discount & book return</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><strong>TK <?= $this->Common->taka_format($account_monthly['monthlysell']) ?></strong> </h3>
                        <p><strong>Monthly sell </strong><br>after subtract discount & book return</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><strong>TK <?= $this->Common->taka_format($account_today['today_due']) ?></strong></h3>
                        <p><strong>Today due</strong><br></p>

                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><strong>TK <?= $this->Common->taka_format($account_monthly['monthly_due']) ?></strong></h3>
                        <p><strong>Monthly due</strong><br></p>

                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
        </div><!-- /.row -->
        <!-- Main row -->

        <div class="row">
            <div class="col-md-12">
                <h2 class="content-header"><strong>Payment Information</strong></h2>
            </div>
            <div class="col-md-6">
                <?= $today_monthly_account_detail_table ?>
            </div>
            <div class="col-md-6">
                <?= $total_account_detail_table ?>
            </div>

        </div>



       <?php } ?>



    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- insert book -->


 <?php if(isset($today_detail_table)){ ?>    
        <div class="row report-logo-for-print" style="background:#fff;margin-left:0px">
            <p class="pull-right" style="margin-right:20px">Report Date: <?php echo date('Y-m-d'); ?>  </p>
            <div class="box-header text-center">
                
                
            </div>
            <div class="col-md-12" >
                <h2 class="text-center"><?php echo $this->config->item('SITETITLE') ?></h2>
                <h2 class="text-center page-header">Sales Report</h2>
                
                 </div>
            <div class="col-md-12">
                
                <?=$today_detail_table ?>
            </div>
        </div>
            
       <?php  }?>
<?php include_once 'footer.php'; ?>