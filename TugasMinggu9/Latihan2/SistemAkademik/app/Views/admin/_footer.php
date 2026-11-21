<div class="card-footer bg-white d-flex justify-content-between align-items-center">
    <small class="text-muted">Total: <?= $pager->getTotal() ?> data</small>
    <?= $pager->links('default', 'bootstrap') ?>
</div>
