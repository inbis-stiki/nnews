<div class="py-1">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h3 class="display-3">Tambahkan Berita</h3>
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
      <p><b>Informasi!</b> Field yang ditandai harus diisi (upload gambar berita hanya untuk Berita dan Artikel).</p>
    </div>
  </div>
</div>
<div class="py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-9">
        <form id="c_form-h" method="post" action="<?= base_url('news/create'); ?>" enctype="multipart/form-data">
        <div class="form-group row"> 
          <label for="judul" class="col-2 col-form-label">Judul*</label>
          <div class="col-9">
            <input type="text" class="form-control" name="judul"> 
          </div>
        </div>
        <div class="form-group row">
          <label for="isi" class="col-2 col-form-label">Isi Berita*</label>
          <div class="col-9">
            <textarea class="form-control form-control-lg" id="isiberita" rows="10" name="isi"></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label for="kategori" class="col-2 col-form-label">Kategori*</label>
          <div class="col-4">
            <select class="custom-select" name="kategori">
              <option selected value="">Pilih Kategori</option>
              <?php foreach ($category as $c){ ?>
                <option value="<?= $c->ID_CATEGORY ?>"><?= $c->NAME_CATEGORY ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-2">Tag*</label>
          <div class="col-9">
            <input type="text" class="form-control" name="tag" id="tokenfield">
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Buat Berita Baru</button>
      </div>
      <div class="col-md-3">
        <label>Preview Gambar Berita</label>
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
  $('#tokenfield').tokenfield({
  <?php if (count($tags) > 0){ ?>
    autocomplete: {source: 
      <?php echo '[';
        $i = 0;
        $counted = count($tags) - 1;
        foreach ($tags as $t){
          echo '\'' . $t['TAGS'] . '\'' . ($i < $counted ? ', ' : '');
          $i++;
        }
        echo ']'; ?>,
      delay: 100
    },
    showAutocompleteOnFocus: true
  <?php } ?>
  });
</script>