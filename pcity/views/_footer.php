<!--[if (gte IE 9)|!(IE)]><!-->
<script src="/assets/wx/js/jquery.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="assets/js/amazeui.ie8polyfill.min.js"></script>
<![endif]-->
<script>
  $(function() {
  $('#doc-prompt-toggle').on('click', function() {
    $('#my-prompt').modal({
      relatedTarget: this,
      onConfirm: function(str) {
        false
        // alert('false ' + e.data || '')
      },
      onCancel: function(e) {
        false
        // alert('false ');
      }
    });
  });
});
</script>
<script src="/assets/wx/js/amazeui.min.js"></script>
</body>
</html>