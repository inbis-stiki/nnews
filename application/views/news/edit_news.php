<div class="py-1">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="display-3">Edit Berita</h3>
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
                <form id="c_form-h" method="post" action="<?= base_url('news/edit'); ?>" enctype="multipart/form-data">
                <input type="hidden" name="id_news" value="<?= $this->uri->segment(3); ?>">
                <div class="form-group row"> 
                    <label for="judul" class="col-2 col-form-label">Judul*</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="judul" value="<?= $news->TITLE_NEWS; ?>"> 
                    </div>
                </div>
                <div class="form-group row">
                    <label for="isi" class="col-2 col-form-label">Isi Berita*</label>
                    <div class="col-9">
                        <textarea class="form-control form-control-lg" id="isiberita" rows="10" name="isi"><?= $news->CONTENT_NEWS; ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="kategori" class="col-2 col-form-label">Kategori*</label>
                    <div class="col-4">
                        <select class="custom-select" name="kategori">
                            <?php foreach ($category as $c){ ?>
                                <option <?= $news->ID_CATEGORY == $c->ID_CATEGORY? 'selected' : ''; ?> value="<?= $c->ID_CATEGORY ?>"><?= $c->NAME_CATEGORY ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="coverstory" class="col-2 col-form-label">Cover Story</label>
                    <div class="col-4"><select class="custom-select" name="coverstory">
                        <option <?= empty($news->ID_COVERSTORY) ? 'selected' : ''; ?> value="">Tidak Ada Cover Story</option>
                        <?php foreach($cover_story as $cs){ ?>
                        <option <?= isset($news->ID_COVERSTORY) && $news->ID_COVERSTORY == $cs['ID_COVERSTORY'] ? 'selected' : ''; ?> value="<?= $cs['ID_COVERSTORY'] ?>"><?= $cs['TITLE_COVERSTORY'] ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2">Tag*</label>
                    <div class="col-9">
                        <input type="text" class="form-control" name="tag" value="<?= implode(', ', $news_tags) ?>" id="tokenfield">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Ubah Berita</button>
            </div>
            <div class="col-md-3">
                <label>Preview Gambar Berita</label>
                <img class="img-fluid d-block py-2" id="preview" src="<?= !empty($news->NEWS_IMAGE) ? base_url('images/news/' . $news->NEWS_IMAGE) : base_url('assets/image/no_image.png') ?>">
                <input type="file" name="files" id="image" style="display: none;">
                <input type="hidden" name="old_files" id="old_files" value="<?= !empty($news->NEWS_IMAGE) ? $news->NEWS_IMAGE : '' ?>">
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
    $('#old_files').val('');
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