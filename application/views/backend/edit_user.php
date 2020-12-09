<div class="py-1">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h3 class="display-3">Ubah Data Backend User</h3>
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
    <form id="c_form-h" method="post" action="<?= base_url('backend/update'); ?>" enctype="multipart/form-data">
      <div class="form-group row"> 
        <label for="judul" class="col-2 col-form-label">Username*</label>
        <div class="col-3">
          <input type="email" class="form-control" name="username" value="<?= $user->USERNAME ?>" readonly> 
        </div>
      </div>
      <div class="form-group row"> 
        <label for="judul" class="col-2 col-form-label">Password</label>
        <div class="col-3">
          <input type="password" class="form-control" name="password" id="password" <?= $user->DEFAULT_PASSWORD == 't' ? 'disabled' : '' ?>> 
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="checkbox" name="use_password" id="use_password" <?= $user->DEFAULT_PASSWORD == 't' ? 'checked' : '' ?>>
          <label class="form-check-label">Gunakan Password Default</label>
        </div>
      </div>
      <div class="form-group row">
        <label for="isi" class="col-2 col-form-label">Nama*</label>
        <div class="col-5">
          <input type="text" class="form-control" name="nama" value="<?= $user->NAME ?>">
        </div>
      </div>
      <div class="form-group row">
        <label for="kategori" class="col-2 col-form-label" style="margin-right: 20px">Jabatan*</label>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="jabatan" value="editor" <?= $user->ROLE == 'editor' ? 'checked' : '' ?>>
          <label class="form-check-label">Editor</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="jabatan" value="publisher" <?= $user->ROLE == 'publisher' ? 'checked' : '' ?>>
          <label class="form-check-label">Publisher</label>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Ubah User</button>
    </form>
  </div>
</div>
<script>
$('#use_password').change(function(){
  if (this.checked){
    $('#password').attr("disabled", true);
  } else {
    $('#password').removeAttr("disabled");
  }
});
</script>