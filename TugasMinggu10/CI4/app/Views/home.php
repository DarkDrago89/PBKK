<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
    <h1 class="display-5 fw-bold">Selamat Datang di Toko Buah</h1>
    <p class="lead text-muted">Latihan <strong>Router &amp; Controller</strong> di CodeIgniter 4.</p>
    <a href="<?= url_to('buah') ?>" class="btn btn-success">Lihat Buah</a>
    <a href="<?= site_url('about') ?>" class="btn btn-outline-success">About</a>
</div>

<h5 class="mb-3">Daftar Route</h5>
<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table mb-0">
        <thead class="table-light"><tr><th>URL</th><th>Controller::method</th><th>Konsep</th></tr></thead>
        <tbody>
        <?php
        $daftar = [
            ['/',                     'Home::index',          'Route default'],
            ['/about',                'Page::about',          'Route statis'],
            ['/contact',              'Page::contact',        'Route statis'],
            ['/faqs',                 'Page::faqs',           'Route statis'],
            ['/tos',                  'Info::getTos',         'Route manual ke method berawalan verb'],
            ['/buah',                 'Buah::index',          'Route group + named route'],
            ['/buah/apel-malang',     'Buah::detail/$1',      'Placeholder (:segment)'],
            ['/buah/kategori/impor',  'Buah::kategori/$1',    'Placeholder (:alpha)'],
        ];
        ?>
        <?php foreach ($daftar as [$url, $handler, $konsep]): ?>
            <tr>
                <td><a href="<?= site_url(ltrim($url, '/')) ?>"><code><?= $url ?></code></a></td>
                <td><code><?= $handler ?></code></td>
                <td><?= $konsep ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
