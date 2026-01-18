const worksContainer = document.querySelector(".works-container");
const totalItemsEl = document.getElementById("total-items");
const totalPriceEl = document.getElementById("total-price");
const confirmBtn = document.getElementById("confirmBtn");

let cart = [];

// ---------------- LOAD CART ----------------
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

// ---------------- STRIPE SETUP ----------------
const stripe = Stripe("STRIPE_PUBLISHABLE_KEY"); // 🔴 Vendos Publishable Key këtu
const elements = stripe.elements();
const cardElement = elements.create("card", { hidePostalCode: true });
cardElement.mount("#card-element");


// ---------------- CONFIRM ORDER ----------------
confirmBtn.addEventListener("click", async () => {
    confirmBtn.disabled = true;

    try {
        const res = await fetch("../php/create-payment-intent.php");
        const data = await res.json();
        console.log("PaymentIntent response:", data);

        if (!data.clientSecret) {
            alert("Nuk u krijua PaymentIntent: " + (data.error || "Gabim i panjohur"));
            confirmBtn.disabled = false;
            return;
        }

        const result = await stripe.confirmCardPayment(data.clientSecret, {
            payment_method: { card: cardElement }
        });

        console.log("Stripe result:", result);

        if (result.error) {
            alert("Stripe error: " + result.error.message);
            confirmBtn.disabled = false;
        } else if (result.paymentIntent.status === "succeeded") {
            const saveRes = await fetch("../php/confirm-checkout.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ payment_intent_id: result.paymentIntent.id })
            });

            const saveData = await saveRes.json();
            console.log("Save response:", saveData);

            if (saveData.success) {
                alert("Pagesa u krye me sukses ✅");
                window.location.href = "HomePage.php";
            } else {
                alert(saveData.error || "Gabim gjatë ruajtjes së porosisë");
                confirmBtn.disabled = false;
            }
        }
    } catch (err) {
        console.error("Gabim i përgjithshëm:", err);
        alert("Ndodhi një gabim gjatë pagesës.");
        confirmBtn.disabled = false;
    }
});

// ---------------- LOAD ON START ----------------
document.addEventListener("DOMContentLoaded", loadCheckout);
