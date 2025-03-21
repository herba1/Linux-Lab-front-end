<?php

declare(strict_types=1);

function GetEmail(object $pdo, string $email) : array|false {
    $query = "SELECT username FROM users WHERE email = :email";
    $statement = $pdo->prepare($query);
	$statement->bindParam(":email", $email);
	$statement->execute();

	$result = $statement->fetch(PDO::FETCH_ASSOC);
	return $result;
}