
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.2.0
    </div>
    <strong>Copyright &copy; 2014-<?php echo date("Y"); ?>
        <a href="<?= $this->config->item('SITE')['website'] ?>">
            <?= $this->config->item('SITE')['name'] ?>
        </a>.
    </strong>
    All rights reserved.Developed by <a href="<?= $this->config->item('DEVELOPER')['website'] ?>" target="_blank">
        <?= $this->config->item('DEVELOPER')['name'] ?>
    </a>.
</footer>

<!-- Control Sidebar -->
<?php include_once 'right_sidebar.php' ?>

<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
</div><!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
<script src="<?php echo $theme_asset_url ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<?php if (isset($glosary)): ?>
    <!-- glosary crud js file -->
    <?php foreach ($glosary->js_files as $file): ?>

        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<script type="text/javascript">
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo $theme_asset_url ?>bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="<?php echo $theme_asset_url ?>plugins/morris/morris.min.js" type="text/javascript"></script>
<!-- Sparkline -->
<script src="<?php echo $theme_asset_url ?>plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- jvectormap -->
<script src="<?php echo $theme_asset_url ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
<script src="<?php echo $theme_asset_url ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
<script src="<?= base_url() . $this->config->item('ASSET_FOLDER') ?>js/custom.js" type="text/javascript"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo $theme_asset_url ?>plugins/knob/jquery.knob.js" type="text/javascript"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script>
<script src="<?php echo $theme_asset_url ?>plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!-- AdminLTE App -->
<script src="<?php echo $theme_asset_url ?>dist/js/app.min.js" type="text/javascript"></script>
<!-- datepicker -->
<script src="<?php echo $theme_asset_url ?>plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $theme_asset_url ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
<!-- Slimscroll -->
<script src="<?php echo $theme_asset_url ?>plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src="<?php echo $theme_asset_url ?>plugins/fastclick/fastclick.min.js" type="text/javascript"></script>


<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo $theme_asset_url ?>dist/js/pages/dashboard.js" type="text/javascript"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $theme_asset_url ?>dist/js/demo.js" type="text/javascript"></script>

<!-- For Add memo form validation -->
<script src="<?= base_url() . $this->config->item('ASSET_FOLDER') ?>js/memo-validation.js" type="text/javascript"></script>
<script src="<?php echo $theme_asset_url ?>plugins/select2/select2.full.min.js" type="text/javascript"></script>


<script type="text/javascript">
    //Date range picker
    $('#reservation').daterangepicker();
    //Initialize Select2 Elements
    $(".select2").select2();
    // Datepicker
    $('.datepicker').datepicker();
    /*     
 * Add collapse and remove events to boxes
 */
$("[data-widget='collapse']").click(function(e) {
    e.preventdefault;
    //Find the box parent        
    var box = $(this).parents(".box").first();
    //Find the body and the footer
    var bf = box.find(".box-body");
    if (!box.hasClass("collapsed-box")) {
        box.addClass("collapsed-box");
        bf.slideUp();
    } else {
        box.removeClass("collapsed-box");
        bf.slideDown();
    }
});

//        due management Property
    $('[name="buyer_id"]').change(function () {
        var buyer_id = $('[name="buyer_id"]').val();
        alert("<?php echo site_url('admin/total_due/') ?>/" + buyer_id);
//        $.ajax({
//            url: "<?php echo site_url('admin/total_due/') ?>/" + buyer_id,
//            cache: false
//        }).done(function (data) {
//                    $("#total_due").html(data);
//                });
    });
</script>
<?php if (isset($scriptInline)) echo $scriptInline; ?>

<?php if(isset($script)){echo $script; }?>


</body>
</html>
