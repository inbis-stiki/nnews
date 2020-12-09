<div class="py-1">
    <div class="container">
        <div class="row">
            <div class="col-md-12"><h3 class="display-3">Manajemen Komentar</h3></div>
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
                    <table class="table table-striped table-borderless" id="commentstable">
                        <thead>
                            <tr>
                                <th rowspan="2" style="vertical-align: middle;" class="text-center">No.</th>
                                <th rowspan="2" style="vertical-align: middle;" class="text-center">Judul Berita</th>
                                <th colspan="2" class="text-center">Komentar</th>
                                <th rowspan="2" style="vertical-align: middle;" class="text-center">Aksi</th>    
                            </tr>
                            <tr>
                                <th class="text-center">Diterima</th>
                                <th class="text-center">Pending</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1;
                            foreach($comments_list as $c){ ?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td><?= $c['TITLE_NEWS']; ?></td>
                                <td class="text-center"><?= $c['COMMENTS_TOTAL'] - ($c['COMMENTS_PENDING'] == '' ? 0 : $c['COMMENTS_PENDING']); ?></td>
                                <td class="text-center"><?= $c['COMMENTS_PENDING'] == '' ? 0 : $c['COMMENTS_PENDING']; ?></td>
                                <td class="text-center"><a href="<?= base_url('comment/manage/' . $c['ID_NEWS']) ?>" class="btn btn-sm btn-primary">Kelola</a></td>
                            </tr>
                            <?php $i++; }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#commentstable').DataTable( {
            "paging":   true,
            "ordering": false,
            "info":     false
        });
    });
</script>