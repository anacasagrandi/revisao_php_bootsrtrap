<?php
// Incluir a conexao com o banco de dados
include_once "conexao.php";

// Receber o ID via método GET
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

// Verifica se o ID não está vazio
if (!empty($id)) { 
    // preparar a query p deletar o usuario com o ID fornecido
    $query_usuario = "DELETE FROM usuarios WHERE id=:id";
    $del_usuario = $conn->prepare($query_usuario);
    $del_usuario->bindParam(':id', $id);

    //executar a query p deletar o usuario
    if ($del_usuario->execute()) {
        //se o usuario for deletado com sucesso, preparar a query opara deletar o endereço associado a esse usuario
        $query_endereco = "DELETE FROM usuarios WHERE usuario_id=:usuario_id";
        $del_endereco = $conn->prepare($query_endereco);
        $del_endereco->bindParam(':usuario_id', $id);

        //executar a query p deletar o endereço associado ao usuario
        if($del_endereco->execute()){
            //se ambos, usuario e endereço, forem deletados com sucesso, retornar uma mensagem de sucesso
            $retorna = ['status' => true, 'msg' =>"<div class='alert alert-danger' role='alert'>Usuário apagado com sucesso!</div>"]; 
        }
    } else {
        // se houver um erro ao deletar o usuario, retornar uma mensagem de erro
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Usuário apagado, endereço não apagado com sucesso! </div>"];
       } 
    } else {
        // se  o id estiver vazio, retornar uma mensaem de erro
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum usuário encontrado! </div>"];
}
// Retornar o resultado em formato JSON
echo json_encode($retorna);
