<?php
require_once 'auth.php';
$conn = new mysqli("localhost", "root", "", "albart");
if ($conn->connect_error) die("Gabim lidhjeje me DB");

$artist_id = getArtistID($conn);
if (!$artist_id) die("Ky user nuk është artist ose nuk ekziston.");
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shto Produkt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f2f2f2; }
        .btn-main { background-color: #4a90e2; color: white; border: none; cursor: pointer; transition: 0.3s; }
        .btn-main:hover { background-color: #357ABD; }
        .card { max-width: 700px; margin: auto; }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="card shadow-lg rounded-4 p-4">
        <h2 class="mb-4 text-center">Shto Produkt të Ri</h2>
        <form id="productForm" enctype="multipart/form-data">
            <label for="product-name">Emri i Veprës:</label>
            <input type="text" id="product-name" name="Emri" required>

            <!-- Kategoria -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Kategoria</label>
                <div class="form-check">
                    <input class="form-check-input category" type="checkbox" value="Pikture" id="catPainting">
                    <label class="form-check-label" for="catPainting">Pikturë</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input category" type="checkbox" value="Sculpture" id="catSculpture">
                    <label class="form-check-label" for="catSculpture">Sculpture</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input category" type="checkbox" value="Handmade" id="catHandmade">
                    <label class="form-check-label" for="catHandmade">Punë artizanale</label>
                </div>
                <div class="form-check d-flex align-items-center mt-2">
                    <input class="form-check-input me-2 category" type="checkbox" id="catOther">
                    <label class="form-check-label me-2" for="catOther">Tjetër</label>
                    <input type="text" class="form-control" id="otherCategory" placeholder="Shkruaj tjetër kategorinë">
                </div>
            </div>

            <!-- Përshkrimi -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Përshkrimi</label>
                <textarea class="form-control" rows="4" id="description" placeholder="Shkruaj përshkrimin e produktit"></textarea>
            </div>

            <!-- Imazhi -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Imazhi</label>
                <input class="form-control" type="file" id="image">
            </div>

            <!-- Çmimi -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Çmimi (€)</label>
                <input type="number" class="form-control" id="price" placeholder="Vendos çmimin e produktit">
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-main px-5 fw-semibold">Confirm</button>
            </div>
            <p id="message" class="mt-2"></p>
        </form>
    </div>
</div>

<script>
    // Vetëm një checkbox mund të zgjidhet
    const checkboxes = document.querySelectorAll('.category');
    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            if (cb.checked) {
                checkboxes.forEach(other => { if (other !== cb) other.checked = false; });
            }
        });
    });

    // Submit AJAX
    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const category = Array.from(checkboxes).find(c => c.checked)?.value || '';
        const otherCategory = document.getElementById('otherCategory').value.trim();
        const description = document.getElementById('description').value.trim();
        const image = document.getElementById('image').files[0];
        const price = parseFloat(document.getElementById('price').value);

        const message = document.getElementById('message');
        message.style.color = 'red';

        if (!category && !otherCategory) { message.innerText = "Zgjidh një kategori."; return; }
        if (!description) { message.innerText = "Shkruaj përshkrimin."; return; }
        if (!image) { message.innerText = "Ngarko një imazh."; return; }
        if (!price || price <= 0) { message.innerText = "Vendos një çmim të vlefshëm."; return; }

        const formData = new FormData();
        formData.append('category', otherCategory || category);
        formData.append('description', description);
        formData.append('image', image);
        formData.append('price', price);

        fetch('../php/add-product.php', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    message.style.color = 'green';
                    message.innerText = 'Produkti u shtua me sukses!';
                    this.reset();
                } else {
                    message.innerText = data.message;
                }
            })
            .catch(err => { message.innerText = 'Gabim me serverin.'; console.error(err); });
    });
</script>
</body>
</html>
