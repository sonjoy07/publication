<!--add header -->
<?php include_once 'header.php'; ?>
<!-- Left side column. contains the logo and sidebar -->
<?php include_once 'main_sidebar.php'; ?> <!-- main sidebar area -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper only_print" style="min-height: 600px">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= $Title ?>
            <small><?= $Title ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= $base_url ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Stock Management</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box">

                    <div class="box-header with-border">
                        <h3 class="box-title">Manage Stock </h3>
                        <p class="pull-right">Report Date: <?php echo date('Y-m-d'); ?></p>
                        
                    </div><!-- /.box-header -->
                    <div class="pull-right">
                        <input class="only_print pull-right btn-info" style="padding:5px;margin:5px" type="button"  onClick="window.print()"  value="Print This Page"/>

                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">

                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="5" class="text-center info">Printing press</th>
                                    </tr>
                                </table>

                                <?= $printing_table ?>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr>

                                        <th colspan="5" class="text-center warning">Binding Store</th>
                                    </tr>
                                </table>

                                <?= $binding_table ?>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr>

                                        <th colspan="5" class="text-center success">Sales Store</th>
                                    </tr>
                                </table>

                                <?= $store_table ?>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <!-- /.row -->

        <!-- modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Transfer Book Amount</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form-inline" action="<?= site_url('admin/transfer_stock'); ?>" method="post">
                            <input type="hidden" name="stock_id_from" />
                            <div class="box-body">
                                <div class="row">

                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>To:</label>
<!--                                            <select class="form-control select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                                <option selected="selected">Select</option>
                                                <option>Printing Press</option>
                                                <option>Binding</option>
                                                <option>Sales</option>

                                            </select>-->
                                            <?= $transfer_from_contact_dropdown ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="amount">Amount:</label>
                                            <input type="number" id="amount" min='1' max="10" name='Quantity' class="form-control">
                                        </div>


                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label for="address">Comment</label>
                                            <textarea name="" id="address" cols="30" rows="4" class="form-control"></textarea>

                                        </div>

                                    </div>
                                </div>






                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button class="btn btn-primary pull-right" type="submit">Transfer</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
        <script>
            jQuery('[data-StockId]').click(function () {
                var stock_id = $(this).attr('data-StockId');
                //console.log(stock_id);
                jQuery('[name="stock_id_from"]').val(stock_id);
            });
        </script>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<div class="box-body report-logo-for-print" style="background:#fff">
                        <div class="row">
                            <p class="pull-right" style="margin-right:20px">Report Date: <?php echo date('Y-m-d'); ?></p>
                            <div class="col-md-12">
                                 
                             <div class="box-header ">
                                          <h2 class="text-center"><?php echo $this->config->item('SITETITLE') ?></h2>
                                           
                                       </div>

                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="5" class="text-center info">Printing press</th>
                                    </tr>
                                </table>

                                <?= $printing_table ?>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr>

                                        <th colspan="5" class="text-center warning">Binding Store</th>
                                    </tr>
                                </table>

                                <?= $binding_table ?>
                            </div>
                            <div class="col-md-12">
                                <table class="table table-bordered">
                                    <tr>

                                        <th colspan="5" class="text-center success">Sales Store</th>
                                    </tr>
                                </table>

                                <?= $store_table ?>
                            </div>
                        </div>

                    </div>
<?php include_once 'footer.php'; ?>
