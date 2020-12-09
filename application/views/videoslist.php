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
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($videos_list as $v){ ?>
              <tr>
                <td><img src="<?= $v->URL_DEFAULT_THUMBNAIL ?>" alt=""></td>
                <td>
                  <h6><a href="http://www.youtube.com/watch?v=<?= $v->ID_VIDEO ?>"><?= $v->TITLE ?></a></h6>
                  <small><?= $v->DESCRIPTION ?></small>
                </td>
                <td><?php if ($v->STATUS_PUBLISHED == 't'){ ?>
                  <a href="<?= base_url('video/change/' . $v->ID_VIDEO . '/' . 0) ?>" class="btn btn-sm btn-info"><i class="fa fa-lg fa-eye-slash"></i>&nbsp;Sembunyikan</a>
                <?php } else { ?>
                  <a href="<?= base_url('video/change/' . $v->ID_VIDEO . '/' . 1) ?>" class="btn btn-sm btn-info"><i class="fa fa-lg fa-eye"></i>&nbsp;Tampilkan</a>
                <?php } ?></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>