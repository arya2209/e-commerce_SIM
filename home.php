<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Toko E-Commerce</title>
    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    >
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .quantity {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quantity button {
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4 text-center">Dashboard Produk</h1>
        <div class="row">

            <!-- Produk 1: Baju -->
            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/300x200?text=Baju" class="card-img-top" alt="Baju">
                    <div class="card-body">
                        <h5 class="card-title">Baju</h5>
                        <p class="card-text">Baju kasual untuk aktivitas sehari-hari.</p>
                        <div class="quantity mb-2">
                            <button class="btn btn-outline-secondary" onclick="decreaseQuantity(0)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span id="quantity-0">0</span>
                            <button class="btn btn-outline-secondary" onclick="increaseQuantity(0)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button class="btn btn-primary w-100" onclick="buyProduct(0)">Beli</button>
                    </div>
                </div>
            </div>

            <!-- Produk 2: Hoodie -->
            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/300x200?text=Hoodie" class="card-img-top" alt="Hoodie">
                    <div class="card-body">
                        <h5 class="card-title">Hoodie</h5>
                        <p class="card-text">Hoodie hangat dan nyaman untuk cuaca dingin.</p>
                        <div class="quantity mb-2">
                            <button class="btn btn-outline-secondary" onclick="decreaseQuantity(1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span id="quantity-1">0</span>
                            <button class="btn btn-outline-secondary" onclick="increaseQuantity(1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button class="btn btn-primary w-100" onclick="buyProduct(1)">Beli</button>
                    </div>
                </div>
            </div>

            <!-- Produk 3: Celana -->
            <div class="col-md-4">
                <div class="card">
                    <img src="https://via.placeholder.com/300x200?text=Celana" class="card-img-top" alt="Celana">
                    <div class="card-body">
                        <h5 class="card-title">Celana</h5>
                        <p class="card-text">Celana jeans untuk gaya santai dan keren.</p>
                        <div class="quantity mb-2">
                            <button class="btn btn-outline-secondary" onclick="decreaseQuantity(2)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span id="quantity-2">0</span>
                            <button class="btn btn-outline-secondary" onclick="increaseQuantity(2)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button class="btn btn-primary w-100" onclick="buyProduct(2)">Beli</button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    ></script>

    <script>
        const quantities = [0, 0, 0];

        function increaseQuantity(index) {
            quantities[index]++;
            document.getElementById(`quantity-${index}`).innerText = quantities[index];
        }

        function decreaseQuantity(index) {
            if (quantities[index] > 0) {
                quantities[index]--;
                document.getElementById(`quantity-${index}`).innerText = quantities[index];
            }
        }

        function buyProduct(index) {
            if (quantities[index] > 0) {
                alert(`Anda membeli ${quantities[index]} produk!`);
                quantities[index] = 0;
                document.getElementById(`quantity-${index}`).innerText = 0;
            } else {
                alert('Silakan pilih jumlah produk terlebih dahulu.');
            }
        }
    </script>
</body>
</html>