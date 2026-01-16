<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>

    <!-- BOOTSTRAP CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f2f2f2;
        }

        .main-color {
            background-color: #4a90e2 !important;
        }

        .btn-main {
            background-color: #4a90e2;
            color: white;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-main:hover {
            background-color: #357ABD;
            color: white;
        }

        .card {
            max-width: 700px;
            margin: auto; /* center horizontally */
        }
    </style>
</head>
<body>

<!-- TOOLBAR -->
<nav class="navbar navbar-dark main-color px-4">
    <span class="navbar-brand mb-0 h1">Add Product</span>
</nav>

<!-- PAGE -->
<div class="container my-5">
    <div class="card shadow-lg rounded-4 p-4">

        <h2 class="mb-4 text-center">Shto Produkt të Ri</h2>

        <form>

            <!-- KATEGORIA -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Kategoria</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Pikture" id="catPainting">
                    <label class="form-check-label" for="catPainting">Pikturë</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Sculpture" id="catSculpture">
                    <label class="form-check-label" for="catSculpture">Sculpture</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="Handmade" id="catHandmade">
                    <label class="form-check-label" for="catHandmade">Punë artizanale</label>
                </div>
                <div class="form-check d-flex align-items-center mt-2">
                    <input class="form-check-input me-2" type="checkbox" id="catOther">
                    <label class="form-check-label me-2" for="catOther">Tjetër</label>
                    <input type="text" class="form-control" placeholder="Shkruaj tjetër kategorinë">
                </div>
            </div>

            <!-- PERSHKRIMI -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Përshkrimi</label>
                <textarea class="form-control" rows="4" placeholder="Shkruaj përshkrimin e produktit"></textarea>
            </div>

            <!-- IMAGJI -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Imazhi</label>
                <input class="form-control" type="file">
            </div>

            <!-- CMIMI -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Çmimi (€)</label>
                <input type="number" class="form-control" placeholder="Vendos çmimin e produktit">
            </div>

            <!-- CONFIRM BUTTON -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-main px-5 fw-semibold">Confirm</button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
