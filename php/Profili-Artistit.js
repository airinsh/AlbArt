document.addEventListener("DOMContentLoaded", () => {

    // Merr ID e artistit nga URL (?id=NUM)
    const params = new URLSearchParams(window.location.search);
    const User_ID = params.get("id");

    if(!User_ID){
        alert("ID e artistit nuk është dhënë në URL!");
        return;
    }

    fetch(`../php/get-artist-profile.php?id=${User_ID}`)
        .then(res => res.json())
        .then(data => {
            if(data.status !== "success"){
                alert(data.message);
                return;
            }

            const artist = data.artist;

            // Emri dhe mbiemri
            document.getElementById("artist-name").innerText =
                artist.name + (artist.surname ? " " + artist.surname : "");

            // Përshkrimi
            document.getElementById("artist-description").innerText =
                artist.Description;

            // ⭐ Rating
            const score = parseFloat(artist.Vleresimi_Total) || 0;
            const stars = document.querySelectorAll(".star");
            stars.forEach((star, i) => {
                star.style.color = i < Math.floor(score) ? "gold" : "#ccc";
            });
            document.getElementById("rating-score").innerText =
                score.toFixed(1);

            // 📜 Certifikime
            const certContainer = document.getElementById("certifikime");
            if(artist.Certifikime){
                certContainer.innerHTML = `<h3>Certifikime</h3>
                <p><a href="../${artist.Certifikime}" target="_blank">Shiko certifikimin</a></p>`;
            }

            // 🎨 Veprat
            const vepratContainer = document.getElementById("veprat");
            vepratContainer.innerHTML = "<h3>Veprat</h3>"; // fshij placeholder
            if(data.produkti.length){
                data.produkti.forEach(p => {
                    const div = document.createElement("div");
                    div.innerHTML = `
                        <h4>${p.Emri}</h4>
                        <img src="../${p.Foto_Produktit}" width="180">
                        <p>${p.Pershkrimi}</p>
                    `;
                    vepratContainer.appendChild(div);
                });
            } else {
                vepratContainer.innerHTML += `<p class="placeholder">Nuk ka ende vepra.</p>`;
            }

            // 💬 Reviews
            const reviewsContainer = document.getElementById("reviews");
            reviewsContainer.innerHTML = "<h3>Reviews</h3>"; // fshij placeholder
            if(data.reviews.length){
                data.reviews.forEach(r => {
                    const div = document.createElement("div");
                    div.style.borderBottom = "1px solid #ddd";
                    div.style.marginBottom = "10px";
                    div.innerHTML = `
                        <strong>${r.klient_emri}</strong> – ${r.Vleresimi}★
                        <p>${r.Koment}</p>
                    `;
                    reviewsContainer.appendChild(div);
                });
            } else {
                reviewsContainer.innerHTML += `<p class="placeholder">Nuk ka ende vlerësime.</p>`;
            }

        })
        .catch(err => console.error(err));
});
