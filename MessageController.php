<?php
require_once 'database.php';
require_once 'helpers.php';

class MessageController
{
    public static function store()
    {
        global $pdo;

        [ $name, $email, $message, $phone_number ] = Helpers::getValidatedInputs();

        try {
            $stmt = $pdo->prepare("
                INSERT INTO messages (name, email, message, phone_number) 
                VALUES (:name, :email, :message, :phone_number)
            ");

            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':message' => $message,
                ':phone_number' => $phone_number
            ]);

            echo json_encode(['success' => true, 'message' => 'Message saved successfully!']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => "Database error: " . $e->getMessage()]);
        }
    }

    public static function list()
    {
        global $pdo;

        $limit = isset($_GET['pageSize']) ? (int)$_GET['pageSize'] : (isset($_GET['limit']) ? (int)$_GET['limit'] : 10);
        $pageIndex = isset($_GET['pageIndex']) ? (int)$_GET['pageIndex'] : 0;
        $offset = $pageIndex * $limit;

        $sql = "SELECT * FROM messages ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $countStmt = $pdo->query("SELECT COUNT(*) FROM messages");
        $totalCount = $countStmt->fetchColumn();

        echo json_encode([
            'messages' => $messages,
            'totalCount' => (int) $totalCount,
        ]);
    }
}