<?php 
/* CABEÇALHOS do HTTP */
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
/*
Essa variável recebe o metodo utilizado
pode ser POST, GET, PUT ou DELETE
*/
$metodoSolicitado = $_SERVER['REQUEST_METHOD'];

/*Esse Id é quando colocamos informações na URL*/
$id     = $_GET['id'] ?? null;
/*
 ?? significa que se $_GET['id'] existir e não for nula 
o conteudo entra na variável id
*/
switch($metodoSolicitado){
    case "POST":
        $dados_recebidos = json_decode(file_get_contents("php://input"), true);
        $nome_materia = $dados_recebidos['Materia'] ?? null;
        $disponivel = $dados_recebidos['Disponivel'] ?? null;

            if ($nome_materia && $disponivel) {
                $servidor = "localhost"; 
                $usuario = "root"; 
                $senha = ""; 
                $banco = "aula_pw3";

                $conexao = new mysqli($servidor, $usuario, $senha, $banco);

                if ($conexao->connect_error) {
                    http_response_code(500);
                    echo json_encode(["erro" => "Falha na conexão: " . $conexao->connect_error]);
                    exit;
                }

                $stmt = $conexao->prepare("INSERT INTO Materias (Materia, Disponivel) VALUES (?, ?)");
                $stmt->bind_param("ss", $nome_materia, $disponivel);

                if ($stmt->execute()) {
                    
                } else {
                    http_response_code(500);
                    
                }

                $stmt->close();
                $conexao->close();
                echo json_encode($dados_recebidos);
            }else{
                echo json_encode("{'erro':'dados inválidos'}");
            }
        break;
    case "GET": 
         
        $servidor = "localhost"; 
        $usuario = "root"; 
        $senha = ""; 
        $banco = "aula_pw3";

        $conexao = new mysqli($servidor, $usuario, $senha, $banco);

        $sql = "Select * from Materias";

        $resultado = $conexao->query($sql);

        $materias = [];
        while ($linha = $resultado->fetch_assoc()) {
            $materias[] = $linha;
        }
        echo json_encode($materias);
        break;    
}


?>