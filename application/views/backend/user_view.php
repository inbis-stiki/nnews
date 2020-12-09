<div class="py-1">
  <div class="container">
    <div class="row">
      <div class="col-md-12"><h3 class="display-3">Manajemen Backend User</h3></div>
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
<div class="py-0">
  <div class="container">
    <div class="row">
      <div class="col-md-12"><a class="btn btn-primary" href="<?php echo base_url('backend/add_user'); ?>"><i class="fa fa-plus"></i>&ensp;Tambahkan User</a></div>
    </div>
  </div>
</div>
<div class="py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-striped table-borderless" id="newstable">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Username</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Jabatan</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1;
              foreach ($users as $u){ ?>
                <tr>
                  <td class="text-center"><?= $i; ?></td>
                  <td class="text-center"><?= $u->USERNAME ?></td>
                  <td><?= $u->NAME ?></td>
                  <td class="text-center"><?= ucwords($u->ROLE) ?></td>
                  <td class="text-center">
                  <?php if ($u->ROLE != 'admin'){ ?>
                    <a href="<?= base_url('backend/edit_user/' . str_replace('@', '_', $u->USERNAME)) ?>" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                    <a href="#" data-toggle="modal" data-target="#ModalDelete" data-id="<?= str_replace('@', '_', $u->USERNAME) ?>"
                          data-title="<?php echo $u->NAME; ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i>&nbsp;Hapus</a>
                  <?php } ?>
                  </td>
                </tr>
              <?php $i++; } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="ModalDelete" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Penghapusan</h5> <button type="button" class="close" data-dismiss="modal"> <span>×</span> </button>
      </div>
      <div class="modal-body">
        <p>Anda yakin ingin menghapus user yang dipilih?</p>
        <p id="newstitle"></p>
      </div>
      <div class="modal-footer"> 
        <a href="<?php echo base_url() ?>backend/delete/<?php echo $u->USERNAME?>" class="btn btn-danger">Delete</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 
      </div>
    </div>
  </div>
</div>
<script>
$('#ModalDelete').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget)
  var news_id = button.data('id')
  var modal = $(this)
  var news_title = button.data('title');
  document.getElementById('newstitle').innerHTML = news_title;
  modal.find('.modal-footer a').attr("href", "<?= base_url() ?>backend/delete/" + news_id)
})
</script>