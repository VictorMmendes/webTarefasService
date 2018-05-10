<?php

	require 'Slim/Slim.php';
	\Slim\Slim::registerAutoloader();

	$app = new \Slim\Slim();

	// CONEXÃO COM O BD
	function getConn() {

		return new PDO('mysql:host=127.0.0.1;dbname=teste', 'root', 'root',
				array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	}

	$app->get('/getAllAnimals', function()
	{
		$conn = getConn();
		$sql = "SELECT * FROM animal_models";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		echo json_encode($stmt->fetchAll());
	});

	// GET - buscar
	$app->get('/getAnimal/:id', function($id)
	{
		$conn = getConn();
		$sql = "SELECT * FROM animal_models WHERE id = '$id'";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		echo json_encode($stmt->fetchAll());
	});

	$app->get('/searchAnimal/:query', function($query)
	{
		$conn = getConn();
		$sql = "SELECT * FROM animal_models WHERE nome like '%$query%' or raca like '%$query%'";
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		echo json_encode($stmt->fetchAll());
	});

	// POST - Inserir
	$app->post('/insertAnimal', function() use ($app) {

		$dadoJson = json_decode( $app->request()->getBody() );

		$nome=$dadoJson[0]->nome;
		$especie=$dadoJson[0]->especie;
		$raca=$dadoJson[0]->raca;
		$peso=$dadoJson[0]->peso;
		$nascimento=$dadoJson[0]->nascimento;
		$porte=$dadoJson[0]->porte;

		$sql = "INSERT INTO animal_models (nome, especie, raca, peso, nascimento, porte) values('$nome', '$especie', '$raca', '$peso', '$nascimento', '$porte')";
		$conn = getConn();
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$id = $conn->lastInsertId();

		echo json_encode( array('msg' => "[OK] Animal ($id) Cadastro com Sucesso!") );
	});

	// PUT - alterar
	$app->put('/editAnimal', function() use ($app)
	{
		$dadoJson = json_decode( $app->request()->getBody() );

		$id=$dadoJson[0]->id;
		$nome=$dadoJson[0]->nome;
		$especie=$dadoJson[0]->especie;
		$raca=$dadoJson[0]->raca;
		$peso=$dadoJson[0]->peso;
		$nascimento=$dadoJson[0]->nascimento;
		$porte=$dadoJson[0]->porte;

		$sql = "update animal_models
					set nome = '$nome',
				 	especie = '$especie',
					raca = '$raca',
					peso = $peso,
					nascimento = '$nascimento',
					porte = '$porte'
				WHERE id = $id";
		$conn = getConn();
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		echo json_encode( array('msg' => "[OK] Animal ($id) alterado com Sucesso!") );
	});

	$app->delete('/deleteAnimal', function() use ($app)
	{
		$dadoJson = json_decode( $app->request()->getBody() );

		$id=$dadoJson[0]->id;

		$sql = "delete from animal_models WHERE id = $id";
		$conn = getConn();
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		echo json_encode( array('msg' => "[OK] Animal ($id) deletado com Sucesso!") );
	});


	$app->run();
?>
