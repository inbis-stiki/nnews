<div class="py-1">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="display-3">Tambahkan Banner</h3>
            </div>
        </div>
    </div>
</div>
<div class="py-1">
    <div class="container">
        <div class="alert alert-primary" role="alert">
            <p><b>Informasi!</b> Isian bertanda (*) wajib diisi</p>
        </div>
    </div>
</div>
<div class="py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form id="c_form-h" method="post" action="<?= base_url('banner/create'); ?>" enctype="multipart/form-data">
                    <div class="form-group row">
                        <label for="kategori" class="col-2 col-form-label">Banner untuk Berita*</label>
                        <div class="col-6">
                            <select class="custom-select" name="kategori">
                                <option selected="" value="">Pilih Berita</option>
                                <?php foreach ($news as $n){ ?>
                                <option value="<?= $n->ID_NEWS; ?>"><?= $n->TITLE_NEWS; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="kategori" class="col-2 col-form-label">Gambar Banner</label>
                        <div class="col-3">
                            <img class="img-fluid d-block py-2" id="preview" src="<?= base_url('assets/image/no_image.png') ?>">
                            <input type="file" name="image" id="image" style="display: none;">
                            <a class="btn btn-default" href="javascript:changeProfile()">Ubah</a>
                            <a class="btn btn-danger" href="javascript:removeImage()">Hapus</a>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Buat Banner</button>
                </form>
            </div>
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