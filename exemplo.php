<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

$dsn = "mysql:host=localhost;dbname=sua_base;charset=utf8";
$user = "seu_usuario";
$pass = "sua_senha";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["erro" => $e->getMessage()]);
    exit;
}

function getJsonInput() {
    return json_decode(file_get_contents("php://input"), true);
}

$method = $_SERVER['REQUEST_METHOD'];
$id     = $_GET['id'] ?? null;

switch ($method) {
    case "GET":
        if ($id) {
            $stmt = $pdo->prepare("SELECT * FROM Alunos WHERE Id=?");
            $stmt->execute([$id]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
        } else {
            $stmt = $pdo->query("SELECT * FROM Alunos");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
        break;

    case "POST":
        $data = getJsonInput();
        $stmt = $pdo->prepare("INSERT INTO Alunos (Aluno, RA, Matriculado) VALUES (?, ?, ?)");
        $stmt->execute([
            $data["Aluno"],
            $data["RA"],
            $data["Matriculado"] ?? 1
        ]);
        echo json_encode(["Id" => $pdo->lastInsertId()]);
        break;

    case "PUT":
        if ($id) {
            $data = getJsonInput();
            $stmt = $pdo->prepare("UPDATE Alunos SET Aluno=?, RA=?, Matriculado=? WHERE Id=?");
            $stmt->execute([
                $data["Aluno"],
                $data["RA"],
                $data["Matriculado"],
                $id
            ]);
            echo json_encode(["msg" => "Atualizado com sucesso"]);
        } else {
            http_response_code(400);
            echo json_encode(["erro" => "ID obrigatório para atualizar"]);
        }
        break;

    case "DELETE":
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM Alunos WHERE Id=?");
            $stmt->execute([$id]);
            echo json_encode(["msg" => "Deletado com sucesso"]);
        } else {
            http_response_code(400);
            echo json_encode(["erro" => "ID obrigatório para deletar"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["erro" => "Método não permitido"]);
        break;
}

?>