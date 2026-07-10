<?php

header('Content-Type: application/json');

require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Ongeldige aanvraag."
    ]);
    exit;
}

$locatie = $_POST['locatie'] ?? '';
$beschrijving = $_POST['beschrijving'] ?? '';

if (empty($locatie) || empty($beschrijving)) {
    echo json_encode([
        "success" => false,
        "message" => "Vul alle velden in."
    ]);
    exit;
}

try {

    $query = "
        INSERT INTO bezienswaardigheden 
        (locatie, beschrijving)
        VALUES
        (:locatie, :beschrijving)
    ";

    $stmt = $db->prepare($query);
    $stmt->bindParam(":locatie", $locatie);
    $stmt->bindParam(":beschrijving", $beschrijving);

    if ($stmt->execute()) {

        echo json_encode([
            "success" => true,
            "message" => "Locatie toegevoegd.",
            "id" => $db->lastInsertId()
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Toevoegen mislukt."
        ]);

    }

} catch(PDOException $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);

}

?>