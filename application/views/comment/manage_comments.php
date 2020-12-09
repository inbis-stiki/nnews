<div class="py-1">
  <div class="container">
      <div class="row">
          <div class="col-md-12">
            <h3 class="display-3">Manajemen Komentar</h3>
            <h4><?= $news->TITLE_NEWS ?></h4>
          </div>
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
          <table class="table table-striped table-borderless" id="commentstable">
            <thead>
              <tr>
                <th class="text-center">Nama User (Username)</th>
                <th class="text-center">Komentar</th>
                <th class="text-center">Tanggal Komentar</th>
                <th class="text-center">Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($komentar as $c){ ?>
              <tr>
                <td><?= $c['USER_NAME'] . ' (' . $c['EMAIL'] . ')' ?></td>
                <td><?= $c['COMMENT_TEXT'] ?></td>
                <td><?= tgl_indo($c['DATE_COMMENT']) ?></td>
                <td class="text-center"><?= $c['IS_APPROVED'] == 'f' ? 'Pending' : 'Ditampilkan' ?></td>
                <td class="text-center">
                <?php if ($c['IS_APPROVED'] == 't'){ ?>
                  <a href="<?= base_url('comment/approve/' . $c['ID_COMMENT'] . '/f') ?>" class="btn btn-sm btn-block btn-danger"><i class="fa fa-times-circle"></i>&nbsp;Sembunyikan</a>
                <?php } else { ?>
                  <a href="<?= base_url('comment/approve/' . $c['ID_COMMENT'] . '/t') ?>" class="btn btn-sm btn-block btn-success"><i class="fa fa-check-circle"></i>&nbsp;Tampilkan</a>
                <?php }
                if (empty($c['ADMIN_REPLY'])) { ?>
                  <a href="#" data-toggle="modal" data-target="#ModalReply" data-id="<?= $c['ID_COMMENT'] ?>" data-text="<?= $c['COMMENT_TEXT'] ?>" class="btn btn-sm btn-block btn-info"><i class="fa fa-reply"></i>&nbsp;Balas</a>
                <?php } else { ?>
                  <a href="#" data-toggle="modal" data-target="#ModalDeleteReply" data-id="<?= $c['ID_COMMENT'] ?>" class="btn btn-sm btn-block btn-info"><i class="fa fa-trash"></i>&nbsp;Hapus Balasan</a>
                <?php } ?>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="ModalReply" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Balas Komentar</h5> <button type="button" class="close" data-dismiss="modal"> <span>×</span> </button>
      </div>
      <form action="<?= base_url('comment/reply') ?>" method="post">
      <div class="modal-body">
        <input type="hidden" name="id_comments" id="id_comments" value="">
        <label for="reply" id="comments"></label>
        <textarea name="reply" id="admin-reply"></textarea>
      </div>
      <div class="modal-footer"> 
        <button type="submit" class="btn btn-success">Kirimkan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button> 
      </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="ModalDeleteReply" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Hapus Balasan Komentar</h5> <button type="button" class="close" data-dismiss="modal"> <span>×</span> </button>
      </div>
      <div class="modal-body">
        Anda yakin ingin menghapus balasan komentar yang dipilih?
      </div>
      <div class="modal-footer"> 
        <a href="#" class="btn btn-info">Hapus</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button> 
      </div>
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    $('#ModalReply').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget)
      var id_comments = button.data('id')
      var modal = $(this)
      var comment_text = button.data('text');
      $('#id_comments').val(id_comments);
      document.getElementById('comments').innerHTML = comment_text;
    });
    $('#ModalDeleteReply').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget)
      var id_comments = button.data('id')
      var modal = $(this)
      modal.find('.modal-footer a').attr("href", "<?= base_url() ?>comment/delete_reply/" + id_comments)
    });
    $('#commentstable').DataTable( {
      "paging":   true,
      "ordering": false,
      "info":     false
    });
  });
</script>