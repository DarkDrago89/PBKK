<?php if (! empty($foto)): ?>
    <img src="<?= base_url('uploads/foto/' . $foto) ?>" alt="<?= esc($nama) ?>" class="rounded-circle <?= $size ?>">
<?php else: ?>
    <img src="https://ui-avatars.com/api/?background=4e73df&color=fff&name=<?= urlencode($nama) ?>" alt="<?= esc($nama) ?>" class="rounded-circle <?= $size ?>">
<?php endif ?>
