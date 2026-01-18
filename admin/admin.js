// Load users nga backend
function loadUsers() {
    document.getElementById("addUserForm").classList.add("hidden");

    fetch("api/userActions.php", {
        method: "POST",
        body: new URLSearchParams({ action: "get" })
    })
        .then(res => res.json())
        .then(data => {
            window.users = data; // ruaj global për popup
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

// Modify popup
function modifyUser(id) {
    fetch("api/userActions.php", {
        method: "POST",
        body: new URLSearchParams({ action: "getSingleUser", id: id })
    })
        .then(res => res.json())
        .then(u => {
            document.getElementById("edit-user-id").value = u.id;
            document.getElementById("edit-name").value = u.name;
            document.getElementById("edit-surname").value = u.surname;
            document.getElementById("edit-email").value = u.email;
            document.getElementById("edit-role").value = u.role;

            document.getElementById("modifyModal").classList.add("show");
        });
}

// Cancel popup
document.getElementById("cancelModify").addEventListener("click", () => {
    document.getElementById("modifyModal").classList.remove("show");
});

// Save user nga popup
document.getElementById("saveModify").addEventListener("click", () => {
    const formData = new FormData();
    formData.append("action", "updateUser");
    formData.append("id", document.getElementById("edit-user-id").value);
    formData.append("name", document.getElementById("edit-name").value);
    formData.append("surname", document.getElementById("edit-surname").value);
    formData.append("email", document.getElementById("edit-email").value);
    formData.append("role", document.getElementById("edit-role").value);

    const photo = document.getElementById("edit-photo").files[0];
    if(photo) formData.append("photo", photo);

    fetch("api/userActions.php", {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(() => {
            alert("User updated!");
            document.getElementById("modifyModal").classList.remove("show");
            loadUsers();
        });
});

// Delete user
function deleteUser(id) {
    if(!confirm("Delete this user?")) return;
    fetch("api/userActions.php", {
        method: "POST",
        body: new URLSearchParams({ action: "delete", id })
    }).then(loadUsers);
}

// Show add user form
function showAddUser() {
    document.getElementById("addUserForm").classList.remove("hidden");
}

// Add user
function addUser() {
    const name = document.getElementById("name").value;
    const surname = document.getElementById("surname").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const role = document.getElementById("role").value;

    fetch("api/userActions.php", {
        method: "POST",
        body: new URLSearchParams({ action:"add", name, surname, email, password, role })
    })
        .then(() => {
            alert("User added!");
            loadUsers();
        });
}

// Load users initially
//loadUsers();
