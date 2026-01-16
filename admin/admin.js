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
                <td contenteditable="true" onblur="updateUser(${u.id}, 'name', this.innerText)">${u.name}</td>
                <td contenteditable="true" onblur="updateUser(${u.id}, 'surname', this.innerText)">${u.surname}</td>
                <td contenteditable="true" onblur="updateUser(${u.id}, 'email', this.innerText)">${u.email}</td>
                <td contenteditable="true" onblur="updateUser(${u.id}, 'role', this.innerText)">${u.role}</td>
                <td>
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
    const nameValue = document.getElementById("name").value;
    const surnameValue = document.getElementById("surname").value;
    const emailValue = document.getElementById("email").value;
    const passwordValue = document.getElementById("password").value;
    const roleValue = document.getElementById("role").value;

    fetch("api/userActions.php", {
        method: "POST",
        body: new URLSearchParams({
            action: "add",
            name: nameValue,
            surname: surnameValue,
            email: emailValue,
            password: passwordValue,
            role: roleValue
        })
    }).then(() => {
        alert("User added!");
        loadUsers();
    });
}

function updateUser(id, field, value) {
    fetch("api/userActions.php", {
        method: "POST",
        body: new URLSearchParams({ action: "update", id, field, value })
    });
}

function deleteUser(id) {
    if (!confirm("Delete this user?")) return;

    fetch("api/userActions.php", {
        method: "POST",
        body: new URLSearchParams({ action: "delete", id })
    }).then(loadUsers);
}
