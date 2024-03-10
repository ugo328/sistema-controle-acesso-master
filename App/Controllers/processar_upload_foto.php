<?php
// Verifique se o arquivo foi enviado corretamente
if ($_FILES["foto"]["error"] == UPLOAD_ERR_OK) {
    // Define o diretório de destino para salvar a foto
    $diretorio_destino = "C:/Users/ugorv/sistema java script condominio/sistema-controle-acesso-master/sistema-controle-acesso-master/public/img/";

    // Se o diretório de destino não existir, crie-o
    if (!is_dir($diretorio_destino)) {
        mkdir($diretorio_destino, 0777, true);
    }

    // Obtém o nome do visitante do formulário e realiza a validação
    $nome_visitante = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    if (!$nome_visitante) {
        // Se o nome do visitante estiver vazio, retorne uma mensagem de erro
        echo "Nome de visitante inválido.";
        exit();
    }

    // Obtém o nome original do arquivo da foto
    $nome_arquivo_foto = $_FILES["foto"]["name"];

    // Define o nome do arquivo da foto como o nome do visitante com a extensão do arquivo
    $nome_arquivo = $nome_visitante . "." . pathinfo($nome_arquivo_foto, PATHINFO_EXTENSION);

    // Move o arquivo para o diretório de destino
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $diretorio_destino . $nome_arquivo)) {
        // Foto foi carregada com sucesso, atualize o registro do visitante com o caminho da foto

        // Obtém a conexão com o banco de dados
        $conn = \App\Connection::getDb();

        // Query SQL para atualizar o registro do visitante com o caminho da foto
        $sql = "UPDATE visitantes SET nome_imagem = :nome_arquivo WHERE id_visitante = :id_visitante";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome_arquivo', $nome_arquivo);
        $stmt->bindParam(':id_visitante', $_POST["id_visitante"]);
        $stmt->execute();

        // Redirecione de volta para a página de cadastro de visitantes com uma mensagem de sucesso
        header("Location: cadastro_visitante.php?sucesso=foto_enviada");
        exit();
    } else {
        // Houve um erro ao mover o arquivo
        echo "Erro ao carregar a foto.";
    }
} else {
    // Houve um erro no upload da foto
    echo "Erro no upload da foto.";
}
?>
