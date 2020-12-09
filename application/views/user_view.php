<div class="py-1">
  <div class="container">
    <div class="row">
      <div class="col-md-12"><h3 class="display-3">Manajemen Pengguna</h3>
      <h6>Jumlah Pengguna : <?= count($users); ?></small></h6>
    </div>
  </div>
</div>
<div class="py-0">
  <?php if ($this->session->flashdata('success_message')){ ?>
  <div class="container">
    <div class="alert alert-success" role="alert">
      <h4 class="alert-heading">Sukses!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></h4>
      <p><?= $this->session->flashdata('success_message') ?></p>
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
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-striped table-borderless" id="newstable">
            <thead>
              <tr>
                <th class="text-center">Email</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Login Terakhir</th>
                <th class="text-center">Tipe User</th>
                <!-- <th class="text-center" style="width: 50px">Aksi</th> -->
              </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u){ ?>
              <tr>
                <td><?= $u->EMAIL ?></td>
                <td><?= $u->USER_NAME ?></td>
                <td><?= tgl_indo($u->LAST_LOGIN) ?></td>
                <td><?= $u->USER_TYPE ?></td>
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