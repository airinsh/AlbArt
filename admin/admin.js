function loadUsers() {
    document.getElementById("addUserForm").classList.add("hidden");

    fetch("api/userActions.php", {
        method: "POST",
        body: new URLSearchParams({ action: "get" })
    })
        .then(res => res.json())
        .then(data => {
            let rows = "";
            data.forEach(u => {
                rows += `
            <tr>
                <td>${u.id}</td>
                <td>${u.name}</td>
                <td>${u.surname}</td>
                <td>${u.email}</td>
                <td>${u.role}</td>
                <td>
                    <button class="modify" onclick="modifyUser(${u.id})">Modify</button>
                    <button class="delete" onclick="deleteUser(${u.id})">Delete</button>
                </td>
            </tr>`;
            });
            document.getElementById("usersTable").innerHTML = rows;
        });
}

function showAddUser() {
    document.getElementById("addUserForm").classList.remove("hidden");
}

function addUser() {
    const name = document.getElementById("name").value;
    const surname = document.getElementById("surname").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const role = document.getElementById("role").value;

    fetch("api/userActions.php", {
        method: "POST",
        body: new URLSearchParams({
            action: "add",
            name, surname, email, password, role
        })
    }).then(() => {
        alert("User added!");
        loadUsers();
    });
}

function deleteUser(id) {
    if (!confirm("Delete this user?")) return;

    fetch("api/userActions.php", {
        method: "POST",
        body: new URLSearchParams({ action: "delete", id })
    }).then(loadUsers);
}

function modifyUser(userId) {
    fetch("api/set-selected-user.php", {
        method: "POST",
        body: new URLSearchParams({
            user_id: userId
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === "artist") {
                window.location.href = "../artist/Profili-Artistit.php";
            } else if (data.status === "client") {
                window.location.href = "../client/Profili-Klientit.php";
            } else {
                alert("Ky user nuk ka rol të vlefshëm");
            }
        });
}

