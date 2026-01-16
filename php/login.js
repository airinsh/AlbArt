document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const remember = document.getElementById("remember").checked;
    const message = document.getElementById("message");

    if (email === "" || password === "") {
        message.innerText = "Plotëso të gjitha fushat.";
        message.style.color = "red";
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        message.innerText = "Shkruaj një email të vlefshëm.";
        message.style.color = "red";
        return;
    }

    fetch("../php/login-ajax.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({ email, password, remember })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                message.style.color = "green";
                message.innerText = "Login i suksesshëm! Po ridrejtojmë...";
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1000);
            } else {
                message.style.color = "red";
                message.innerText = data.message;
            }
        })
        .catch(() => {
            message.innerText = "Gabim lidhjeje me serverin.";
            message.style.color = "red";
        });
});
