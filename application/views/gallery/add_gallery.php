<div class="py-1">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="display-3">Tambahkan Galeri Berita</h3>
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
        <form method="post" id="myForm" action="<?= base_url('gallery/create'); ?>" enctype="multipart/form-data">
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
                <label for="coverstory" class="col-2 col-form-label">Cover Story</label>
                <div class="col-4"><select class="custom-select" name="coverstory">
                    <option selected="" value="">Tidak Ada Cover Story</option>
                    <?php foreach($cover_story as $cs){ ?>
                    <option value="<?= $cs['ID_COVERSTORY'] ?>"><?= $cs['TITLE_COVERSTORY'] ?></option>
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
            <div class="form-group row">
                <label class="col-2">Pilih Gambar* (maksimal 5 file)</label>
                <div class="col-9 dropzone" id="dropzonePreview"></div>
            </div>
            <button type="submit" id="sbmtbtn" class="btn btn-primary">Buat Galeri</button>
        </form>
    </div>
</div>
<script type="text/javascript">
  Dropzone.options.dropzonePreview = {
    url: '<?= base_url('gallery/upload_image'); ?>',
    uploadMultiple: true,
    parallelUploads: 5,
    maxFiles: 5,
    maxFilesize: 10,
    autoDiscover: false,
    acceptedFiles: "image/*",
    addRemoveLinks: true,
    paramName: "files",
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