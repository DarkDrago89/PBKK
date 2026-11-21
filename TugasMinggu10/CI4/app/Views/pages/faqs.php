<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1>FAQs</h1>
<p class="lead">Pertanyaan seputar Router &amp; Controller.</p>
<div class="list-group shadow-sm">
    <?php foreach ($faqs as $tanya => $jawab): ?>
        <div class="list-group-item">
            <div class="fw-semibold"><?= esc($tanya) ?></div>
            <div class="text-muted"><?= esc($jawab) ?></div>
        </div>
    <?php endforeach ?>
</div>
<p class="text-muted small mt-3">Route <code>GET /faqs</code> &rarr; <code>Page::faqs()</code> &rarr; view <code>pages/faqs</code> (data dikirim dari controller)</p>
<?= $this->endSection() ?>
