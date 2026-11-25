<?php foreach (['success' => ['success', 'check-circle-fill'], 'error' => ['danger', 'exclamation-octagon-fill'], 'info' => ['info', 'info-circle-fill']] as $key => [$cls, $icon]): ?>
    <?php if (session($key)): ?>
        <div class="alert alert-<?= $cls ?> d-flex align-items-center gap-2 alert-dismissible fade show" role="alert">
            <i class="bi bi-<?= $icon ?>"></i>
            <div><?= esc(session($key)) ?></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif ?>
<?php endforeach ?>

<?php if (session('errors')): ?>
    <div class="alert alert-danger" role="alert">
        <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Periksa kembali isian Anda:</div>
        <ul class="mb-0 ps-3">
            <?php foreach ((array) session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
