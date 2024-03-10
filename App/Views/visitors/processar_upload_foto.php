<?php
// Verifique se o arquivo foi enviado corretamente
if ($_FILES["foto"]["error"] == UPLOAD_ERR_OK) {
    // Define o diretório de destino para salvar a foto
    $diretorio_destino = "C:/Users/ugorv/sistema java script condominio/sistema-controle-acesso-master/sistema-controle-acesso-master/public/img/";

    // Se o diretório de destino não existir, crie-o
    if (!is_dir($diretorio_destino)) {
        mkdir($diretorio_destino, 0777, true);
    }
    
    // Obtém o nome do visitante do formulário
    $nome_visitante = $_POST["nome"];
    
    // Obtém o nome original do arquivo da foto
    $nome_arquivo_foto = $_FILES["foto"]["name"];
    
    // Define o nome do arquivo da foto como o nome do visitante com a extensão do arquivo
    $nome_arquivo = $nome_visitante . "." . pathinfo($nome_arquivo_foto, PATHINFO_EXTENSION);
    
    // Move o arquivo para o diretório de destino
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $diretorio_destino . $nome_arquivo)) {
        // Foto foi carregada com sucesso, atualize o registro do visitante com o caminho da foto
        // Supondo que você esteja utilizando uma conexão com o banco de dados
        $caminho_foto = $diretorio_destino . $nome_arquivo;
        // Atualize o registro do visitante no banco de dados com o caminho da foto
        // Substitua 'caminho_foto' pelo campo correspondente na tabela de visitantes
        // Substitua 'visitantes_cadastrados' pelo nome correto da tabela de visitantes
        // Substitua 'id_visitante' pelo nome correto do campo de ID na tabela de visitantes
        $sql = "UPDATE visitantes_cadastrados SET caminho_foto = :caminho_foto WHERE id_visitante = :id_visitante";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':caminho_foto', $caminho_foto);
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
