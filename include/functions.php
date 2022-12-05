<?php

function openSQLite(): object
{
	$sqlitedb = new PDO('sqlite:./database.db');
	return $sqlitedb;
}

function selectAsJson( object $db, string $sql ): array
{
	$query = $db->query( $sql );
	$results = $query->fetchAll( PDO::FETCH_ASSOC );
	header( 'HTTP/1.1 200 OK' );
	return $results;
}

function executeInsert( object $db, string $sql ): int
{
	$query = $db->query($sql);
	return $db->lastInsertId();
}

function returnError( PDOException $pdoex ): void
{
	header( 'HTTP/1.1 500 Internal Server Error' );
	$error = [
		'error' => $pdoex->getMessage(),
	];
	echo json_encode( $error );
	exit();
}
