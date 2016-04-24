<?php include('_header.php'); ?>

<div class="am-tabs" data-am-tabs="" style=" padding-top: 50px;margin-top:.7em;background:#fff;">
  <div data-am-widget="slider" class="am-slider am-slider-b2" data-am-slider='{&quot;controlNav&quot;:false}' >
  <ul class="am-slides">
    <?php if (!empty($t_images)) : ?>
    <?php foreach ($t_images as $key => $value) : ?>
      <li>
          <img src="<?php echo $value['url']; ?>">
      </li>
    <?php endforeach; ?>
    <?php endif;?>
  </ul>
  </div>
  <time><?php echo date('Y-m-d', $create_time); ?></time>
  <table border="0" cellspacing="0" cellpadding="0" class="am-more">
    <?php foreach ($template as $key => $value) : ?>
      <tr>
      <th<?php if ($key == 0) echo ' width="20%"'; ?>><?php echo $value['title']; ?></th>
      <td><?php echo $value['value']; ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>

<?php include('_footer.php'); ?>