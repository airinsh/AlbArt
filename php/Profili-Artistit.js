document.addEventListener("DOMContentLoaded", () => {

    const profilePhoto = document.getElementById("profile-photo");
    const editLink = document.getElementById('edit-photo-link');
    const fileInput = document.getElementById('photo-input');

    // Hap file picker
    editLink.addEventListener('click', e => {
        e.preventDefault();
        fileInput.value = "";
        fileInput.click();
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => profilePhoto.src = e.target.result;
        reader.readAsDataURL(file);

        const formData = new FormData();
        formData.append("photo", file);

        fetch("../php/update-artist-profile-photo.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(resp => {
                if (resp.status !== "success") {
                    alert("Gabim gjatë ruajtjes së fotos: " + resp.message);
                } else {
                    console.log("Foto u ruajt në DB: " + resp.path);
                }
            })
            .catch(err => console.error("Gabim gjatë POST foto:", err));
    });

    // FETCH PROFILI I ARTISTIT – pa id nga URL
    fetch("../php/get-artist-profile.php")
        .then(res => res.json())
        .then(data => {
            if (data.status !== "success") {
                alert(data.message);
                return;
            }

            const artist = data.artist;
            document.getElementById("artist-name").innerText =
                artist.name + (artist.surname ? " " + artist.surname : "");
            document.getElementById("artist-description").innerText = artist.Description;
            if (artist.Fotografi) profilePhoto.src = "../" + artist.Fotografi;

            // Rating
            const score = parseFloat(artist.Vleresimi_Total) || 0;
            const stars = document.querySelectorAll(".star");
            stars.forEach((star, i) => star.style.color = i < Math.floor(score) ? "gold" : "#ccc");
            document.getElementById("rating-score").innerText = score.toFixed(1);

            // Certifikime
            const certContainer = document.getElementById("certifikime");
            certContainer.innerHTML = "<h3>Certifikime</h3>";
            if (artist.Certifikime) {
                certContainer.innerHTML += `<p><a href="../${artist.Certifikime}" target="_blank">Shiko certifikimin</a></p>`;
            } else {
                certContainer.innerHTML += `<p class="placeholder">Nuk ka certifikime.</p>`;
            }

            // Veprat
            const vepratContainer = document.getElementById("veprat");
            vepratContainer.innerHTML = "<h3>Veprat</h3>";
            if (data.produkti && data.produkti.length) {
                data.produkti.forEach(p => {
                    const div = document.createElement("div");
                    div.innerHTML = `
    <div class="work-info">
        <p class="category">${p.Kategoria_Emri ?? ''}</p>
        <h4 class="name">${p.Emri}</h4>
        <img src="../uploads/${p.Foto_Produktit}" alt="${p.Emri}">
        <p>${p.Pershkrimi}</p>
        <p class="price">€${p.Cmimi}</p>
    </div>
`;
                    vepratContainer.appendChild(div);
                });
            } else {
                vepratContainer.innerHTML += `<p class="placeholder">Nuk ka ende vepra.</p>`;
            }


            // Reviews
            const reviewsContainer = document.getElementById("reviews");
            reviewsContainer.innerHTML = "<h3>Reviews</h3>";
            if (data.reviews && data.reviews.length) {
                data.reviews.forEach(r => {
                    const div = document.createElement("div");
                    div.style.borderBottom = "1px solid #ddd";
                    div.style.marginBottom = "10px";
                    div.style.paddingBottom = "10px";
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
        .catch(err => console.error("Gabim gjatë marrjes së të dhënave:", err));
});
