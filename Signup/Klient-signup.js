document.getElementById("signupForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("name").value.trim();
    const surname = document.getElementById("surname").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const terms = document.getElementById("terms").checked;

    const prefs = [];
    document.querySelectorAll(".pref-check:checked").forEach(p => {
        prefs.push(p.value);
    });

    const message = document.getElementById("message");
    message.innerHTML = "";

    if (name === "" || surname === "" || email === "" || password === "") {
        message.innerText = "Plotëso të gjitha fushat.";
        message.style.color = "red";
        return;
    }

    if (!terms) {
        message.innerText = "Duhet të pranosh kushtet.";
        message.style.color = "red";
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../Signup/ajax/klient-signup-ajax.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onload = function () {
        const res = JSON.parse(this.responseText);

        message.innerHTML = res.message;
        message.style.color = res.status === "error" ? "red" : "green";

        if (res.status === "verify") {
            setTimeout(() => {
                window.location.href =
                    "../Signup/verify.php?email=" + encodeURIComponent(res.email);
            }, 1500);
        }
    };

    xhr.send(JSON.stringify({
        name: name,
        surname: surname,
        email: email,
        password: password,
        preferenca: prefs
    }));
});
