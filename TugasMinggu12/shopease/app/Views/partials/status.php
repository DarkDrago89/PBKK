<?php [$label, $warna, $ikon] = \App\Models\OrderModel::BADGE[$s] ?? [$s, 'secondary', 'circle']; ?>
<span class="badge rounded-pill status-badge text-bg-<?= $warna ?>"><i class="bi bi-<?= $ikon ?>"></i> <?= $label ?></span>
