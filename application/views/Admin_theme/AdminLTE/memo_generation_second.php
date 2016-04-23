<!--add header -->
<?php include_once 'header.php'; ?>

      <!-- Left side column. contains the logo and sidebar -->
<?php include_once 'main_sidebar.php'; ?> <!-- main sidebar area -->
      <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            MEMO GENERATION
            <small>Generate Memos</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?=$base_url ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Memo generation</li>
          </ol>
        </section>
        
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                <div class="box">
                  <form role="form-inline">
                    <div class="box-body">
                        
                                <div class="form-group">
                                  <label for="party_name">Party Name:</label>
                                  <input type="text" placeholder="Party name" id="party_name" class="form-control">
                                </div>

                                  <div class="form-group" >
                                    
                                  <label>Book Name:</label>
                                  <table class="table table-border table striped">
                                    <tr>
                                        <th class="success">#</th>
                                        <th class="success">Book Name</th>
                                        <th class="success">Quantity</th>
                                      </tr>
                                  </table>
                                  <div style="overflow-y:scroll;max-height:200px;">
                                    <table class="table table-bordered table-striped">
                                      


                                      <tr>
                                        <td><input type="checkbox" value=""></td>
                                        <td>book 1</td>
                                        <td><input type="number" class="form-control"></td>
                                      </tr>

                                      <tr>
                                        <td><input type="checkbox" value=""></td>
                                        <td>book 1</td>
                                        <td><input type="number" class="form-control"></td>
                                      </tr>

                                      <tr>
                                        <td><input type="checkbox" value=""></td>
                                        <td>book 1</td>
                                        <td><input type="number" class="form-control"></td>
                                      </tr>

                                      <tr>
                                        <td><input type="checkbox" value=""></td>
                                        <td>book 1</td>
                                        <td><input type="number" class="form-control"></td>
                                      </tr>

                                      <tr>
                                        <td><input type="checkbox" value=""></td>
                                        <td>book 1</td>
                                        <td><input type="number" class="form-control"></td>
                                      </tr>

                                      <tr>
                                        <td><input type="checkbox" value=""></td>
                                        <td>book 1</td>
                                        <td><input type="number" class="form-control"></td>
                                      </tr>
                                    </table>
                                    </div>
                                  </div>
                                
                                  
     
                       </div><!-- /.box-body -->

                  <div class="box-footer">
                    <a class="btn btn-primary pull-right" type="submit">Next</a>
                  </div>
                </form>
                    
                </div>
            </div>
<!-- /.row -->
          


        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

<?php include_once 'footer.php'; ?>