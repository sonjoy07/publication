<!--add header -->
<?php include_once __DIR__ . '/../header.php'; ?>

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
            <li class="active"><?php echo $Title ?></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box">

                    <?php
                    if ($this->uri->segment(3) === 'add') {
                        ?>
                       
                        //<?php
//                        $message = $this->session->userdata('message');
//                        if (isset($message)) {
//                            echo $message;
//                        }
//                        $this->session->unset_userdata('message');
//                        ?>
                        <!-- begin panel -->
                        <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
                            <div class="panel-heading">
                                <h4 class="panel-title">Salary Table</h4>
                            </div>
                            <div class="panel-body">
                                <form action="<?php echo base_url(); ?>index.php/Salary/save_salary_amount" method="post" class="form-horizontal">
                                  
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Month of Salary</label>
                                        <div class="col-md-9">
                                            <select name="month_salary_payment" class="form-control">
                                                <option value="1">January</option>
                                                <option value="2">February</option>
                                                <option value="3">March</option>
                                                <option value="4">April</option>
                                                <option value="5">May</option>
                                                <option value="6">June</option>
                                                <option value="7">July</option>
                                                <option value="8">August</option>
                                                <option value="9">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Year of Salary</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" placeholder="Year of Salary" name="year_salary_payment" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Issue Salary Payment</label>
                                        <div class="col-md-9">
                                            <input class="form-control datepicker" id="" placeholder="Issue Salary Payment" name="issue_salary_payment" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Date Salary Payment</label>
                                        <div class="col-md-9">
                                            <input class="form-control datepicker" id="" placeholder="Date Salary Payment" name="date_salary_payment" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Amount of Salary</label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" placeholder="Amount of Salary" name="amount_salary_payment" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"></label>
                                        <div class="col-md-9">
                                            <div class="box box-default collapsed-box">
                                                <div class="box-header with-border" style="background: #00A65A;color: #fff;">
                                                    <h3 class="box-title">Add Bonus</h3>
                                                    <div class="box-tools pull-right">
                                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                                    </div><!-- /.box-tools -->
                                                </div><!-- /.box-header -->
                                                <div class="box-body">
                                                    
                                                        <div class="form-group ">
                                                            <label class="col-md-3">Salary Bonus Type</label>
                                                            <div class="col-md-9">
                                                                <select class="form-control select2"style="width:100%;" name="id_salary_bonus_type">
                                                                    <option>Select Bonus Type</option>
                                                                    <?php
                                                                    foreach ($salary_bonus as $bonus) {
                                                                        ?>
                                                                        <option value="<?php echo $bonus->id_salary_bonus_type; ?>"><?php echo $bonus->name_salary_bonus_type; ?></option>
                                                                    <?php }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                        </div><!-- /.form-group -->
                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label">Amount Salary Bonus</label>
                                                            <div class="col-md-9">
                                                                <input type="text" name="amount_salary_bonus" class="form-control" placeholder="Amount Salary Bonus" />
                                                            </div>
                                                        </div>

<!--                                                        <div class="form-group">
                                                            <label class="col-md-3 control-label"></label>
                                                            <div class="col-md-9">
                                                                <button type="submit" class="btn btn-sm btn-success"> Save</button>
                                                            </div>
                                                        </div>-->
                                                    
                                                </div><!-- /.box-body -->
                                                
                                            </div><!-- /.box -->
                                            
                                        </div><!-- /.col -->
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label"></label>
                                        <div class="col-md-9">
                                            <button type="submit" class="btn btn-sm btn-success">Save</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <!-- end panel -->
                        <?php
                    } else {

                        echo $glosary->output;
                    }
                    ?>

                </div>

            </div>


        </div>


     



    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<!-- insert book -->
<script type="text/javascript">
    $(document).ready(function () {
        $(".select2").select2();
    });
</script>


<?php include_once __DIR__ . '/../footer.php'; ?>