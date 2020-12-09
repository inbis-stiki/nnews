<!DOCTYPE html>
<html>

<head>
<title><?= $page_title ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?= base_url('assets/css/font-awesome-4.7.0/css/font-awesome.min.css') ?>" type="text/css">
<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/jquery-ui.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap-tokenfield.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/simple-sidebar.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/dropzone.css') ?>">
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery-ui.min.js') ?>"></script>
<script src="<?= base_url('assets/js/popper.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/js/loader.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap-tokenfield.js') ?>"></script>
<script src="https://cdn.tiny.cloud/1/i8b1vm1qkrytw968a78y2mz1mdfk51re4smkal9m11h4012x/tinymce/5/tinymce.min.js"></script>
<script src="<?= base_url('assets/js/dropzone.js') ?>"></script>
</head>
<body>
	<div class="d-flex" id="wrapper">
		<nav class="bg-light border-right" id="sidebar-wrapper">
			<div class="sidebar-heading">DigiMagz PTPN X </div>
			<div class="list-group list-group-flush">
				<a href="<?= base_url(); ?>" class="list-group-item list-group-item-action bg-light">Dashboard</a>
				<a href="<?= base_url('news'); ?>" class="list-group-item list-group-item-action bg-light">Berita</a>
				<a href="<?= base_url('video'); ?>" class="list-group-item list-group-item-action bg-light">Video</a>
        <a href="<?= base_url('gallery'); ?>" class="list-group-item list-group-item-action bg-light">Galeri</a>
        <?php if ($this->session->userdata('role') == 'admin'){ ?>
          <a href="<?= base_url('user'); ?>" class="list-group-item list-group-item-action bg-light">Manajemen Mobile User</a>
          <a href="<?= base_url('backend'); ?>" class="list-group-item list-group-item-action bg-light">Manajemen Backend User</a>
          <a href="<?= base_url('comment'); ?>" class="list-group-item list-group-item-action bg-light">Manajemen Komentar</a>
        <?php } ?>
				<a href="<?= base_url('coverstory'); ?>" class="list-group-item list-group-item-action bg-light">Cover Story</a>
				<a href="<?= base_url('emagz'); ?>" class="list-group-item list-group-item-action bg-light">E-Magazine</a>
				<a href="<?= base_url('tags'); ?>" class="list-group-item list-group-item-action bg-light">Tag</a>
			</div>
		</nav>
		<!-- /#sidebar-wrapper -->
		<!-- Page Content -->
		<div id="page-content-wrapper">
			<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
				<button class="btn btn-light" id="menu-toggle"><i class="fa fa-reorder"></i></button>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item">Halo, <?= $this->session->userdata('nama') ?><br></li>
					</ul>
					<ul class="navbar-nav ml-auto mt-2 mt-lg-0">
						<li class="nav-item"> <a class="nav-link" href="<?= base_url('login/logout'); ?>">Keluar<br></a> </li>
					</ul>
				</div>
			</nav>
			<div class="container-fluid">
				<?php $this->load->view($main_content); ?>
			</div>
		</div>	
	</div>
</body>

<script src="https://www.gstatic.com/firebasejs/7.2.1/firebase-app.js"></script>
<script>
var firebaseConfig = {
  apiKey: "AIzaSyDRzs4rOsEp94LZtHOK54zP8ITDXC39vCM",
  authDomain: "digimagz-fccc4.firebaseapp.com",
  databaseURL: "https://digimagz-fccc4.firebaseio.com",
  projectId: "digimagz-fccc4",
  storageBucket: "digimagz-fccc4.appspot.com",
  messagingSenderId: "210648783864",
  appId: "1:210648783864:web:067772123c15f4f2b2d40d"
};
firebase.initializeApp(firebaseConfig);
</script>
<script>
  tinymce.init({
    selector: 'textarea',
    plugins: 'image media link code tinydrive',
    tinydrive_token_provider: "<?= base_url('token'); ?>",
    toolbar_drawer: 'floating',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Digimagz'
  });
  $("#menu-toggle").click(function(e) {
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
	});
	$(document).ready(function() {
    $('#newstable').DataTable( {
      "paging":   true,
      "ordering": false,
      "info":     false
    });
  });
</script>

</html>