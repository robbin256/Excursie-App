<?php

header('Content-Type: application/json');

require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    http_response_code(405);

    echo json_encode([
        'success' => false,
        'message' => 'Alleen POST is toegestaan.'
    ]);

    exit;
}


$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);


if (!$id) {

    echo json_encode([
        'success' => false,
        'message' => 'Ongeldig ID.'
    ]);

    exit;

}


try {

    // Controleer of de locatie bestaat
    $check = $db->prepare(
        "SELECT id FROM bezienswaardigheden WHERE id = :id"
    );

    $check->execute([
        ':id' => $id
    ]);
    if (!$check->fetch()) {

        echo json_encode([
            'success' => false,
            'message' => 'Locatie niet gevonden.'
        ]);

        exit;

    }


    // Verwijderen
    $delete = $db->prepare(
        "DELETE FROM bezienswaardigheden WHERE id = :id"
    );

    $delete->execute([
        ':id' => $id
    ]);


    echo json_encode([
        'success' => true,
        'message' => 'Locatie succesvol verwijderd.'
    ]);


} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Databasefout.',
        'error' => $e->getMessage()
    ]);

}

?>