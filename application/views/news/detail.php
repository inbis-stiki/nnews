<div class="py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h3 class="display-4"><?= $berita->TITLE_NEWS ?></h3>
        <small><?= 'Dipublikasikan pada ' . tgl_indo($berita->DATE_NEWS) . ' oleh ' . $berita->EDITOR ?></small>
      </div>
    </div>
  </div>
</div>
<div class="py-2">
  <div class="container">
    <div class="row">
      <div class="<?= isset($berita->NEWS_IMAGE) ? 'col-md-8' : 'col-md-12' ?>">
        <?= $berita->CONTENT_NEWS ?>
      </div>
      <?php if (isset($berita->NEWS_IMAGE)){ ?>
      <div class="col-md-4">
        <img src="<?= base_url('images/news/' . $berita->NEWS_IMAGE) ?>" style="max-width: 100%;">
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<div class="py-2">
  <div class="container">
    <div class="row">
      <div class="col-md-3">
        <form action="<?= base_url('news/verify') ?>" method="post">
          <input type="hidden" name="id_news" value="<?= $berita->ID_NEWS ?>">
          <div class="form-group">
            <label for="status">Pilih Status Verifikasi</label>
            <select class="custom-select" id="status" name="status">
              <option value="pending" <?= $berita->STATUS == 'pending' ? 'selected' : '' ?>>Pending</option>
              <option value="published" <?= $berita->STATUS == 'published' ? 'selected' : '' ?>>Diterima dan Dipublikasikan</option>
              <option value="rejected" <?= $berita->STATUS == 'rejected' ? 'selected' : '' ?>>Ditolak</option>
            </select>
          </div>
          <button type="submit" id="button-submit" class="btn btn-success" disabled><i class="fa fa-gavel"></i> Selesai</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('#status').change(function(){
    var selected = this.value;
    if (selected != ''){
      $('#button-submit').removeAttr('disabled');
    }
  });
</script>