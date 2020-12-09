<div class="py-1">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h3 class="display-3">Edit Galeri Berita</h3>
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
      <p><b>Informasi!</b> Isian bertanda (*) wajib diisi.</p>
    </div>
  </div>
</div>
<div class="py-4">
  <div class="container">
    <form method="post" id="myForm" action="<?= base_url('gallery/edit_gallery'); ?>" enctype="multipart/form-data">
      <input type="hidden" name="id_news" value="<?= $gallery->ID_NEWS; ?>">
      <div class="form-group row"> 
        <label for="judul" class="col-2 col-form-label">Judul*</label>
        <div class="col-9">
          <input type="text" class="form-control" name="judul" value="<?= $gallery->TITLE_NEWS; ?>"> 
        </div>
      </div>
      <div class="form-group row">
        <label for="isi" class="col-2 col-form-label">Isi Berita*</label>
        <div class="col-9">
          <textarea class="form-control form-control-lg" id="isiberita" rows="10" name="isi"><?= $gallery->CONTENT_NEWS; ?></textarea>
        </div>
      </div>
      <div class="form-group row">
        <label for="coverstory" class="col-2 col-form-label">Cover Story</label>
        <div class="col-4"><select class="custom-select" name="coverstory">
          <option <?= empty($gallery->ID_COVERSTORY) ? 'selected' : ''; ?> value="">Tidak Ada Cover Story</option>
          <?php foreach($cover_story as $cs){ ?>
            <option <?= $gallery->ID_COVERSTORY == $cs['ID_COVERSTORY'] ? 'selected' : ''; ?> value="<?= $cs['ID_COVERSTORY'] ?>"><?= $cs['TITLE_COVERSTORY'] ?></option>
          <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-2">Tag*</label>
        <div class="col-9">
          <input type="text" class="form-control" name="tag" id="tokenfield" value="<?= implode(', ', $news_tags) ?>">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-2">Pilih Gambar* (maksimal 5 file)</label>
        <div class="col-9 dropzone" id="dropzonePreview"></div>
      </div>
      <button type="submit" id="sbmtbtn" class="btn btn-primary">Ubah Galeri</button>
    </form>
  </div>
</div>
<script type="text/javascript">
  Dropzone.options.dropzonePreview = {
    url: '<?= base_url('gallery/upload_image'); ?>',
    uploadMultiple: true,
    resizeWidth: 120,
    parallelUploads: 5,
    maxFiles: 5,
    maxFilesize: 10,
    autoDiscover: false,
    acceptedFiles: "image/*",
    addRemoveLinks: true,
    paramName: "files",
    init: function(){
      myDropzone = this;
      $.ajax({
        type: 'POST',
        url: '<?= base_url('gallery/getCurrentImage'); ?>',
        data: {id_news: <?= $gallery->ID_NEWS ?>},
        dataType: 'json',
        success: function(response){
          $.each(response, function(key, value) {
            var mockFile = { name: value.name, size: value.size };
            myDropzone.createThumbnailFromUrl(mockFile, value.path);
            myDropzone.emit("addedfile", mockFile);
            myDropzone.emit("thumbnail", mockFile, value.path);
            myDropzone.emit("complete", mockFile);
          });
        }
      });
    },
    removedfile: function(file) {
      var name = file.name;        
      $.ajax({
        type: 'POST',
        url: '<?= base_url('gallery/deleteTemp'); ?>',
        data: {image_file: name},
        dataType: 'html'
      });
      var _ref;
      return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;        
    }
  };
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