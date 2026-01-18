const worksContainer = document.querySelector(".works-container");
const totalItemsEl = document.getElementById("total-items");
const totalPriceEl = document.getElementById("total-price");
const confirmBtn = document.getElementById("confirmBtn");

let cart = [];


// LOAD CART

function loadCheckout() {
    fetch('../php/get-details-for-shopping-cart.php')
        .then(res => res.json())
        .then(products => {
            cart = products;
            worksContainer.innerHTML = "";

            let total = 0;

            products.forEach(p => {
                total += parseFloat(p.Cmimi);

                const div = document.createElement("div");
                div.classList.add("work-card");

                div.innerHTML = `
                    <img src="${p.Foto_Produktit}">
                    <div>
                        <h6 class="mb-1">${p.Emri}</h6>
                        <p class="mb-1">${p.Kategori_Emri}</p>
                        <strong>$${p.Cmimi}</strong>
                    </div>
                `;

                worksContainer.appendChild(div);
            });

            totalItemsEl.textContent = cart.length;
            totalPriceEl.textContent = total.toFixed(2);
        })
        .catch(err => console.error("Gabim checkout:", err));
}


// CONFIRM ORDER

confirmBtn.addEventListener("click", () => {
    const cardInput = document.getElementById("cardNumber");
    const card = cardInput.value.trim();

    // vetëm 16 shifra
    const cardRegex = /^\d{16}$/;

    if (!cardRegex.test(card)) {
        alert("Numri i kartës duhet të përmbajë saktësisht 16 shifra!");
        cardInput.focus();
        return;
    }

    fetch('../php/confirm-checkout.php', {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ card })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Porosia u krye me sukses ✅");
                window.location.href = "HomePage.php";
            } else {
                alert(data.error || "Gabim gjatë kryerjes së porosisë");
            }
        })
        .catch(err => console.error("Gabim confirm:", err));
});


// LOAD ON START

document.addEventListener("DOMContentLoaded", loadCheckout);
