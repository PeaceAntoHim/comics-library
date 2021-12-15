<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h2 class="mt-2">Detail Comic</h2>
            <div class="card mb-3" style="max-width: 940px;">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="/img/<?= $comics['cover']; ?>" class="card-img cover-detail">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= $comics['title']; ?></h5>
                            <p class="card-text"><b>Writer : </b><?= $comics['writer']; ?></p>
                            <p class="card-text"><small><b>Publisher : </b><?= $comics['publisher']; ?></small></p>
                            <p class="card-text"><small class="text-muted"><b>Synopsis : </b><?= $comics['synopsis']; ?></small></p>
                            <br>

                            <a href="/comics/edit/<?= $comics['slug']; ?>" class="btn btn-warning">Edit</a>

                            <form action="/comics/<?= $comics['id']; ?>" method="post" class="d-inline">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</button>
                            </form>
                            <br><br>
                            <a href="/comics">Return to list of comics</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>