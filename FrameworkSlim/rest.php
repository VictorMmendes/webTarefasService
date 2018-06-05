<?php

	require 'Slim/Slim.php';
	\Slim\Slim::registerAutoloader();

	$app = new \Slim\Slim();

	function getConn() {

		return new PDO('mysql:host=127.0.0.1;dbname=tarefas', 'root', 'root',
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	}

	$app->get('/getAllTarefas', function()
	{
		$conn = getConn();
		$sql = "SELECT * FROM tarefa";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		echo json_encode($stmt->fetchAll());
	});

	$app->post('/insertTarefa', function() use ($app) {

		$dadoJson = json_decode( $app->request()->getBody() );

		$titulo = $dadoJson[0]->titulo;
		$descricao = $dadoJson[0]->descricao;
		$status = $dadoJson[0]->status;

		$sql = "INSERT INTO tarefa (titulo, descricao, status) values('$titulo', '$descricao', $status)";
		$conn = getConn();
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$id = $conn->lastInsertId();

		echo json_encode( array('msg' => "[OK] tarefa ($id) Cadastro com Sucesso!") );
	});

	$app->put('/editTarefa', function() use ($app)
	{
		$dadoJson = json_decode( $app->request()->getBody() );

		$id = $dadoJson[0]->id;
		$titulo = $dadoJson[0]->titulo;
		$descricao = $dadoJson[0]->descricao;
		$status = $dadoJson[0]->status;

		$sql = "update tarefa
								set titulo = '$titulo',
				 				descricao = '$descricao',
								status = $status
						WHERE id = $id";
		$conn = getConn();
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		echo json_encode( array('msg' => "[OK] tarefa ($id) alterado com Sucesso!") );
	});


	$app->run();
?>
