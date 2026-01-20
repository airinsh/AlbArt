document.addEventListener("DOMContentLoaded", () => {
    fetch("ajax/get-klient-profile.php")
        .then(res => res.json())
        .then(data => {
            if(data.status !== "success"){
                alert(data.message);
                return;
            }

            const k = data.klient;
            document.getElementById("klient-name").innerText = k.name + " " + k.surname;

            // BLERJET
            const ordersList = document.getElementById("orders-list");
            ordersList.innerHTML = "";
            if(data.blerjet.length > 0){
                data.blerjet.forEach(b => {
                    ordersList.innerHTML += `
    <li>
        <strong>${b.Emri}</strong><br>
        ${parseFloat(b.Cmimi).toFixed(2)} €<br>
        <em>(Artist: ${b.artist_name} ${b.artist_surname})</em>
    </li>
`;

                });
            } else {
                ordersList.innerHTML = `<li class="placeholder">Nuk ka ende blerje.</li>`;
            }

            // REVIEWS
            const reviewsList = document.getElementById("reviews-list");
            reviewsList.innerHTML = "";
            if(data.reviews.length > 0){
                data.reviews.forEach(r => {
                    reviewsList.innerHTML += `
                    <li>
                        <strong>${r.Vleresimi}★</strong> – ${r.Koment || "(pa koment)"} 
                        <em>(Artist: ${r.artist_name} ${r.artist_surname})</em>
                    </li>
                `;
                });
            } else {
                reviewsList.innerHTML = `<li class="placeholder">Nuk ka ende vlerësime.</li>`;
            }

        })
        .catch(err => console.error("Gabim JS:", err));
});
