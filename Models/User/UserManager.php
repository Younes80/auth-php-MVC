<?php

namespace Models\User;

use Models\Database;


class UserManager extends Database
{


	public function checkEmail($email)
	{
		$req = "SELECT email FROM user WHERE email = ?";
		$statement = $this->getBdd()->prepare($req);
		$statement->bindValue(1, $email);
		$statement->execute();
		return $statement->fetchColumn();
	}


	public function register($firstname, $lastname, $email, $password)
	{

		$req = 'INSERT INTO user VALUES (
      DEFAULT,
      :firstname,
      :lastname,
      :email,
      :password
    )';
		$statement = $this->getBdd()->prepare($req);
		$statement->bindValue(':firstname', $firstname);
		$statement->bindValue(':lastname', $lastname);
		$statement->bindValue(':email', $email);
		$statement->bindValue(':password', $password);
		$statement->execute();
		return $this->login($this->getBdd()->lastInsertId());
	}



	public function login($email)
	{
		$req = 'SELECT * FROM user WHERE email = :email';
		$statement = $this->getBdd()->prepare($req);
		$statement->bindValue(':email', $email);
		$statement->execute();
		return $statement->fetch();
	}


	public function sessionCreateOne($user, $sessionId)
	{
		$req = 'INSERT INTO session VALUES ( :sessionid, :userid )';
		$statement = $this->getBdd()->prepare($req);
		$statement->bindValue(':sessionid', $sessionId);
		$statement->bindValue(':userid', $user->id);
		$statement->execute();
	}


	public function sessionUser($sessionId)
	{
		$req = 'SELECT session.id as sessionId, user.id, user.firstname, user.lastname, user.email 
            FROM session 
            JOIN user on user.id=session.userid 
            WHERE session.id=?';
		$statement = $this->getBdd()->prepare($req);
		$statement->execute([$sessionId]);
		return $statement->fetch();
	}


	public function logout($sessionId)
	{
		$req = 'DELETE FROM session WHERE id = ?';
		$statement = $this->getBdd()->prepare($req);
		$statement->execute([$sessionId]);
	}
}
