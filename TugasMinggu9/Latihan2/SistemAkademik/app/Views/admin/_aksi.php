<td class="text-right pr-3 text-nowrap align-middle">
    <a href="<?= site_url("admin/{$uri}/{$id}/edit") ?>" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
    <form action="<?= site_url("admin/{$uri}/{$id}/delete") ?>" method="post" class="d-inline"
          onsubmit="return confirm(<?= esc(json_encode($konfirmasi), 'attr') ?>)">
        <?= csrf_field() ?>
        <button class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
    </form>
</td>
