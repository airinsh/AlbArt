<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detaje Produkti</title>

    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f2f2f2;
        }

        .main-color {
            background-color: #a2b5cc !important;
        }

        .product-img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 16px;
        }

        .artist-photo {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            object-fit: cover;
        }

        .price {
            font-size: 26px;
            font-weight: bold;
            color: #4a90e2;
        }

        /* BUTON CLICKABLE + HOVER */
        .btn-main {
            background-color: #a2b5cc;
            color: white;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-main:hover {
            background-color: #357ABD;
            color: white;
        }
    </style>
</head>
<body>

<!-- TOOLBAR -->
<nav class="navbar navbar-dark main-color px-4">
    <span class="navbar-brand mb-0 h1">Product</span>
</nav>

<!-- PAGE -->
<div class="container my-5">
    <div class="card shadow-lg rounded-4 p-4">

        <h2 class="mb-4">Emri i Produktit</h2>

        <div class="row g-4">

            <!-- FOTO PRODUKTI -->
            <div class="col-md-6">
                <img src="Portrete.jpeg" class="product-img" alt="Product">
            </div>

            <!-- INFO -->
            <div class="col-md-6">

                <!-- AUTORI -->
                <div class="d-flex align-items-center mb-3">
                    <img src="Piktura.jpeg" class="artist-photo me-3" alt="Artist">

                    <div class="d-flex align-items-center gap-3">
                        <span class="fw-semibold">Emri i Artistit</span>
                        <button class="btn btn-sm btn-outline-primary">
                            Open Profile
                        </button>
                    </div>
                </div>

                <!-- PERSHKRIMI -->
                <p class="mt-3">
                    Përshkrimi i produktit vendoset këtu. Ky tekst do të shpjegojë detaje
                    rreth veprës, materialit dhe stilit artistik.
                </p>

                <!-- INFO -->
                <div class="bg-light p-3 rounded-3 mb-3">
                    <p class="mb-1"><strong>Kategori:</strong> Art Modern</p>
                    <p class="mb-1"><strong>Material:</strong> Vaj në kanavacë</p>
                    <p class="mb-0"><strong>Përmasa:</strong> 50 x 70 cm</p>
                </div>

                <!-- CMIMI + ADD TO CART -->
                <div class="d-flex align-items-center justify-content-between mt-3">
                    <span class="price">120 €</span>
                    <button class="btn btn-main px-4 fw-semibold">
                        Add to Cart
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

</body>
</html>
