<?php
require_once "db_connection.php";

class eventController
{
    public function test() {
        http_response_code(400);
        echo json_encode(['Welcome to YGEOM']);
    }

    public function index() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM events");
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($events) {
            echo json_encode($events);
        } else {
            echo json_encode(["message" => "No data available"]);
        }
    }


    public function store() {
        global $pdo;
        $data = json_decode(file_get_contents("php://input"), true);

        $stmt = $pdo->prepare("INSERT INTO events (event_name, event_description, event_date, event_manager_id) 
                               VALUES (?, ?, ?, ?)");

        $stmt->execute([$data["event_name"], $data["event_description"], $data["event_date"], $data["event_manager_id"]]);

        echo json_encode(["message" => "Event added successfully!"]);
    }

    public function show($params) {
        global $pdo;

        if (!isset($params['event_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request.']);
            return;
        }

        $id = $params['event_id'];

        if (!ctype_digit((string)$id) || $id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid event ID.']);
            return;
        }

        $stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = ?");
        $stmt->execute([$id]);

        $car = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$car) {
            http_response_code(404);
            echo json_encode(['error' => 'Event not found.']);
            return;
        }

        echo json_encode($car);
    }

    public function update($params) {
        global $pdo;

        if (!isset($params['event_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            return;
        }

        $id = $params['event_id'];

        if (!ctype_digit((string)$id) || $id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid event ID.']);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request. Request body is empty.']);
            return;
        }

        $setClauses = [];
        $paramsToBind = [];

        foreach ($data as $key => $value) {
            $allowedFields = ['event_name', 'event_description', 'event_date', 'event_manager_id'];
            if (in_array($key, $allowedFields)) {
                $setClauses[] = "$key = ?";
                $paramsToBind[] = $value;
            }
        }

        if (empty($setClauses)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request. No valid fields provided for update.']);
            return;
        }

        $sql = "UPDATE events SET " . implode(', ', $setClauses) . " WHERE event_id = ?";
        $paramsToBind[] = $id;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($paramsToBind);

        echo json_encode(['message' => 'Event updated successfully']);
    }

    public function destroy($params) {
        global $pdo;

        if (!isset($params['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            return;
        }

        $id = $params['id'];

        // Validate that $id is a positive integer
        if (!ctype_digit((string)$id) || $id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid event ID']);
            return;
        }

        $stmt = $pdo->prepare("DELETE FROM events WHERE event_id = ?");
        $stmt->execute([$id]);

        echo json_encode(['message' => 'Event deleted successfully']);
    }
    
}


?>