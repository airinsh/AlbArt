document.addEventListener("DOMContentLoaded", () => {
    const profilePhoto = document.getElementById("profile-photo");
    const editLink = document.getElementById('edit-photo-link');
    const fileInput = document.getElementById('photo-input');

    // ngarkimi fotos profilit
    editLink.addEventListener('click', e => {
        e.preventDefault();
        fileInput.value = "";
        fileInput.click();
    });

    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (!file) return;

        // Shfaqja e fotos menjehere
        const reader = new FileReader();
        reader.onload = e => profilePhoto.src = e.target.result;
        reader.readAsDataURL(file);

        // post ne php per save
        const formData = new FormData();
        formData.append("photo", file);

        fetch("ajax/update-artist-profile-photo.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(resp => {
                if (resp.status !== "success") {
                    alert("Gabim gjate ruajtjes se fotos: " + resp.message);
                } else {
                    console.log("Foto u ruajt ne DB: " + resp.path);
                    profilePhoto.src = "../" + resp.path; // path relativ per shfaqje
                }
            })
            .catch(err => console.error("Gabim gjate POST foto:", err));
    });

    // fetch profili artistit
    fetch("ajax/get-artist-profile.php")
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

            // Pershkrimi
            document.getElementById("artist-description").innerText = artist.Description;

            // Fotoja
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

            // REVIEWS
            const reviewsContainer = document.getElementById("reviews");

            reviewsContainer.innerHTML = "<h3>Reviews</h3>";

            if (data.reviews && data.reviews.length > 0) {
                data.reviews.forEach(r => {
                    const div = document.createElement("div");
                    div.classList.add("review-item");
                    div.innerHTML = `
            <strong>${r.klient_emri}</strong> – ${r.Vleresimi}★
            <p>${r.Koment}</p>
        `;
                    reviewsContainer.appendChild(div);
                });
            } else {
                reviewsContainer.innerHTML += `<p class="placeholder">Nuk ka ende vleresime.</p>`;
            }


            // editimi emrit
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
                if (!newName || !newSurname) return alert("Ju lutem plotesoni emrin dhe mbiemrin.");

                const formData = new FormData();
                formData.append("type", "name");
                formData.append("name", newName);
                formData.append("surname", newSurname);

                fetch("ajax/update-artist-profile.php", {method: "POST", body: formData})
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            document.getElementById("artist-name").innerText = newName + " " + newSurname;
                            modal.style.display = "none";
                        } else {
                            alert(data.message || "Ndodhi nje gabim.");
                        }
                    })
                    .catch(() => alert("Gabim gjate komunikimit me serverin."));
            });

            // editimi pershkrimit
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
                if (!newDesc) return alert("Pershkrimi nuk mund te jete bosh.");

                const formData = new FormData();
                formData.append("type", "description");
                formData.append("description", newDesc);

                fetch("ajax/update-artist-profile.php", {method: "POST", body: formData})
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            document.getElementById("artist-description").innerText = newDesc;
                            descModal.style.display = "none";
                        } else {
                            alert(data.message || "Ndodhi nje gabim.");
                        }
                    })
                    .catch(() => alert("Gabim gjate komunikimit me serverin."));
            });

            // EDIT & DELETE VEPRAT
            let currentWorkId = null;
            const editWorkModal = document.getElementById("editWorkModal");
            const saveWorkBtn = document.getElementById("save-work-btn");
            const cancelWorkBtn = document.getElementById("cancel-work-btn");

            document.addEventListener("click", e => {
                // Edit
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
                    if (!workId) return alert("ID e vepres nuk eshte e sakte.");
                    if (!confirm("A jeni te sigurt qe doni ta fshini kete veper?")) return;

                    const formData = new FormData();
                    formData.append("action", "delete");
                    formData.append("id", workId);

                    fetch("ajax/update-artist-profile.php", {method: "POST", body: formData})
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === "success") e.target.closest(".work-info-container").remove();
                            else alert(data.message || "Gabim gjate fshirjes.");
                        })
                        .catch(() => alert("Gabim gjate komunikimit me serverin."));
                }
            });

            // Ruajtja e edit work
            cancelWorkBtn.addEventListener("click", () => editWorkModal.style.display = "none");

            saveWorkBtn.addEventListener("click", () => {
                const name = document.getElementById("edit-work-name").value.trim();
                const desc = document.getElementById("edit-work-desc").value.trim();
                const price = parseFloat(document.getElementById("edit-work-price").value.trim());

                if (!name || !desc || isNaN(price) || price <= 0) return alert("Plotesoni te gjitha fushat me vlere te sakte.");
                if (!currentWorkId) return alert("ID e vepres nuk eshte e sakte.");

                const formData = new FormData();
                formData.append("action", "edit");
                formData.append("id", currentWorkId.toString());
                formData.append("name", name);
                formData.append("desc", desc);
                formData.append("price", price.toString());

                fetch("ajax/update-artist-profile.php", {method: "POST", body: formData})
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === "success") {
                            const workDiv = document.querySelector(`.edit-work-btn[data-id='${currentWorkId}']`).closest(".work-info");
                            workDiv.querySelector(".name").innerText = name;
                            workDiv.querySelector(".desc").innerText = desc;
                            workDiv.querySelector(".price").innerText = "€" + price;
                            editWorkModal.style.display = "none";
                        } else alert(data.message || "Gabim gjate ruajtjes se vepres.");
                    })
                    .catch(() => alert("Gabim gjate komunikimit me serverin."));
            });

        });
});
