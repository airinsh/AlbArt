document.addEventListener("DOMContentLoaded", () => {

    fetch("../php/get-klient-profile.php")
        .then(res => res.json())
        .then(data => {
            console.log(data); // ⬅️ për debug nëse prap nuk shfaqet
            if (data.status !== "success") {
                alert(data.message);
                return;
            }

            const k = data.klient;

            document.getElementById("klient-name").innerText =
                k.name + " " + k.surname;

            document.getElementById("klient-email").innerText =
                "Email: " + k.email;

            /* ================= BLERJET ================= */
            const blerjetDiv = document.getElementById("blerjet");
            blerjetDiv.innerHTML = "<h3>Blerjet e mia</h3>";

            if (data.blerjet.length) {
                data.blerjet.forEach(b => {
                    blerjetDiv.innerHTML += `
                        <div style="margin-bottom:15px">
                            <img src="../${b.Foto_Produktit}" width="120">
                            <p><strong>${b.Emri}</strong></p>
                            <p>${parseFloat(b.Cmimi).toFixed(2)} €</p>
                        </div>
                    `;
                });
            } else {
                blerjetDiv.innerHTML += `<p class="placeholder">Nuk ka ende blerje.</p>`;
            }

            /* ================= REVIEWS ================= */
            const reviewsDiv = document.getElementById("reviews");
            reviewsDiv.innerHTML = "<h3>Vlerësimet e mia</h3>";

            if (data.reviews.length) {
                data.reviews.forEach(r => {
                    reviewsDiv.innerHTML += `
                        <p><strong>${r.Vleresimi}★</strong> – ${r.Koment}</p>
                    `;
                });
            } else {
                reviewsDiv.innerHTML += `<p class="placeholder">Nuk ka ende vlerësime.</p>`;
            }

        })
        .catch(err => console.error("Gabim JS:", err));
});
