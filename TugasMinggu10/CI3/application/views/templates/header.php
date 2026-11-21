<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo html_escape($title); ?> | Toko Buah (CI 3)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
<nav class="navbar navbar-expand navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo site_url(); ?>">🍉 Toko Buah</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="<?php echo site_url(); ?>">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo site_url('about'); ?>">About</a></li>
            <li class="nav-item"><a class="nav-link" href="<?php echo site_url('contact'); ?>">Contact</a></li>
        </ul>
    </div>
</nav>
<main class="container py-5 flex-grow-1">
