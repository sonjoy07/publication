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
            <small> <?= $Title ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= $base_url ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Cost Management</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content only_print">
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
        
        
        <?php if(isset($main_content)){ ?>
        
        <div class="col-md-12" >
                                
                <p class="pull-left" style="margin-left:20px"> <strong>Search Range: (From - To) </strong> <?php echo $date_range; ?></p>
                                
                <input class="only_print pull-right btn btn-primary" type="button"  onClick="window.print()"  value="Print Report"/>
            </div>
        
        <?php
             echo $main_content;
        }else{ 
        ?>
        
            <div class="row">
             <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>TK <?php echo $today_office_cost ?></h3>
                        <p><strong>Today Office Cost </strong></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><strong>TK <?php echo $monthly_office_cost ?></strong> </h3>
                        <p><strong>This Month Office Cost </strong></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
             <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3><strong>TK <?php echo $previous_month_office_cost ?></strong> </h3>
                        <p><strong>Previous Month Office Cost </strong></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="#" class="small-box-footer"><i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div><!-- ./col -->
        </div>
        
        
        <div class="row">
            <div class="col-md-12">

                <div class="box">

                    <?php
                    
                    
                   
                        echo $glosary->output;
                    }
                        
                    
                    ?>

                </div>

            </div>
        </div>
        
    



    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- insert book -->
<div class="row report-logo-for-print">
<?php if(isset($main_content)){ ?>
        
        <div class="col-md-12" >
                <h2 class="text-center page-header">Office Cost Report</h2>
                
                <p class="pull-left" style="margin-left:20px"> <strong>Search Range: (From - To) </strong> <?php echo $date_range; ?></p>
                  <p class="pull-right" style="margin-right:20px">Report Date: <?php echo date('Y-m-d'); ?>  </p>               
                <input class="only_print pull-right btn btn-primary" type="button"  onClick="window.print()"  value="Print Report"/>
            </div>
        
        <?php
             echo $main_content;
        }
        ?>
</div>
<?php include_once 'footer.php'; ?>