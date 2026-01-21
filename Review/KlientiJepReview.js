// Star rating
const stars = document.querySelectorAll(".star");
let selectedRating = 0;

// Funksioni per te zgjedhur yjet
stars.forEach(star => {
    star.addEventListener("click", () => {
        selectedRating = parseInt(star.getAttribute("data-value"));

        // Hiq te gjitha aktivet
        stars.forEach(s => s.classList.remove("active"));

        // Vendos yjet aktive sipas zgjedhjes
        stars.forEach(s => {
            if (parseInt(s.getAttribute("data-value")) <= selectedRating) {
                s.classList.add("active");
            }
        });
    });
});

// Send review
function sendReview() {
    const comment = document.getElementById("comment").value.trim();
    const artistIdInput = document.getElementById("Artist_ID");

    if (!artistIdInput) {
        alert("Artist ID mungon!");
        return;
    }

    const artistId = artistIdInput.value;

    if (selectedRating === 0) {
        alert("Ju lutem zgjidhni një vlerësim!");
        return;
    }

    const formData = new FormData();
    formData.append("Artist_ID", artistId);
    formData.append("vleresimi", selectedRating);
    formData.append("koment", comment);

    fetch("ajax/KlientiJepReview-ajax.php", {
        method: "POST",
        body: formData
    })
        .then(res => res.text())
        .then(data => {
            switch(data) {
                case "success":
                    document.getElementById("successMessage").classList.remove("d-none");
                    break;
                case "already_reviewed":
                    alert("Ju keni bërë tashmë review për këtë artist!");
                    break;
                case "unauthorized":
                    alert("Ju lutem logohuni fillimisht!");
                    break;
                case "missing_data":
                case "invalid_rating":
                case "db_error":
                    alert("Ndodhi një gabim gjatë ruajtjes së review!");
                    break;
                default:
                    alert("Gabim i panjohur!");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Gabim gjatë komunikimit me serverin!");
        });
}

//Funksion per mbushje automatike te yjeve
document.addEventListener("DOMContentLoaded", () => {
    const artistIdInput = document.getElementById("Artist_ID");
    if (!artistIdInput) return;

    // Merr vleresimin total nga PHP
    const ratingTotal = parseInt(artistIdInput.dataset.rating) || 0;
    if (ratingTotal > 0) {
        stars.forEach(s => s.classList.remove("active"));
        stars.forEach(s => {
            if (parseInt(s.getAttribute("data-value")) <= ratingTotal) {
                s.classList.add("active");
            }
        });
    }
});
