<ul class="list-group list-group-flush">
    <li class="list-group-item d-flex justify-content-between"><span>Subtotal</span><span><?= rupiah($biaya['subtotal']) ?></span></li>
    <li class="list-group-item d-flex justify-content-between">
        <span>Ongkos kirim</span>
        <span><?= $biaya['ongkir'] === 0 && $biaya['subtotal'] > 0 ? '<span class="text-success">Gratis</span>' : rupiah($biaya['ongkir']) ?></span>
    </li>
    <li class="list-group-item d-flex justify-content-between fw-bold fs-5"><span>Total</span><span class="text-primary"><?= rupiah($biaya['total']) ?></span></li>
</ul>
