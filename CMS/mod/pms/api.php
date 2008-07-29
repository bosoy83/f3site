<?php /* API prywatnych wiadomo�ci - pami�taj o obj�ciu operacji transakcj� bazy danych */
class PM
{
	public //Version 1
		$to,
		$text,
		$topic,
		$bbcode = 0,
		$sender = UID, //Tylko ID
		$status = 1,
		$exceptions;

	#Sprawd� poprawno�� danych - u�yj $errRef, je�li ju� zdefiniowa�e� tablic� b��d�w
	function errors(&$errRef = null)
	{
		global $lang;

		#Tu zapisuj b��dy
		if($errRef && is_array($errRef))
		{
			$error = &$errRef;
		}
		else
		{
			$error = array();
		}

		#Tre�� za d�uga?
		if(isset($this->text[20001])) $error[] = $lang['pm18'];

		#Skrzynka pe�na?
		if($this->inboxFull($this->to)) $error[] = $lang['pm21'];

		#Wyrzu� b��d
		if($error)
		{
			if($this->exceptions)
			{
				throw new Exception($error);
			}
			elseif($errRef)
			{
				return true;
			}
			else
			{
				return $error;
			}
		}
		return false; //FALSE gdy brak b��d�w
	}

	#Wy�lij wiadomo��
	function send($force = false)
	{
		global $db;

		#Odbiorca
		if(!is_numeric($this->to)) $this->to = userID($this->to);

		#Gdy s� b��dy...
		if(!$force && $this->errors()) return false;

		#Zapytanie
		$q = $db->prepare('INSERT INTO '.PRE.'pms (topic,usr,owner,st,date,bbc,txt)
			VALUES (:topic,:usr,:owner,:st,:date,:bbc,:txt)');

		$q->execute( array(
			'owner' => $this->status > 2 ? UID : $this->to,
			'usr'   => $this->status < 3 ? UID : $this->sender,
			'topic' => $this->topic,
			'txt'   => $this->text,
			'bbc'   => $this->bbcode,
			'st'    => $this->status,
			'date'  => $_SERVER['REQUEST_TIME']
		));

		#Zwi�ksz liczb� nieodebranych wiadomo�ci
		if($this->status === 1) $db->exec('UPDATE '.PRE.'users SET pms=pms+1 WHERE ID='.$this->to);
	}

	#Zapisz wiadomo�� (domy�lnie - kopia robocza)
	function update($id, $force=false)
	{
		global $db;

		$q = $db->prepare('UPDATE '.PRE.'pms SET topic=:topic, usr=:usr, owner=:owner,
			st=:st, date=:date, bbc=:bbc, WHERE owner=:owner AND st=4 AND ID=:id');

		$q->execute( array(
			'id'    => $id,
			'owner' => $this->status > 2 ? UID : $this->to,
			'usr'   => $this->status < 3 ? UID : $this->sender,
			'topic' => $this->topic,
			'txt'   => $this->text,
			'bbc'   => $this->bbcode,
			'st'    => $this->status,
			'date'  => $_SERVER['REQUEST_TIME']
		));

		#Zwi�ksz liczb� nieodebranych wiadomo�ci
		if($this->status === 1) $db->exec('UPDATE '.PRE.'users SET pms=pms+1 WHERE ID='.$this->to);
	}

	#Czy skrzynka jest pe�na?
	function inboxFull($user=null)
	{
		if(!$user) $user = $this->to;
		return db_count('ID','pms WHERE owner='.(int)$user) >= $GLOBALS['cfg']['pmLimit'];
	}

	#Usu� wiadomo�ci
	function delete($id)
	{
		if(is_array($id))
		{
			$in = array();
			foreach($id as $x) $in[] = (int)$x;
		}
		elseif(is_numeric($id))
		{
			$in = array($id);
		}
		else return false;

		$GLOBALS['db']->exec('DELETE FROM '.PRE.'pms WHERE ID IN ('.join(',', $in).')');
		return $GLOBALS['db']->rowCount(); //Zwr�� ilo�� usuni�tych wiadomo�ci
	}
}

#Pobierz ID u�ytkownika
function userID($login)
{
	global $db;

	if($id = (int)$db->query('SELECT ID FROM '.PRE.'users WHERE login='.$db->quote($login))->fetchColumn())
	{
		return $id;
	}
	else
	{
		throw new Exception($GLOBALS['lang']['pm20']);
	}
} // Use "search" function in your editor if you wish you found a function. :)
 // Or use kED if you would like to know this function finishes here - it's free!