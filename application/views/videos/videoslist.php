<div class="py-1">
  <div class="container">
    <div class="row">
      <div class="col-md-12"><h3 class="display-3">Video</h3></div>
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
                <th>Thumbnail</th>
                <th>Judul (Deskripsi)</th>
                <th style="width: 30%;text-align: center;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1;
              foreach ($videos_list as $video){ ?>
                <tr>
                  <td><img src="<?= $video->URL_DEFAULT_THUMBNAIL ?>" alt=""></td>
                  <td>
                    <h6><a href="http://www.youtube.com/watch?v=<?= $video->ID_VIDEO ?>"><?= $video->TITLE ?></a></h6>
                    <small><?= $video->DESCRIPTION ?></small>
                  </td>
                  <td class="text-center">
                    <?php if ($video->STATUS_PUBLISHED == 't'){ ?>
                        <a href="<?= base_url('video/change/' . $video->ID_VIDEO . '/' . 0) ?>" class="btn btn-sm btn-secondary"><i class="fa fa-eye-slash"></i>&nbsp;Sembunyikan</a>
                    <?php } else { ?>
                        <a href="<?= base_url('video/change/' . $video->ID_VIDEO . '/' . 1) ?>" class="btn btn-sm btn-secondary"><i class="fa fa-eye"></i>&nbsp;Tampilkan</a>
                    <?php } ?>
                    <!-- <a href="#" class="btn btn-sm btn-success"><i class="fa fa-bar-chart"></i>&nbsp;Statistik</a> -->
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
        <p>Anda yakin ingin menghapus berita yang dipilih?</p>
        <p id="newstitle"></p>
      </div>
      <div class="modal-footer"> 
        <a href="<?php echo base_url() ?>videos/delete/<?php echo $video->ID_VIDEO?>" class="btn btn-danger">Delete</a>
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
    modal.find('.modal-footer a').attr("href", "<?= base_url() ?>videos/delete/" + news_id)
})
</script>
