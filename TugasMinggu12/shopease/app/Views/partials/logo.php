<?php
/** Logo ShopEase (tas belanja tersenyum). Variabel opsional: $light (bool), $size (px) */
$size  ??= 40;
$light ??= false;
?>
<span class="se-logo <?= $light ? 'light' : '' ?>">
    <svg width="<?= $size ?>" height="<?= $size ?>" viewBox="0 0 48 48" aria-hidden="true">
        <rect x="6" y="14" width="36" height="30" rx="8" fill="<?= $light ? '#ffffff' : '#6d28d9' ?>"/>
        <path d="M16 18v-4a8 8 0 0 1 16 0v4" fill="none" stroke="<?= $light ? '#ffffff' : '#6d28d9' ?>" stroke-width="3.5" stroke-linecap="round"/>
        <path d="M17 28c2 4 4.5 6 7 6s5-2 7-6" fill="none" stroke="<?= $light ? '#6d28d9' : '#ffffff' ?>" stroke-width="3.5" stroke-linecap="round"/>
    </svg>
    <span>
        <span class="se-logo-name d-block">ShopEase</span>
        <span class="se-logo-sub d-block">Online Store</span>
    </span>
</span>
