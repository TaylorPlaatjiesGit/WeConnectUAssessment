<?php

Class Helpers
{
    public static function getValidatedInputs(): array
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $phone_number = trim($_POST['phone_number'] ?? '');

        if (empty($name) || empty($email) || empty($message) || empty($phone_number)) {
            echo json_encode(['success' => false, 'message' => 'All fields are required!']);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email address!']);
            exit;
        }

        if (! preg_match('/^(\+27|0)[6-8][0-9]{8}$/', $phone_number)) {
            echo json_encode(['success' => false, 'message' => 'Invalid phone number provided!']);
            exit;
        }

        return [
            $name,
            $email,
            $message,
            $phone_number
        ];
    }
}