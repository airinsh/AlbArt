<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detaje Produkti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f2f2f2; }
        .main-color { background-color: #a2b5cc !important; }
        .product-img { width: 100%; max-height: 400px; object-fit: cover; border-radius: 16px; }
        .artist-photo { width: 55px; height: 55px; border-radius: 50%; object-fit: cover; }

        .return-btn {
            background-color: #ffffff;
            color: #a2b5cc;
            border: 2px solid #ffffff;
            border-radius: 20px;
            padding: 5px 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .return-btn:hover {
            background-color: transparent;
            color: #ffffff;
            border-color: #ffffff;
        }

        .price { font-size: 26px; font-weight: bold; color: #4a90e2; }
        .btn-main { background-color: #a2b5cc; color: white; border: none; cursor: pointer; transition: 0.3s; }
        .btn-main:hover { background-color: #357ABD; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark main-color px-4 d-flex justify-content-between align-items-center">
    <span class="navbar-brand mb-0 h1">Product</span>

    <!-- Butoni Homepage -->
    <button class="return-btn"
            onclick="window.location.href='../HomePage/HomePage.php'">
        HomePage
    </button>
</nav>


<div class="container my-5">
    <div class="card shadow-lg rounded-4 p-4">

        <h2 class="mb-4" id="product-name">Loading...</h2>

        <div class="row g-4">
            <!-- FOTO PRODUKTI -->
            <div class="col-md-6">
                <img id="product-img" src="" class="product-img" alt="Product">
            </div>

            <!-- INFO -->
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <img id="artist-img" src="../img/default-artist.png" class="artist-photo me-3" alt="Artist">
                    <div class="d-flex align-items-center gap-3">
                        <span id="artist-name" class="fw-semibold">Loading Artist...</span>
                    </div>

                </div>

                <p id="product-desc">Loading description...</p>

                <div class="bg-light p-3 rounded-3 mb-3">
                    <p class="mb-1"><strong>Kategori:</strong> <span id="product-category">...</span></p>
                </div>

                <div class="d-flex align-items-center justify-content-between mt-3">
                    <span class="price" id="product-price">0 €</span>
                    <button class="btn btn-main px-4 fw-semibold">Add to Cart</button>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="./DetajeProdukti.js"></script>
</body>
</html>
