// checkout.js

const worksContainer = document.querySelector(".works-container");
const totalItemsEl = document.getElementById("total-items");
const totalPriceEl = document.getElementById("total-price");

let cart = []; // do të mbajmë produktet që ngarkohen nga serveri

// Përditëson summary (total items & total price)
function updateSummary() {
    const totalItems = cart.length;
    const totalPrice = cart.reduce((sum, p) => sum + parseFloat(p.Cmimi), 0);

    totalItemsEl.textContent = totalItems;
    totalPriceEl.textContent = totalPrice.toFixed(2);
}

// Krijon HTML për secilën vepër me Delete button
function createWorkItem(product) {
    const workDiv = document.createElement("div");
    workDiv.classList.add("work-card", "d-flex", "mb-3", "p-3", "shadow-sm", "rounded");

    workDiv.innerHTML = `
        <div class="work-image me-3">
            <img src="${product.Foto_Produktit}" alt="${product.Emri}" style="width:120px; height:120px; object-fit:cover; border-radius:8px;">
        </div>
        <div class="work-info d-flex flex-column justify-content-between flex-grow-1">
            <h5 class="work-name mb-1">${product.Emri}</h5>
            <div class="artist-name mb-1">
                <strong>Artist:</strong> ${product.Artist_Name} ${product.Artist_Surname}
            </div>
            <p class="category mb-1">Kategori: ${product.Kategori_Emri}</p>
            <p class="price mb-0">Cmimi: $${product.Cmimi}</p>
        </div>
        <div class="ms-3 d-flex align-items-start">
            <button class="btn btn-danger btn-sm delete-btn">Delete</button>
        </div>
    `;

    // Shto event listener për Delete button
    const deleteBtn = workDiv.querySelector(".delete-btn");
    deleteBtn.addEventListener("click", () => {
        // Confirm për siguri (opsionale)
        if (!confirm("A jeni të sigurt që doni ta fshini këtë produkt nga shporta?")) return;

        // Thirr PHP për të fshirë produktin nga cart
        fetch('../php/remove-from-cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ Produkt_ID: product.Produkt_ID })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Fshij div-in nga DOM
                    workDiv.remove();
                    // Përditëso cart dhe summary
                    cart = cart.filter(p => p.Produkt_ID !== product.Produkt_ID);
                    updateSummary();
                } else {
                    alert("Gabim gjatë heqjes nga shporta: " + data.error);
                }
            })
            .catch(err => console.error("Gabim fetch:", err));
    });

    return workDiv;
}

// Ngarkon produktet nga serveri (checkout: produktet në karte)
function loadWorks() {
    fetch('../php/get-details-for-shopping-cart.php') // ky php duhet të kthejë produktet që janë në cart
        .then(res => res.json())
        .then(products => {
            cart = products; // i ruajmë në cart për summary
            worksContainer.innerHTML = "";
            products.forEach(product => {
                const workDiv = createWorkItem(product);
                worksContainer.appendChild(workDiv);
            });
            updateSummary();
        })
        .catch(err => console.error("Gabim fetch:", err));
}

// Return to Homepage
document.getElementById("returnBtn").addEventListener("click", () => {
    window.location.href = "../php/HomePage.php"; // faqja kryesore
});

// Load kur DOM është gati
document.addEventListener("DOMContentLoaded", () => {
    loadWorks();
});
