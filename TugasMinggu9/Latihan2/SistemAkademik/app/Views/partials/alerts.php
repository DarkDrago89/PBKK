<?php foreach (['success' => 'success', 'error' => 'danger'] as $key => $cls): ?>
    <?php if (session($key)): ?>
        <div class="alert alert-<?= $cls ?> alert-dismissible fade show no-print">
            <?= esc(session($key)) ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif ?>
<?php endforeach ?>

<?php if (session('errors')): ?>
    <div class="alert alert-danger no-print">
        <ul class="mb-0 pl-3">
            <?php foreach ((array) session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
