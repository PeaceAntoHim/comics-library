<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <a href="/comics/create" class="btn btn-primary mt-3 mb-3 float-right">Add new comics</a>
            <h1 class="mt-3">List of Comics</h1>
            <form action="" method="post">
                <div class="input-group mb-3 search-comics">
                    <input type="text" class="form-control" autofocus placeholder="Enter a keyword..." name="keyword">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit" name="submit">Search</button>
                    </div>
                </div>
            </form>
            <?php if (session()->getFlashdata('message')) : ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('message'); ?>
                </div>
            <?php endif; ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Sampul</th>
                        <th scope="col">Komik</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1 + (3 * ($currentPage - 1)); ?>
                    <?php foreach ($comics as $c) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><img src="/img/<?= $c['cover']; ?>" class="cover"></td>
                            <td><?= $c['title']; ?></td>
                            <td>
                                <a href="/comics/<?= $c['slug']; ?>" class="btn btn-success">detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Link pagination -->
            <?= $pager->links('comics', 'comics_pagination'); ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>