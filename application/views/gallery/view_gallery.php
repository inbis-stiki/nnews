<div class="py-1">
  <div class="container">
    <div class="row">
      <div class="col-md-12"><h3 class="display-3">Galeri</h3></div>
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
<?php if ($this->session->userdata('role') != 'publisher'){ ?>
<div class="py-0">
  <div class="container">
    <div class="row">
      <div class="col-md-12"><a class="btn btn-primary" href="<?php echo base_url('gallery/add_gallery'); ?>"><i class="fa fa-plus"></i>&ensp;Tambahkan Galeri</a></div>
    </div>
  </div>
</div>
<?php } ?>
<div class="py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-striped table-borderless" id="newstable">
            <thead>
              <tr>
                <th class="text-center" style="width: 35%">Judul</th>
                <th class="text-center">Tanggal Publikasi</th>
                <th class="text-center"><i class="fa fa-eye"></i></th>
                <th class="text-center"><i class="fa fa-heart"></i></th>
                <th class="text-center"><i class="fa fa-share-alt"></i></th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
            <?php  foreach ($news_list as $news){ ?>
              <tr>
                <td><?php echo $news->TITLE_NEWS; ?></td>
                <td class="text-center"><?php echo tgl_indo($news->DATE_NEWS); ?></td>
                <td class="text-center"><?php echo $news->VIEWS_COUNT; ?></td>
                <td class="text-center"><?php echo $news->LIKES ?></td>
                <td class="text-center"><?php echo $news->SHARES_COUNT; ?></td>
                <td class="text-center">
                  <?php if (in_array($this->session->userdata('role'), ['publisher', 'admin'])){ ?>
                    <a href="<?= base_url('gallery/detail/' . $news->ID_NEWS) ?>" class="btn btn-sm btn-secondary"><i class="fa fa-tasks"></i>&nbsp;Detail</a>
                  <?php } 
                  if (in_array($this->session->userdata('role'), ['editor', 'admin'])) { ?>
                  <a href="<?= base_url('gallery/edit/' . $news->ID_NEWS); ?>" class="btn btn-sm btn-info"><i class="fa fa-pencil"></i>&nbsp;Edit</a>
                  <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#ModalDelete" data-id="<?php echo $news->ID_NEWS; ?>" data-title="<?php echo $news->TITLE_NEWS; ?>"><i class="fa fa-trash"></i>&nbsp;Hapus</a>
                  <?php } ?>
                  <a href="#" class="btn btn-sm btn-success"><i class="fa fa-bar-chart"></i>&nbsp;Statistik</a>
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
<div class="modal fade" id="ModalDelete" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Penghapusan</h5> <button type="button" class="close" data-dismiss="modal"> <span>×</span> </button>
      </div>
      <div class="modal-body">
        <p>Anda yakin ingin menghapus galeri berita yang dipilih?</p>
        <p id="newstitle"></p>
      </div>
      <div class="modal-footer"> 
        <a href="<?php echo base_url() ?>gallery/delete/<?php echo $news->ID_NEWS?>" class="btn btn-danger">Delete</a>
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
  modal.find('.modal-footer a').attr("href", "<?= base_url() ?>gallery/delete/" + news_id)
})
</script>