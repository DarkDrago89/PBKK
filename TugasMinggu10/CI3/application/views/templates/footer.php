<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
</main>
<footer class="bg-white border-top py-3 text-center small text-muted">
    Controller: <code><?php echo $this->router->fetch_class(); ?></code>
    &middot; Method: <code><?php echo $this->router->fetch_method(); ?>()</code>
    &middot; CodeIgniter <?php echo CI_VERSION; ?>
</footer>
</body>
</html>
