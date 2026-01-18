<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Profile</title>

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

        .placeholder {
            color: #888;
            font-style: italic;
        }

        .work-info img {
            width: 100%;
            max-width: 200px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .work-info {
            margin-bottom: 15px;
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
            <img src="../img/default-artist.png" alt="Foto Artisti" class="artist-photo mb-3" id="artist-photo">
            <h2 class="mb-1" id="artist-name">Duke u ngarkuar...</h2>
            <p class="mb-2 opacity-75" id="artist-description">
                Përshkrimi i artistit do të shfaqet këtu.
            </p>

            <!-- VLERËSIMI -->
            <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                <span class="fw-bold" id="rating-number">0.0</span>
                <span class="stars" id="rating-stars">☆☆☆☆☆</span>
            </div>

            <!-- BUTON REVIEW -->
            <a href="#" class="btn btn-light fw-semibold px-4" id="review-btn">
                Review
            </a>
        </div>

        <!-- CONTENT -->
        <div class="card-body">

            <!-- Veprat -->
            <div class="mb-4">
                <h4 class="section-title fw-semibold">Veprat</h4>
                <div class="bg-light p-3 rounded-3" id="veprat">
                    <p class="placeholder">Nuk ka ende vepra.</p>
                </div>
            </div>

            <!-- Certifikimet -->
            <div class="mb-4">
                <h4 class="section-title fw-semibold">Certifikime</h4>
                <div class="bg-light p-3 rounded-3" id="certifikime">
                    <p class="placeholder">Nuk ka certifikime.</p>
                </div>
            </div>

            <!-- Reviews -->
            <div>
                <h4 class="section-title fw-semibold">Reviews</h4>
                <div class="bg-light p-3 rounded-3" id="reviews">
                    <p class="placeholder">Nuk ka ende vlerësime.</p>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- ================= JS ================= -->
<script src="../php/KurKlikonArtist.js"></script>

</body>
</html>
