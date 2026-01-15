<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Profile</title>%µ

    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f2f2f2;
        }

        .main-color {
            background-color: #a2b5cc !important;
        }

        .artist-photo {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
        }

        .stars {
            color: gold;
            font-size: 20px;
        }

        .section-title {
            color: #a2b5cc;
        }
    </style>
</head>
<body>

<!-- TOOLBAR -->
<nav class="navbar navbar-dark main-color px-4">
    <span class="navbar-brand mb-0 h1">ARTIST</span>
</nav>

<!-- PAGE -->
<div class="container my-5">
    <div class="card shadow-lg rounded-4 overflow-hidden">

        <!-- HEADER ARTIST -->
        <div class="main-color text-white text-center p-4">
            <img src="Piktura.jpeg" alt="Foto Artisti" class="artist-photo mb-3">
            <h2 class="mb-1">Emri i Artistit</h2>
            <p class="mb-2 opacity-75">
                Artist bashkëkohor i specializuar në vepra unike dhe të personalizuara.
            </p>

            <!-- VLERËSIMI -->
            <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                <span class="fw-bold">4.5</span>
                <span class="stars">★★★★☆</span>
            </div>

            <!-- BUTON REVIEW -->
            <button class="btn btn-light fw-semibold px-4">
                Review
            </button>
        </div>

        <!-- CONTENT -->
        <div class="card-body">

            <div class="mb-4">
                <h4 class="section-title fw-semibold">Veprat</h4>
                <div class="bg-light p-3 rounded-3">
                    Lista e veprave artistike do të shfaqet këtu.
                </div>
            </div>

            <div class="mb-4">
                <h4 class="section-title fw-semibold">Certifikime</h4>
                <div class="bg-light p-3 rounded-3">
                    Certifikime, diploma ose çmime artistike.
                </div>
            </div>

            <div>
                <h4 class="section-title fw-semibold">Reviews</h4>
                <div class="bg-light p-3 rounded-3">
                    Vlerësimet dhe komentet nga klientët.
                </div>
            </div>

        </div>

    </div>
</div>

</body>
</html>
