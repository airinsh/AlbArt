document.addEventListener("DOMContentLoaded", () => {
    const profilePhoto = document.getElementById("profile-photo");
    const editLink = document.getElementById('edit-photo-link');
    const fileInput = document.getElementById('photo-input');

    // ======= NGARKO FOTO PROFILI =======
    editLink.addEventListener('click', e => {
        e.preventDefault();
        fileInput.value = "";
        fileInput.click();
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (!file) return;

        // Shfaq foto menjëherë
        const reader = new FileReader();
        reader.onload = e => profilePhoto.src = e.target.result;
        reader.readAsDataURL(file);

        // POST në PHP për ruajtje
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
                    profilePhoto.src = "../" + resp.path; // path relativ për shfaqje
                }
            })
            .catch(err => console.error("Gabim gjatë POST foto:", err));
    });

    // ======= FETCH PROFILI I ARTISTIT =======
    fetch("../php/get-artist-profile.php")
        .then(res => res.json())
        .then(data => {
            if (data.status !== "success") {
                alert(data.message);
                return;
            }

            const artist = data.artist;

            // Emri dhe mbiemri
            document.getElementById("artist-name").innerText =
                artist.name + (artist.surname ? " " + artist.surname : "");

            // Përshkrimi
            document.getElementById("artist-description").innerText = artist.Description;

            // Foto
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
                    div.classList.add("work-info-container");
                    div.innerHTML = `
                        <div class="work-info">
                            <p class="category">${p.Kategoria_Emri ?? ''}</p>
                            <h4 class="name">${p.Emri}</h4>
                            <img src="../uploads/${p.Foto_Produktit}" alt="${p.Emri}">
                            <p class="desc">${p.Pershkrimi}</p>
                            <p class="price">€${p.Cmimi}</p>
                            <div class="work-actions">
                                <button class="edit-work-btn" data-id="${p.Produkt_ID}">Edit</button>
                                <button class="delete-work-btn" data-id="${p.Produkt_ID}">Delete</button>
                            </div>
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

    // ======= EDIT EMRI =======
    const editNameBtn = document.getElementById("edit-name-btn");
    const modal = document.getElementById("editNameModal");
    const cancelBtn = document.getElementById("cancel-name-btn");
    const saveBtn = document.getElementById("save-name-btn");

    editNameBtn.addEventListener("click", () => {
        const fullName = document.getElementById("artist-name").innerText;
        const parts = fullName.split(" ");
        document.getElementById("edit-name-input").value = parts[0] || "";
        document.getElementById("edit-surname-input").value = parts[1] || "";
        modal.style.display = "flex";
    });

    cancelBtn.addEventListener("click", () => modal.style.display = "none");

    saveBtn.addEventListener("click", () => {
        const newName = document.getElementById("edit-name-input").value.trim();
        const newSurname = document.getElementById("edit-surname-input").value.trim();
        if (!newName || !newSurname) return alert("Ju lutem plotësoni emrin dhe mbiemrin.");

        const formData = new FormData();
        formData.append("type", "name");
        formData.append("name", newName);
        formData.append("surname", newSurname);

        fetch("../php/update-artist-profile.php", { method: "POST", body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    document.getElementById("artist-name").innerText = newName + " " + newSurname;
                    modal.style.display = "none";
                } else {
                    alert(data.message || "Ndodhi një gabim.");
                }
            })
            .catch(() => alert("Gabim gjatë komunikimit me serverin."));
    });

    // ======= EDIT DESCRIPTION =======
    const editDescBtn = document.getElementById("edit-desc-btn");
    const descModal = document.getElementById("editDescModal");
    const cancelDescBtn = document.getElementById("cancel-desc-btn");
    const saveDescBtn = document.getElementById("save-desc-btn");

    editDescBtn.addEventListener("click", () => {
        document.getElementById("edit-desc-input").value =
            document.getElementById("artist-description").innerText.trim();
        descModal.style.display = "flex";
    });
    cancelDescBtn.addEventListener("click", () => descModal.style.display = "none");

    saveDescBtn.addEventListener("click", () => {
        const newDesc = document.getElementById("edit-desc-input").value.trim();
        if (!newDesc) return alert("Përshkrimi nuk mund të jetë bosh.");

        const formData = new FormData();
        formData.append("type", "description");
        formData.append("description", newDesc);

        fetch("../php/update-artist-profile.php", { method: "POST", body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    document.getElementById("artist-description").innerText = newDesc;
                    descModal.style.display = "none";
                } else {
                    alert(data.message || "Ndodhi një gabim.");
                }
            })
            .catch(() => alert("Gabim gjatë komunikimit me serverin."));
    });

    // ======= EDIT & DELETE VEPRAT =======
    let currentWorkId = null;
    const editWorkModal = document.getElementById("editWorkModal");
    const saveWorkBtn = document.getElementById("save-work-btn");
    const cancelWorkBtn = document.getElementById("cancel-work-btn");

    document.addEventListener("click", e => {
        // EDIT
        if (e.target.classList.contains("edit-work-btn")) {
            const workDiv = e.target.closest(".work-info");
            currentWorkId = e.target.dataset.id;
            document.getElementById("edit-work-name").value = workDiv.querySelector(".name").innerText;
            document.getElementById("edit-work-desc").value = workDiv.querySelector(".desc").innerText;
            document.getElementById("edit-work-price").value = workDiv.querySelector(".price").innerText.replace("€", "");
            editWorkModal.style.display = "flex";
        }

        // DELETE
        if (e.target.classList.contains("delete-work-btn")) {
            const workId = e.target.dataset.id;
            if (!workId) return alert("ID e veprës nuk është e saktë.");
            if (!confirm("A jeni të sigurt që doni ta fshini këtë vepër?")) return;

            const formData = new FormData();
            formData.append("action", "delete");
            formData.append("id", workId);

            fetch("../php/update-artist-profile.php", { method: "POST", body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") e.target.closest(".work-info-container").remove();
                    else alert(data.message || "Gabim gjatë fshirjes.");
                })
                .catch(() => alert("Gabim gjatë komunikimit me serverin."));
        }
    });

    // Ruaj edit work
    cancelWorkBtn.addEventListener("click", () => editWorkModal.style.display = "none");

    saveWorkBtn.addEventListener("click", () => {
        const name = document.getElementById("edit-work-name").value.trim();
        const desc = document.getElementById("edit-work-desc").value.trim();
        const price = parseFloat(document.getElementById("edit-work-price").value.trim());

        if (!name || !desc || isNaN(price) || price <= 0) return alert("Plotësoni të gjitha fushat me vlerë të saktë.");
        if (!currentWorkId) return alert("ID e veprës nuk është e saktë.");

        const formData = new FormData();
        formData.append("action", "edit");
        formData.append("id", currentWorkId.toString());
        formData.append("name", name);
        formData.append("desc", desc);
        formData.append("price", price.toString());

        fetch("../php/update-artist-profile.php", { method: "POST", body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    const workDiv = document.querySelector(`.edit-work-btn[data-id='${currentWorkId}']`).closest(".work-info");
                    workDiv.querySelector(".name").innerText = name;
                    workDiv.querySelector(".desc").innerText = desc;
                    workDiv.querySelector(".price").innerText = "€" + price;
                    editWorkModal.style.display = "none";
                } else alert(data.message || "Gabim gjatë ruajtjes së veprës.");
            })
            .catch(() => alert("Gabim gjatë komunikimit me serverin."));
    });

});
document.addEventListener("DOMContentLoaded", () => {

    const modifyBtn = document.getElementById("modifyBtn");
    const modal = document.getElementById("editModal");
    const closeModal = document.getElementById("closeModal");

    if (!modifyBtn) {
        console.error("❌ Modify button nuk u gjet");
        return;
    }

    // HAP POPUP
    modifyBtn.addEventListener("click", () => {
        modal.style.display = "flex";

        // 👉 MBUSH TE DHENAT NGA PROFILI (shembull)
        document.getElementById("name").value = artist.name;
        document.getElementById("surname").value = artist.surname;
        document.getElementById("email").value = artist.email;
    });

    // MBYLL POPUP
    closeModal.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Mbyll kur klikon jashtë
    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});