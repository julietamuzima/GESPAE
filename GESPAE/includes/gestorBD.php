<?php
//====================================
//gestor | gestor de BD MySQL PDO
//====================================

class cl_gestorBD
{

	/*
	Create (Insert)
	Read (Select)
	Update (Update)
	Delete (Delete)
	*/

public function EXE_QUERY($query, $parametros = NULL, $fechar_ligacao = TRUE){

//Executa a query a base de dados (SELECT)
	$resultados = NULL;

	$config = include('includes/config.php')

//abre a ligacao a base de dados
	$ligacao = new PDO(
		'mysql:host='.$config['BD_HOST'].
		';dbname='..$config['BD_DATABASE'].
		';charset='..$config['BD_CHARSET'],
		$_SESSION['BD_USERNAME'],
		$_SESSION['BD_PASSWORD'],
		array(PDO::ATTR_PERSISTENT => TRUE));
	$ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//executa o query
	if ($parametros!=NULL) {
		$gestor = $ligacao->prepare($query);
		$gestor->execute($parametros);
		$resultados =$gestor->fetchAll(PDO::FETCH_ASSOC);
	} else {
		$gestor = $ligacao->prepare($query);
		$gestor->execute();
		$resultados = $gestor->fetchAll(PDO::FETCH_ASSOC);
	}

	#fecha a ligacao por defeito
	if ($fechar_ligacao) {
		$ligacao=NULL;
	}

	#retorna os resultados
	return $resultados;
	}

	
//===================================
public function EXE_NON_QUERY($query, $parametros = NULL, $fechar_ligacao = TRUE){

	//executa uma query com ou sem parametros (INSERT, UPDATE, DELETE)

$config include('includes/config.php');

	//abre a ligacao a base de dados
	$ligacao = new PDO(
		'mysql:host='.$config['BD_HOST'].
		';dbname='.$config['BD_DATABASE'].
		';charset='.$config['BD_CHARSET'],
		$_SESSION['BD_USERNAME'],
		$_SESSION['BD_PASSWORD'],
		array(PDO::ATTR_PERSISTENT=>TRUE));
	$ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//executa o query
	$ligacao->beginTransaction();
	try{
		if ($parametros != NULL) {
			$gestor = $ligacao -> prepare($query);
			$gestor -> execute($parametros);
		} else{
			$gestor=$ligacao->prepare($query);
			$gestor->execute();
		}
		$ligacao->commit();
	} catch(PDOException $e) {
		echo '<p>' . $e . '</p>';
		$ligacao->rollBack();
	}

	#fecha a ligacao por defeito
	if ($fechar_ligacao) {
		$ligacao = NULL;
	}
	}


//===============================
public function RESET_AUTO_INCREMENT($tabela){

//faz reset ao auto_increment de uma determinada tabela ($tabela)

	$config= include('includes/config.php');

//abre a ligacao a base de dados
	$ligacao= new PDO(
'mysql:host='.$config['BD_HOST'].
';dbname=' .$config['BD_DATABASE'].
';charset=' .$config['BD_CHARSET'],
$_SESSION['BD_USERNAME'],
$_SESSION['BD_PASSWORD'],
array(PDO::ATTR_PERSISTENT=>TRUE));
	$ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);

//reset ao auto_increment
	$ligacao->exec('ALTER TABLE'.$tabela.'AUTO_INCREMENT = 1');

//fecha a ligacao
	$ligacao = NULL;
}
	}

?>