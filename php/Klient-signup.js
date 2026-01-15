document.getElementById("signupForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const terms = document.getElementById("terms").checked;

    const prefs = [];
    document.querySelectorAll(".pref-check:checked").forEach(p => {
        prefs.push(p.value);
    });

    const message = document.getElementById("message");


    if (name === "" || email === "" || password === "") {
        message.innerHTML = "Plotëso të gjitha fushat.";
        message.style.color = "red";
        return;
    }

    const errors = []; // array për të mbledhur të gjitha gabimet

    if (password.length < 6) {
        errors.push("Të ketë minimum 6 karaktere");
    }

    const uppercaseRegex = /[A-Z]/;
    const symbolRegex = /[!@#$%^&*(),.?\":{}|<>]/;

    if (!uppercaseRegex.test(password)) {
        errors.push("Të ketë të paktën një shkronjë të madhe");
    }

    if (!symbolRegex.test(password)) {
        errors.push("Të ketë të paktën një simbol special (!@#$%^&* etj.)");
    }

    if (errors.length > 0) {
        // Shfaq mesazhet si listë
        message.innerHTML = "Password duhet të përmbajë: <ul><li>" + errors.join("</li><li>") + "</li></ul>";
        message.style.color = "red";
        return;
    }


    // Kontroll për të paktën një preferencë
    if (prefs.length === 0) {
        message.innerHTML = "Duhet të zgjedhësh të paktën një preferencë.";
        message.style.color = "red";
        return;
    }

    if (!terms) {
        message.innerText = "Duhet të pranosh kushtet.";
        message.style.color = "red";
        return;
    }


    // AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/klient-signup-ajax.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onload = function () {
        const res = JSON.parse(this.responseText);

        if (res.status === "error") {
            message.innerHTML = res.message; // <--- Këtu innerHTML në vend të innerText
            message.style.color = "red";
        } else {
            message.innerHTML = res.message;
            message.style.color = "green";

            setTimeout(() => {
                window.location.href = "login.php";
            }, 1500);
        }
    }
        xhr.send(JSON.stringify({
        name: name,
        email: email,
        password: password,
        preferenca: prefs
    }));
});
