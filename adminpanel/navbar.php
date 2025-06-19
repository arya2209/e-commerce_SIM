<style>
  body{
    padding: 20px;
  }
  .navbar {
            background: rgba(255, 255, 255, 0.8);
        }
        .navbar-nav .nav-link {
            transition: color 0.3s;
        }
        .navbar-nav .nav-link:hover {
            color: blue !important;
        }
</style>

<nav class="navbar navbar-expand-lg shadow-sm py-3 sticky-top bg-light">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Dashboard Luxia</a>
    <div class="collapse navbar-collapse justify-content-end" id="navbarAdmin">
      <ul class="navbar-nav gap-3">
        <li class="nav-item"><a class="nav-link" href="produk.php">Produk</a></li>
        <li class="nav-item"><a class="nav-link" href="kategori.php">Kategori</a></li>
        <li class="nav-item"><a class="nav-link" href="riwayat_order.php">Order</a></li>
        <?php if (isset($_SESSION['nama'])): ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
              <i class="fas fa-user"></i> <?= $_SESSION['nama']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="../adminpanel/index.php">Dashboard</a></li>
              <li><a class="dropdown-item text-danger" href="../adminpanel/logout.php">Logout</a></li>
            </ul>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
