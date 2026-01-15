<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review-Artistit</title>

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
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
        }

        .star {
            font-size: 32px;
            color: #ccc;
            cursor: pointer;
        }
    </style>
</head>
<body>

<!-- TOOLBAR -->
<nav class="navbar navbar-dark main-color px-4">
    <span class="navbar-brand mb-0 h1">Review</span>
</nav>

<!-- PAGE -->
<div class="container my-5">
    <div class="card shadow-lg rounded-4 p-4">

        <!-- ARTIST INFO -->
        <div class="text-center mb-4">
            <img src="Piktura.jpeg" class="artist-photo mb-3" alt="Artist">
            <h3 class="mb-1">Emri i Artistit</h3>
        </div>

        <!-- VLERËSIMI -->
        <div class="text-center mb-4">
            <p class="fw-semibold mb-2">Vlerësimi</p>
            <div class="d-flex justify-content-center gap-2">
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
                <span class="star">★</span>
            </div>
            <small class="text-muted">Zgjidh vlerësimin (aktivizohet më vonë)</small>
        </div>

        <!-- KOMENTE -->
        <div class="mb-4">
            <label class="form-label fw-semibold">Komente</label>
            <textarea class="form-control" rows="4"
                      placeholder="Shkruaj mendimin tënd këtu..."></textarea>
        </div>

        <!-- BUTON -->
        <div class="text-center">
            <button class="btn main-color text-white px-5 fw-semibold">
                Dërgo
            </button>
        </div>

    </div>
</div>

</body>
</html>
