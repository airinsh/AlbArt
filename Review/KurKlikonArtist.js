document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const artistId = params.get('id');
    if (!artistId) return console.error("ID e artistit nuk u gjet në URL");

    // Selektorët
    const artistPhoto = document.getElementById("artist-photo");
    const artistName = document.getElementById("artist-name");
    const artistDescription = document.getElementById("artist-description");
    const ratingNumber = document.getElementById("rating-number");
    const ratingStars = document.getElementById("rating-stars");

    const vepratContainer = document.getElementById("veprat");
    const certContainer = document.getElementById("certifikime");
    const reviewsContainer = document.getElementById("reviews");
    const reviewBtn = document.getElementById("review-btn");

    fetch(`ajax/get-single-artist-details.php?id=${artistId}`)
        .then(res => res.json())
        .then(artist => {
            if (artist.error) {
                console.error(artist.error);
                return;
            }

            // =========================
            // FOTO ARTISTIT
            // =========================
            artistPhoto.src = artist.Fotografi ? "../" + artist.Fotografi : "../img/default-artist.png";

            // =========================
            // EMRI DHE DESCRIPTION
            // =========================
            artistName.textContent = artist.Artist_Name + " " + artist.Artist_Surname;
            artistDescription.textContent = artist.Description || "Nuk ka përshkrim.";

            // =========================
            // RATING – mbush yjet
            // =========================
            const rating = parseFloat(artist.Vleresimi_Total) || 0;
            ratingNumber.textContent = rating.toFixed(1);

            const fullStars = Math.floor(rating);
            const halfStar = rating - fullStars >= 0.5 ? 1 : 0;
            const emptyStars = 5 - fullStars - halfStar;
            ratingStars.textContent = "★".repeat(fullStars) + (halfStar ? "½" : "") + "☆".repeat(emptyStars);

            // BUTONI REVIEW
            reviewBtn.href = `../Review/KlientiJepReview.php?Artist_ID=${artist.Artist_ID}`;

            // =========================
            // VEPRAT
            // =========================
            vepratContainer.innerHTML = "";
            if (artist.veprat && artist.veprat.length) {
                const row = document.createElement("div");
                row.classList.add("row", "g-3");

                artist.veprat.forEach(p => {
                    const col = document.createElement("div");
                    col.classList.add("col-md-4");

                    col.innerHTML = `
                        <div class="card h-100 shadow-sm">
                            <img src="../uploads/${p.Foto_Produktit}" class="card-img-top" alt="${p.Emri}">
                            <div class="card-body">
                                <h5 class="card-title">${p.Emri}</h5>
                                <p class="card-text"><small class="text-muted">${p.Kategoria_Emri ?? ''}</small></p>
                                <p class="card-text">${p.Pershkrimi}</p>
                                <p class="card-text fw-bold">€${p.Cmimi}</p>
                            </div>
                        </div>
                    `;
                    row.appendChild(col);
                });
                vepratContainer.appendChild(row);
            } else {
                vepratContainer.innerHTML = `<p class="placeholder">Nuk ka ende vepra.</p>`;
            }

            // =========================
            // CERTIFIKIMET
            // =========================
            certContainer.innerHTML = "";
            if (artist.Certifikime && artist.Certifikime.trim() !== "") {
                const certs = artist.Certifikime.split(',');
                certs.forEach(cert => {
                    const link = document.createElement("a");
                    link.href = "../" + cert.trim();
                    link.target = "_blank";
                    link.textContent = cert.trim().split('/').pop();
                    link.style.display = "block";
                    certContainer.appendChild(link);
                });
            } else {
                certContainer.innerHTML = `<p class="placeholder">Nuk ka certifikime të regjistruara.</p>`;
            }

            // =========================
            // REVIEWS
            // =========================
            reviewsContainer.innerHTML = "";
            if (artist.Reviews && artist.Reviews.length) {
                artist.Reviews.forEach(r => {
                    const div = document.createElement("div");
                    div.classList.add("mb-2", "p-2");
                    div.style.borderBottom = "1px solid #ddd";
                    div.innerHTML = `
                        <strong>${r.klient_emri} ${r.klient_mbiemri}</strong> – ${r.Vleresimi}★
                        <p>${r.Koment}</p>
                    `;
                    reviewsContainer.appendChild(div);
                });
            } else {
                reviewsContainer.innerHTML = `<p class="placeholder">Nuk ka ende vlerësime.</p>`;
            }

        })
        .catch(err => console.error("Gabim fetch artist:", err));
});
