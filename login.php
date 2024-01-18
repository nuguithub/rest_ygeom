<?php
require_once "db_connection.php";

class AuthController
{
    public function login()
    {
        global $pdo;

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['username']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Username and password are required.']);
            return;
        }

        $username = $data['username'];
        $password = $data['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid username or password.']);
            return;
        }

        // Check the user's role
        $role = $user['role'];

        // Redirect based on the user's role
        switch ($role) {
            case 'superadmin':
                // Redirect to the /admin folder
                header('Location: /admin');
                break;
            case 'event_manager':
                // Redirect to the /manage folder
                header('Location: /manage');
                break;
            case 'participant':
                // Stay on the index page
                echo json_encode(['message' => 'Login successful', 'user_id' => $user['user_id']]);
                break;
            default:
                // Invalid role
                http_response_code(401);
                echo json_encode(['error' => 'Invalid role.']);
                break;
        }
    }
}
?>