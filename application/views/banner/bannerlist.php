<div class="py-1">
  <div class="container">
    <div class="row">
      <div class="col-md-12"><h3 class="display-3">Banner</h3></div>
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
      <div class="col-md-12"><a class="btn btn-primary" href="<?php echo base_url('banner/add_banner'); ?>"><i class="fa fa-plus"></i>&ensp;Tambahkan Banner</a></div>
    </div>
  </div>
</div>
<?php } ?>
<div class="py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="table-responsive">
          <table class="table table-striped table-borderless" id="commentstable">
            <thead>
              <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Gambar Banner</th>
                <th class="text-center">Judul Berita</th>
                <?php if ($this->session->userdata('role') != 'publisher'){ ?>
                <th class="text-center">Aksi</th>
                <?php } ?>    
              </tr>
            </thead>
            <tbody>
            <?php $i = 1;
            foreach($banners_list as $c){ ?>
            <tr>
              <td><?= $i; ?></td>
              <td><img src="<?= base_url('images/banner/'.$c['IMAGE_BANNER']); ?>" alt="<?= $c['TITLE_NEWS']; ?>"></td>
              <td><?= $c['TITLE_NEWS']; ?></td>
              <?php if ($this->session->userdata('role') != 'publisher'){ ?>
              <td>
                <a href="#"><i class="fa fa-lg fa-pencil text-secondary"></i></a>
                <a href="#" data-toggle="modal" data-target="#ModalDelete" data-id="<?= $c['ID_BANNER']; ?>"
                    data-title="<?= $c['TITLE_NEWS']; ?>">
                    <i class="fa fa-lg fa-trash text-danger"></i>
                </a>
              </td>
              <?php } ?>
            </tr>
            <?php $i++; }
            ?>
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
          <h5 class="modal-title">Konfirmasi Penghapusan</h5> <button type="button" class="close" data-dismiss="modal"> <span>??</span> </button>
        </div>
        <div class="modal-body">
          <p>Anda yakin ingin menghapus banner yang dipilih?</p>
          <p id="newstitle"></p>
        </div>
        <div class="modal-footer"> 
          <a href="<?php echo base_url() ?>banner/delete/<?= $c['ID_COVERSTORY'] ?>" class="btn btn-danger">Delete</a>
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
    modal.find('.modal-footer a').attr("href", "<?= base_url() ?>banner/delete/" + news_id)
})
</script>
<script>
    $(document).ready(function() {
        $('#commentstable').DataTable( {
            "paging":   true,
            "ordering": false,
            "info":     false
        });
    });
</script>