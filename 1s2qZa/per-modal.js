document.querySelectorAll("button[data-admin-email]").forEach(function(button) {
    button.addEventListener("click", function() {
        var adminEmail = this.getAttribute("data-admin-email");
        displayModal(adminEmail);
    });
});

function displayModal(adminEmail) {
    var modal = document.getElementById("permissionModal");
    var adminEmailField = document.getElementById("adminEmail");
    adminEmailField.value = adminEmail;
    modal.style.display = "block";
}

var closeBtn = document.querySelector(".close");
closeBtn.addEventListener("click", function() {
    var modal = document.getElementById("permissionModal");
    modal.style.display = "none";
});

document.getElementById("permissionForm").addEventListener("submit", function(event) {
    event.preventDefault();
    
    var selectedLevel = document.querySelector('input[name="admin_level"]:checked');
    var messageParagraph = document.querySelector(".permission-message");

    if (!selectedLevel) {
        messageParagraph.textContent = "Veuillez sélectionner un niveau de permission.";
        return;
    }

    var formData = new FormData(this);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "per-modal.php", true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            try {
                var response = JSON.parse(xhr.responseText);
                messageParagraph.textContent = response.message;
                if (response.success) {
                    setTimeout(function() {
                        closeModal();
                        location.reload();
                    }, 3000);
                }
            } catch (error) {
                console.error("Invalid JSON response:", xhr.responseText);
                alert("Erreur lors du traitement de la réponse !");
            }
        } else {
            alert("Erreur : " + xhr.status);
        }
    };
    xhr.send(formData);
});

function closeModal() {
    var modal = document.getElementById("permissionModal");
    modal.style.display = "none";
}
