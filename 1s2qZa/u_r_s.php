<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
mb_internal_encoding('UTF-8');
if (!isset($_SESSION['admin_email'])) {
    header('Location: index.php');
    exit;
}

try {
    require_once('adb.php');

    $response = ['success' => false, 'message' => ''];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method. POST expected.');
    }

    $request_id = $_POST['request_id'] ?? null;
    $status = $_POST['status'] ?? null;

    if (!$request_id || !$status) {
        throw new Exception('Invalid request parameters');
    }

    if (!in_array($status, ['approved', 'rejected'])) {
        throw new Exception('Invalid status. Must be "approved" or "rejected".');
    }

    $sql = "SELECT * FROM RipChangeRequests WHERE request_id = :request_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':request_id' => $request_id]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        throw new Exception('Request not found');
    }

    $sql = "UPDATE RipChangeRequests SET status = :status WHERE request_id = :request_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':status' => $status, ':request_id' => $request_id]);

    if ($status == 'approved' && $request['status'] != 'approved') {
        $sql = "UPDATE Users SET RIP = :new_rip WHERE user_r_pseudo = :user_r_pseudo";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':new_rip' => $request['new_rip'], ':user_r_pseudo' => $request['user_pseudo']]);

        if ($stmt->rowCount() == 0) {
            throw new Exception('User not found or RIP not updated');
        }
        $response['message'] = 'Request approved and RIP updated';
    } elseif ($status == 'rejected' && $request['status'] == 'approved') {
        $sql = "UPDATE Users SET RIP = :original_rip WHERE user_r_pseudo = :user_r_pseudo";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':original_rip' => $request['original_rip'], ':user_r_pseudo' => $request['user_pseudo']]);

        if ($stmt->rowCount() == 0) {
            throw new Exception('User not found or RIP not reverted');
        }
        $response['message'] = 'Request rejected and RIP reverted to original value';
    } else {
        $response['message'] = 'Request status updated';
    }

    $response['success'] = true;

} catch (PDOException $e) {
    $response['message'] = 'Database error. Please try again or contact support.';
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($response);
