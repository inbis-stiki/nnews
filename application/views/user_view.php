<div class="py-1">
  <div class="container">
    <div class="row">
      <div class="col-md-12"><h3 class="display-3">Manajemen Pengguna</h3>
      <h6>Jumlah Pengguna : <?= count($users); ?></small></h6>
    </div>
  </div>
</div>
<div class="py-0">
  <?php if ($this->session->flashdata('success')){ ?>
  <div class="container">
    <div class="alert alert-success" role="alert">
      <h4 class="alert-heading">Sukses!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></h4>
      <p><?= $this->session->flashdata('success') ?></p>
    </div>
  </div>
  <?php }
  if ($this->session->flashdata('error_message')){ ?>
  <div class="container">
    <div class="alert alert-danger" role="alert">
      <h4 class="alert-heading">Oh snap!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></h4>
      <p><?= $this->session->flashdata('error_message') ?></p>
    </div>
  </div>
  <?php } ?>
</div>
<div class="py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-12"><h4 style="text-align: center;">Data Dokter<h4></div>
    </div>
    <div class="row" style="margin-top: 24px;margin-bottom: 24px;">
      <div class="col-md-12"><a class="btn btn-primary" href="<?php echo base_url('user/add_doctor'); ?>"><i class="fa fa-plus"></i>&ensp;Tambahkan Dokter</a></div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-striped table-borderless" id="doctorTable">
            <thead>
              <tr>
                <th class="text-center">Email</th>
                <th class="text-center">Nama</th>
                <!-- <th class="text-center">Login Terakhir</th>
                <th class="text-center">Tipe User</th> -->
                <th class="text-center" style="width: 50px">Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($doctors as $d){ ?>
              <tr>
                <td><?= $d->EMAIL ?></td>
                <td><?= $d->NAME ?></td>
                <td>
                  <a href="<?= base_url('user/edit_doctor/'.$d->EMAIL) ?>" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                  <a href="#" data-toggle="modal" data-target="#ModalDeleteDoctor" data-id="<?= $d->EMAIL ?>"
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row" style="margin-top: 24px;">
      <div class="col-md-12"><h4 style="text-align: center;">Data User<h4></div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-striped table-borderless" id="newstable">
            <thead>
              <tr>
                <th class="text-center">Email</th>
                <th class="text-center">Nama</th>
                <!-- <th class="text-center">Login Terakhir</th>
                <th class="text-center">Tipe User</th> -->
                <!-- <th class="text-center" style="width: 50px">Aksi</th> -->
              </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u){ ?>
              <tr>
                <td><?= $u->EMAIL ?></td>
                <td><?= $u->NAME ?></td>
                <!-- <td><?= tgl_indo($u->LAST_LOGIN) ?></td>
                <td><?= $u->USER_TYPE ?></td> -->
                <!-- <td class="text-center">

                </td> -->
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
