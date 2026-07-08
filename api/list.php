<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database
include_once 'config/database.php';

// database connectie
$database = new Database();
$db = $database->getConnection();

// query bezienswaardigheden
$query = "SELECT 
            id,
            locatie,
            beschrijving
          FROM bezienswaardigheden
          ORDER BY locatie";

$stmt = $db->prepare($query);
$stmt->execute();

$num = $stmt->rowCount();

if ($num > 0) {

    $bezienswaardigheden_arr = array();
    $bezienswaardigheden_arr["records"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $item = array(
            "id" => $id,
            "locatie" => $locatie,
            "beschrijving" => html_entity_decode($beschrijving)
        );

        array_push($bezienswaardigheden_arr["records"], $item);
    }

    http_response_code(200);

    echo json_encode($bezienswaardigheden_arr);

} else {

    http_response_code(404);

    echo json_encode(
        array("message" => "Geen bezienswaardigheden gevonden.")
    );
}
?>