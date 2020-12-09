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
<script src="<?= base_url('assets/js/tinymce/tinymce.min.js') ?>"></script>
<script src="<?= base_url('assets/js/dropzone.js') ?>"></script>
<script>tinymce.init({selector:'textarea'});</script>
<style>
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 80%;
}
</style>
</head>

<body>
	<div class="d-flex" id="wrapper">
		<div id="page-content-wrapper">
			<div class="container-fluid">
        <div class="py-4">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <h3 class="display-4"><?= $berita->TITLE_NEWS ?></h3>
                <small><?= 'Dipublikasikan pada ' . tgl_indo($berita->DATE_NEWS) ?></small>
              </div>
            </div>
          </div>
        </div>
        <div class="py-2">
          <div class="container">
            <div class="row">
              <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                <?php $i = 1; foreach($gallery as $g){ ?>
                  <div class="carousel-item <?= $i == 1 ? 'active' : '' ?>">
                    <img class="center" src="<?= base_url('images/gallery/' . $this->uri->segment(3) . '/' . $g->IMAGE_FILE) ?>" alt="<?= $berita->TITLE_NEWS ?>">
                  </div>
                <?php $i++; } ?>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                  <span class="carousel-control-next-icon"></span>
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="py-2">
          <div class="container">
            <div class="row">
              <?= $berita->CONTENT_NEWS ?>
            </div>
          </div>
        </div>
			</div>
		</div>	
	</div>
</body>
</html>