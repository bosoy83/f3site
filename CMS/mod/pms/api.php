<?php /* API prywatnych wiadomo¶ci - pamiêtaj o objêciu operacji transakcj± bazy danych */
class PM
{
	public
		$to,
		$topic,
		$text,
		$sender = UID,
		$bbcode = 0,
		$copy,
		$errorMode;

	#Sprawd¼ poprawno¶æ danych - u¿yj $errRef, je¶li ju¿ zdefiniowa³e¶ tablicê b³êdów
	function errors(&$errRef = null)
	{
		global $lang;

		#Tu zapisuj b³êdy
		if($errRef && is_array($errRef))
		{
			$error = &$errRef;
		}
		else
		{
			$error = array();
		}

		#Tre¶æ za d³uga?
		if(isset($this->txt[20001])) $error[] = $lang['pm_18'];

		#Skrzynka pe³na?
		if(inboxFull($uid)) $error[] = $lang['pm_21'];

		#Wyrzuæ b³±d
		if($error)
		{
			if($this->errorMode === 'EXCEPTION')
			{
				throw new Exception($e);
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
		return false; //FALSE gdy brak b³êdów
	}
	
	#Wy¶lij wiadomo¶æ
	function send($force = false)
	{
		global $db;

		#Odbiorca
		$uid = is_numeric($this->to) ? $this->to : userID($this->to);

		#Gdy s± b³êdy...
		if(!$force && $this->errors) return false;

		#Zapytanie
		$q = $db->prepare('INSERT INTO '.PRE.'pms (topic,usr,owner,st,date,bbc,txt)
			VALUES (:topic,:usr,:owner,1,'.$_SERVER['REQUEST_TIME'].',:bbc,:txt)');

		$q->execute( array(
			'owner'  => $uid,
			'usr'    => (int)$this->sender,
			'topic'  => $this->topic,
			'txt'    => $this->text,
			'bbc'    => $this->bbc,
		));

		#Zwiêksz liczbê nieodebranych wiadomo¶ci
		$db->exec('UPDATE '.PRE.'users SET pms=pms+1 WHERE ID='.$to_id);
	}

	#Zapisz wiadomo¶æ (domy¶lnie - kopia robocza)
	function save($id = 0, $type = null)
	{
		global $db;

		if($id)
		{
			$q = $db->prepare('UPDATE '.PRE.'pms SET topic=:topic, usr=:usr, owner=:owner,
				st=:st, st=:st, date=:date, bbc=:bbc, WHERE ');
		}
	}

	#Czy skrzynka jest pe³na?
	function inboxFull($user=null)
	{
		if(!$user) $user = $this->to;
		return db_count('ID','pms WHERE owner='.(int)$user) >= $GLOBALS['cfg']['pmLimit'];
	}

	#Usuñ wiadomo¶ci
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
		return $GLOBALS['db']->rowCount(); //Zwróæ ilo¶æ usuniêtych wiadomo¶ci
	}

	#Pobierz ID u¿ytkownika
	function userID($login)
	{
		if($id = (int)$db->query('ID','users WHERE login='.$db->quote($pm['to']))->fetchColumn())
		{
			return $id;
		}
		else
		{
			throw new Exception($GLOBALS['lang']['pm_20']);
		}
	} // Use "search" function in your editor if you wish you found a function. :)
}	  // Or use kED if you would like to know this function finishes here - it's free!

#Plik jêzyka
if(!isset($lang['re'])) include LANG_DIR.'pms.php';