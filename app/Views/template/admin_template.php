<?php include 'header.php'; ?>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <?php include 'menu.php'; ?>

    <div class="content-wrapper">
      <?= $this->renderSection('content'); ?>
    </div>

    <footer class="main-footer">
      <strong>Created By:</strong>
      Rovyn F. Garduque
    </footer>

  </div>
  <!-- ./wrapper -->
  <?php include 'scripts.php'; ?>

  <?= $this->renderSection('pagescripts'); ?>
</body>

</html>