<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
<link rel="stylesheet" href="<?= base_url('assets/css/font-awesome-4.7.0/css/font-awesome.min.css') ?>" type="text/css">
<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
</head>

<body>
    <div class="text-center py-0" style="">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <img class="d-block img-fluid mx-auto" src="<?= base_url('assets/image/logo.jpg') ?>" width="200">
                </div>
            </div>
            <div class="row">
                <div class="mx-auto col-10 bg-white border border-success col-md-4 p-3">
                    <h1 class="mb-4">Masuk</h1>
                    <?php if ($this->session->flashdata('error_login')){ ?>
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <p class="mb-0"><?= $this->session->flashdata('error_login') ?></p>
                    </div>
                    <?php } ?>
                    <form action="<?php echo base_url() . 'login/auth' ?>" method="post">
                        <div class="form-group"> 
                            <input type="text" name="username" class="form-control" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <?= $captcha['image'] ?>
                        </div>
                        <div class="form-group">
                            <input type="text" name="captcha" class="form-control" placeholder="Masukkan kode di atas">
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Masuk</button>
                    </form>
                    <p class="pt-3">All Rights Reserved © PTPN X 2019</p>
                </div>
            </div>
        </div>
    </div>
	<script src="<?= base_url('assets/js/jquery-3.3.1.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/popper.min.js') ?>"></script>
	<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
</body>

</html>