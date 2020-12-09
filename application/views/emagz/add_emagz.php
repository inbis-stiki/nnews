<div class="py-1">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h3 class="display-3">Tambahkan E-Magazine</h3>
      </div>
    </div>
  </div>
</div>
<?php if ($this->session->flashdata('error_message')){ ?>
<div class="py-1">
  <div class="container">
    <div class="alert alert-danger" role="alert">
      <h4 class="alert-heading">Oh snap!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></h4>
      <p><?= $this->session->flashdata('error_message') ?></p>
    </div>
  </div>
</div>
<?php } ?>
<div class="py-1">
  <div class="container">
    <div class="alert alert-primary" role="alert">
      <p><b>Informasi!</b> Field yang ditandai harus diisi.</p>
    </div>
  </div>
</div>
<div class="py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-9">
        <form id="c_form-h" method="post" action="<?= base_url('emagz/insert'); ?>" enctype="multipart/form-data">
        <div class="form-group row"> 
          <label for="judul" class="col-3 col-form-label">Nama E-Magazine*</label>
          <div class="col-9">
            <input type="text" class="form-control" name="judul"> 
          </div>
        </div>
        <div class="form-group row">
          <label for="isi" class="col-3 col-form-label">File E-Magazine (PDF)*</label>
          <div class="col-9">
            <input type="file" name="emagz" class="form-control-file">
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Buat E-Magazine Baru</button>
      </div>
      <div class="col-md-3">
        <label>Preview Thumbnail E-Magazine</label>
        <img class="img-fluid d-block py-2" id="preview" src="<?= base_url('assets/image/no_image.png') ?>">
        <input type="file" name="files" id="image" style="display: none;">
        <a class="btn btn-default" href="javascript:changeProfile()">Ubah</a>
        <a class="btn btn-danger" href="javascript:removeImage()">Hapus</a>
      </div>
      </form>
    </div>
  </div>
</div>
<script type='text/javascript'>
  function changeProfile() {
    $('#image').click();
  }
  $('#image').change(function () {
    var imgPath = this.value;
    var ext = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
    if (ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg")
      readURL(this);
    else
      alert("Please select image file (jpg, jpeg, png).")
  });
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.readAsDataURL(input.files[0]);
      reader.onload = function (e) {
        $('#preview').attr('src', e.target.result);
      };
    }
  }
  function removeImage() {
    $('#preview').attr('src', '<?= base_url('assets/image/no_image.png') ?>');
  }
</script>