<!--add header -->
<?php include_once 'header.php'; ?>

      <!-- Left side column. contains the logo and sidebar -->
<?php include_once 'main_sidebar.php'; ?> <!-- main sidebar area -->
      <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper" style="min-height: 650px">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?=$Title ?>
            <small> <?=$Title ?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=$base_url ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Stock Transfer Log</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content only_print">
            <div class="row">
                <div class="col-md-12">
                    
               

                    <div class="box">
                        <br>
                        
                         <div class="only_print">
                              <?php
                
                    $attributes = array(
                        'clase' => 'form-inline',
                        'method' => 'post');
                    echo form_open('', $attributes);
                    ?>
                             <div class="col-md-3">
                            <label>Select Book Name :</label>
                            </div>
                            <div class="col-md-3">
                                <?php echo $book_dropdown; ?>
                            </div>
                           
                            <div class="col-md-3">
                                <label>From :</label>
                            </div>
                            <div class="col-md-3">
                                <?php echo $transfer_log_From_dropdown;?>
                            </div>
                             
                            <div class="clearfix"></div>
                            <br>
                            <div class="col-md-3">
                                <label>To :</label>
                            </div>
                            <div class="col-md-3">
                                <?php echo $transfer_log_to_dropdown; ?>
                            </div>
                            <div class="col-md-3">
                                <label>Date:</label>
                            </div>
                            <div class="col-md-3">
                                
                          <input type="text" name="date_range" value="<?= isset($date_range) ? $date_range : ''; ?>" class="form-control pull-right" id="reservation"  title="This is not a date"/>
                                        
              
                            </div>
                            <br><br>
                            <div class="col-md-12 pull-right">
                                <?php if(isset($date_range)){ ?>
                                <p class="pull-left" style="margin-left:20px"> <strong>Search Range: (From - To) </strong> <?php echo $date_range; ?></p>
                                <?php } ?>
                                <input type="submit" name="submit_single" class="btn btn-primary pull-right" value="Search for all transactions">
                                
                                <input style="margin-right:20px" type="submit" name="submit_sum" class="btn btn-primary pull-right" value="Search for total quantity">
                            </div>
                            
                            <br>
                            <?php echo form_close() ?>
                            
                            
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-12" style="padding:25px">
                               <?php 
                               if(isset($transfer_log_table)){
                                   echo '<input class="only_print pull-right btn btn-primary" type="button"  onClick="window.print()"  value="Print Report"/><br><br>';
                                   echo '<h2 class="page-header text-center">Stock Transfer Log Report</h2>';
                                   echo $transfer_log_table ;
                               
                               }
                             ?>
                                
                                </div>
                            </div>
                         </div>
                        <br>
                        <?php
                        //if(isset($result_table)){
                            ?>
                        
                        <!--<div class="box">-->
                            <?php //echo $result_table; ?>
<!--                        </div>-->
                        <?php //} ?>
                    <?php  
                    if(!isset($transfer_log_table)){
                       echo $glosary->output;
                    }
                    ?>
               
                    </div>
                    
                </div>
            </div>
         


          
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- insert book -->
<div class="box-body report-logo-for-print" style="background:#fff">
      <div class="row">
          <p class="text-center"><strong>Stock Transfer Log</strong></p>
          <p class="pull-left" style="margin-left:20px"> <strong>Search Range: (From - To) </strong> <?php echo $date_range; ?></p>
                                
          <p class="pull-right" style="margin-right:20px">Report Date: <?php echo date('Y-m-d'); ?></p>
          <div class="col-md-12">
              
           <?php 
                  if(isset($transfer_log_table)){
                    //echo '<div class="box-header "><h2 class="text-center">Stock Transfer Log Report</h2></div>';
                    echo $transfer_log_table ;
                               
                       }
                 ?>
      </div>
</div>
</div>
<?php if(isset($script)){echo $script; }?>
<?php include_once 'footer.php'; ?>
