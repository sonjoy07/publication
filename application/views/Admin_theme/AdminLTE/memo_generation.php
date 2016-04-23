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
       
                
<!-- /.row -->
          
      <div class="row">
        <div class="col-md-12">

        <div class="row">
        <div class="col-md-offset-3 col-md-6">
        <div class="box">
        <div class="box-body">
        
            <div class="tab-content">


                
                  <div role="tabpanel" class="tab-pane fade in active" id="step1" style="height:400px;">
                    <div class="box-body">
                      <form role="form-inline">
                                
                                        <div class="form-group">
                                          <label>Party Name:</label>
                                          <select class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option selected="selected">Select party name</option>
                                            <option>Alaska</option>
                                            <option>California</option>
                                            <option>Delaware</option>
                                            <option>Tennessee</option>
                                            <option>Texas</option>
                                            <option>Washington</option>
                                          </select>
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
                                                <td width="50px"><input type="checkbox" value=""></td>
                                                <td>book 1</td>
                                                <td width="100px"><input type="number" class="form-control"></td>
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
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="step2" style="height:400px;">
                    <h1 class="page-header text-center">Price Calculation</h1>
                    <table class="table table-border table-striped">
                      <tr>
                        <th>Sub Total:</th>
                        <td>900</td>
                      </tr>
                      <tr>
                        <th>Discount:</th>
                        <td><input type="text" value="0"></td>
                      </tr>
                      <tr>
                        <th>Previous Due:</th>
                        <td><input type="text" value="50"></td>
                      </tr>
                      
                      <tr class="info">
                        <th>Total Price:</th>
                        <td>950</td>
                      </tr>

                    </table>
                      
                   
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="step3" style="height:400px;">
                    <h1 class="page-header text-center">Payment Method</h1>
                    <table class="table table-border">
                      <tr>
                        <th>cash</th>
                        <th>Bank Due</th>
                        <th>Due</th>
                      </tr>
                      <tr>
                        <td>
                          <input type="text" class="form-control">
                        </td>
                        <td>
                          <input type="text" class="form-control">
                        </td>
                        <td>
                          <input type="text" class="form-control">
                        </td>
                      </tr>
                    </table>

                    <div class="text-center">
                           
                           <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#myModal">
                            Generate Cash Memo
                            </button> 
                   </div>
                   </form>
                  </div>
             


                <ul class="nav nav-tabs" role="tablist">
                  <li role="presentation" class="active"><a href="#step1" class="btn btn-primary" aria-controls="home" role="tab" data-toggle="tab">Step 1</a></li>
                  <li role="presentation"><a href="#step2" aria-controls="profile" class="btn btn-primary" role="tab" data-toggle="tab">Step 2</a></li>
                  <li role="presentation"><a href="#step3" aria-controls="messages"  class="btn btn-primary" role="tab" data-toggle="tab">Step 3</a></li>
                </ul>
        </div>
        </div>
        </div>

        </div>
        </div>
      
      </div>
      </div>      
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->


      <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Cash Memo</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-8">
                    <img src="http://www.aosware.net/images/dfs/full/2591.jpg" alt="" class="img-responsive">
                  </div>
                  <div class="col-md-4 text-center">
                    <a href="" class="btn btn-info btn-block">Print</a><br><br>
                    <a href="" class="btn btn-info btn-block">Download as pdf</a>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                
              </div>
            </div>
          </div>
        </div>
  
<?php include_once 'footer.php'; ?>