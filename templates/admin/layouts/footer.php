</div>
<footer class="main-footer">
   Mọi ý kiến đóng góp vui lòng gửi về địa chỉ Email:  <strong>phongtrothaonguyen@gmail.com </strong>
</footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/js/pages/dashboard.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/js/demo.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<?php
  $body = getBody();
  $module='';
  if(!empty($body['module'])) {
    $module = $body['module'];
  }
?>
<script type="text/javascript">
  let rootUrl = '<?php  echo _WEB_HOST_ROOT; ?>'
</script>


<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/js/custom.js"></script>
<script src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/js/main.js?ver=<?php echo rand(); ?>"></script>
</body>
</html>
