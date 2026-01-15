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
    message.innerHTML = "";

    // ---------------- VALIDIME
    if (name === "" || email === "" || password === "") {
        message.innerText = "Plotëso të gjitha fushat.";
        message.style.color = "red";
        return;
    }

    const errors = [];

    if (password.length < 6) {
        errors.push("Minimum 6 karaktere");
    }
    if (!/[A-Z]/.test(password)) {
        errors.push("Të paktën një shkronjë të madhe");
    }
    if (!/[!@#$%^&*(),.?\":{}|<>]/.test(password)) {
        errors.push("Të paktën një simbol special");
    }

    if (errors.length > 0) {
        message.innerHTML = "Password duhet të përmbajë:<ul><li>" +
            errors.join("</li><li>") +
            "</li></ul>";
        message.style.color = "red";
        return;
    }

    if (prefs.length === 0) {
        message.innerText = "Duhet të zgjedhësh të paktën një preferencë.";
        message.style.color = "red";
        return;
    }

    if (!terms) {
        message.innerText = "Duhet të pranosh kushtet.";
        message.style.color = "red";
        return;
    }

    // ---------------- AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/klient-signup-ajax.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onload = function () {
        const res = JSON.parse(this.responseText);

        if (res.status === "error") {
            message.innerHTML = res.message;
            message.style.color = "red";
        }

        if (res.status === "verify") {
            message.innerHTML = res.message;
            message.style.color = "green";

            setTimeout(() => {
                window.location.href =
                    "verify.php?email=" + encodeURIComponent(res.email);
            }, 1500);
        }
    };

    xhr.send(JSON.stringify({
        name: name,
        email: email,
        password: password,
        preferenca: prefs
    }));
});
