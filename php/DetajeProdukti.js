document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    if(!id){
        alert("Produkt nuk u gjet (ID nuk u dërgua)");
        return;
    }

    fetch(`../php/get-single-product-details.php?id=${id}`)
        .then(res => res.json())
        .then(product => {
            if(product.error){
                alert(product.error);
                return;
            }

            document.getElementById('product-name').textContent = product.Emri;
            document.getElementById('product-img').src = product.Foto_Produktit;
            document.getElementById('product-desc').textContent = product.Pershkrimi;
            document.getElementById('product-category').textContent = product.Kategori_Emri;
            document.getElementById('product-price').textContent = product.Cmimi + " €";


            document.getElementById('artist-name').textContent = product.Artist_Name + " " + product.Artist_Surname;
            document.getElementById('artist-img').src = product.Artist_Foto;
            document.getElementById('artist-profile-btn').addEventListener('click', () => {
                window.location.href = `ArtistProfile.php?id=${product.Artist_ID}`;
            });
        })
        .catch(err => console.error("Gabim fetch:", err));
});
