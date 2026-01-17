document.addEventListener("DOMContentLoaded", () => {

    // NGARKON VEPRAT (PRODUCTS)

    const worksContainer = document.querySelector(".works-container");

    fetch('../php/get-product-details.php')
        .then(res => res.json())
        .then(products => {
            worksContainer.innerHTML = "";

            products.forEach(product => {
                const workDiv = document.createElement("div");
                workDiv.classList.add("work-item");

                workDiv.innerHTML = `
                    <div class="work-image">
                        <img src="${product.Foto_Produktit}" alt="${product.Emri}">
                    </div>
                    <div class="work-info">
                        <p class="category">${product.Kategori_Emri}</p>
                        <p class="name">${product.Emri}</p>
                        <p class="price">$${product.Cmimi}</p>
                        <button class="add-cart" onclick="addToCart(event,'${product.Emri}')">Cart</button>
                    </div>
                `;

                // Klikimi hap faqen e detajeve të produktit
                workDiv.addEventListener("click", () => {
                    window.location.href = `DetajeProdukti.php?id=${product.Produkt_ID}`;
                });

                worksContainer.appendChild(workDiv);
            });
        })
        .catch(err => console.error("Gabim fetch veprat:", err));


    // NGARKON ARTISTËT

    const artistsContainer = document.querySelector(".artists-container");

    fetch('../php/get-artist-photo.php')
        .then(res => res.json())
        .then(artists => {
            artistsContainer.innerHTML = ""; // fshi shembujt ekzistues

            artists.forEach(artist => {
                const artistBtn = document.createElement("button");
                artistBtn.classList.add("artist");

                   artistBtn.innerHTML = `
                    <img src="../${artist.Fotografi}" alt="${artist.name} ${artist.surname}">
                    <p>${artist.name} ${artist.surname}</p>
                `;


                // Klikimi hap faqen e detajeve të artistit
                artistBtn.addEventListener("click", () => {
                    window.location.href = `KurKlikonArtist.php?id=${artist.Artist_ID}`;
                });

                artistsContainer.appendChild(artistBtn);
            });
        })
        .catch(err => console.error("Gabim fetch artistët:", err));
});



function addToCart(event, workName){
    event.stopPropagation(); // ndalon klikimin e kutisë që hap faqen
    alert("Shto në Cart: " + workName);
}
