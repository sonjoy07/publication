<!--add header -->
<?php include_once 'header.php'; ?>

      <!-- Left side column. contains the logo and sidebar -->
<?php include_once 'main_sidebar.php'; ?> <!-- main sidebar area -->
      <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?=$Title ?>
            <small> <?=$Title ?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=$base_url ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Add New stock</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">

                    <div class="box">
                    
                    <div class="box box-info">
                    <?php 
                     $attributes = array('class' => 'form-horizontal');
                      echo form_open('admin/add_stock/true', $attributes);
                
                      
                    ?>
                
                  <div class="box-body">
                    <div class="form-group">
                      <label class="col-sm-4 control-label pull-left" for="bookname">Book Name:</label>
                      <div class="col-sm-8">
                        <?php echo $bookname;?>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-4 control-label" for="printingpressname">Printing press Name :</label>
                      <div class="col-sm-8">
                        <?php echo $printingpress ?>
                      </div>
                      </div>
                     <div class="form-group">
                      <label class="col-sm-4 control-label" for="inputPassword3">Quantity:</label>
                      <div class="col-sm-8">
                        <?php 
                        $data = array(
                                  'name'        => 'quantity',
                                  'id'          => 'quantity',
                                  'class'        => 'form-control',
                                  'maxlength'   => '100',
                                  'size'        => '50',
                                  'type'        => 'number',
                                  
                                );
                        echo form_input($data) ?>
                      </div>
                    </div>
                  <div class="box-footer">
                    
                    <button class="btn btn-info pull-right" type="submit">Add To Stock</button>
                  </div><!-- /.box-footer -->
               <!--  </form> -->
              </div>
               
                    </div>
                    
                </div>
            </div>
         


          
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

      <!-- insert book -->



<?php include_once 'footer.php'; ?>