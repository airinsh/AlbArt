document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    if (!id) {
        alert("Produkt nuk u gjet (ID nuk u dërgua)");
        return;
    }

    // Merr detajet e produktit nga serveri
    fetch(`../php/get-single-product-details.php?id=${id}`)
        .then(res => res.json())
        .then(product => {
            if (product.error) {
                alert(product.error);
                return;
            }

            // Popullo të dhënat e produktit
            document.getElementById('product-name').textContent = product.Emri;
            document.getElementById('product-img').src = product.Foto_Produktit;
            document.getElementById('product-desc').textContent = product.Pershkrimi;
            document.getElementById('product-category').textContent = product.Kategori_Emri;
            document.getElementById('product-price').textContent = product.Cmimi + " €";

            // Popullo të dhënat e artistit
            document.getElementById('artist-name').textContent = product.Artist_Name + " " + product.Artist_Surname;
            document.getElementById('artist-img').textContent = product.Artist_Name + " " + product.Artist_Surname;
            document.getElementById('artist-img').src = "/" + product.Artist_Foto + "?t=" + new Date().getTime();


            // Select butonin Add to Cart
            const addToCartBtn = document.querySelector('.btn-main');

            // Funksion Toggle Add/Remove
            addToCartBtn.addEventListener('click', () => {
                if (addToCartBtn.textContent === "Add to Cart") {
                    // Shto produktin në shportë
                    fetch('../php/add-to-cart.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ Produkt_ID: product.Produkt_ID })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                addToCartBtn.textContent = "Added";
                                addToCartBtn.style.backgroundColor = "#4CAF50"; // gjelbër
                            } else {
                                alert("Gabim gjatë shtimit në shportë: " + data.error);
                            }
                        })
                        .catch(err => console.error("Gabim fetch:", err));

                } else {
                    // Heq produktin nga shporta
                    fetch('../php/remove-from-cart.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ Produkt_ID: product.Produkt_ID })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                addToCartBtn.textContent = "Add to Cart";
                                addToCartBtn.style.backgroundColor = "#a2b5cc"; // ngjyra fillestare
                            } else {
                                alert("Gabim gjatë heqjes nga shporta: " + data.error);
                            }
                        })
                        .catch(err => console.error("Gabim fetch:", err));
                }
            });

        })
        .catch(err => console.error("Gabim fetch:", err));
});
