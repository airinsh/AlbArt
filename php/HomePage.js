document.addEventListener("DOMContentLoaded", () => {
    const worksContainer = document.querySelector(".works-container");

    fetch('../php/get-product-details.php')
        .then(res => res.json())
        .then(products => {
            worksContainer.innerHTML = ""; // fshi shembujt ekzistues

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

                // Klikimi hap faqen e detajeve
                workDiv.addEventListener("click", () => {
                    window.location.href = `DetajeProdukti.php?id=${product.Produkt_ID}`;
                });

                worksContainer.appendChild(workDiv);
            });
        })
        .catch(err => console.error("Gabim fetch:", err));
});

function addToCart(event, workName){
    event.stopPropagation();
    alert("Shto në Cart: " + workName);
}
