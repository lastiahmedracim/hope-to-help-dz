<?php
session_start();
if (isset($_SESSION['admin_email'])) {
    $label = "Demande change R.I.P";
    require_once('global.php');  
    require_once('adb.php');
    require_once('ch_ad_l.php');

    $admin_email = $_SESSION['admin_email'];
    $admin_level = getAdminLevel($admin_email, $conn);

    if ($admin_level === 'main_admin') {
        $ripChangeRequests = getRipChangeRequests($conn);
    } else {
        $ripChangeRequests = [];
    }
} else {
    header('Location: index.php');
    exit;
}

function getRipChangeRequests($conn) {
    $sql = "SELECT * FROM RipChangeRequests";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $requests = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $requests[] = $row;
    }
    return $requests;
}
?>
    <?php if ($admin_level === 'main_admin') :?>
        <section id="al">
            <div class="container">
                <div class="al-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Pseudo Droite</th>
                                <th>Nouvelle RIP</th>
                                <th>Date de demande</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($ripChangeRequests)) : ?>
                            <?php foreach ($ripChangeRequests as $request): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($request['user_pseudo']); ?></td>
                                    <td><?php echo htmlspecialchars($request['new_rip']); ?></td>
                                    <td><?php echo htmlspecialchars($request['request_date']); ?></td>
                                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="accept" onclick="updateRequestStatus(<?php echo $request['request_id']; ?>, 'approved')">Accepter</button>
                                            <button class="reject" onclick="updateRequestStatus(<?php echo $request['request_id']; ?>, 'rejected')">Rejeter</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6">Aucune demande de changement de RIP trouvée.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    <?php endif; ?>
<script>
    function showMessage(message, type = 'success') {
        const messageContainer = document.getElementById('message-container');
        const messageBox = document.getElementById('message-box');
        const messageText = document.getElementById('message-text');
        const messageClose = document.getElementById('message-close');

        messageText.textContent = message;
        messageBox.className = type;
        messageContainer.style.display = 'block';

        const hideTimeout = setTimeout(() => {
            messageContainer.style.display = 'none';
        }, 5000);

        messageClose.onclick = () => {
            messageContainer.style.display = 'none';
            clearTimeout(hideTimeout);
        };
    }

    function updateRequestStatus(requestId, status) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "u_r_s.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        xhr.onreadystatechange = function () {
            console.log("Ready state:", xhr.readyState, "Status:", xhr.status);
            if (xhr.readyState == 4) {
                if (xhr.status == 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        console.log("Response:", response);
                        if (response.success) {
                            showMessage(response.message, 'success');
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showMessage("Error: " + response.message, 'error');
                        }
                    } catch (e) {
                        console.error("JSON parse error:", e);
                        showMessage("Error: Invalid response from server", 'error');
                    }
                } else {
                    console.error("Request failed. Status:", xhr.status);
                    showMessage("Error: Request failed. Status: " + xhr.status, 'error');
                }
            }
        };
        
        xhr.onerror = function () {
            console.error("Network error");
            showMessage("Network error. Please check your connection.", 'error');
        };
        
        console.log("Sending request:", "request_id=" + requestId + "&status=" + status);
        xhr.send("request_id=" + requestId + "&status=" + status);
    }
</script>
<div id="message-container">
    <div id="message-box">
        <span id="message-text"></span>
        <button id="message-close">&times;</button>
    </div>
</div>
</body>
</html>
