<?php /* Klasa wspomaga operacje: zmieñ ilo¶æ pozycji w kategoriach, sprawd¼ prawa... */
class Saver
{
	public
		$id = 0,
		$data = array(),
		$old = array(),
		$old_cat;

	function __construct(&$data, $id, $table, $cols='cat,author,access')
	{
		//Stare dane
		if($id)
		{
			$this->old = $GLOBALS['db'] -> query('SELECT '.$cols.' FROM '.PRE.$table.' WHERE ID='.$id) -> fetch(2);
			$this->old_cat = $this->old ? $this->old['cat'] : null;
		}
		else
		{
			$this->old_cat = &$data['cat'];
		}

		//ID, dane
		$this->id = $id;
		$this->data =& $data;

		//Dane istniej±?
		if($this->old_cat !== null)
		{
			$GLOBALS['db']->beginTransaction();
		}
		else
		{
			throw new Exception($GLOBALS['lang']['noex']);
		}

		//Prawa do kategorii
		if(!Admit($this->data['cat'], 'CAT') || ($this->data['cat'] != $this->old_cat && !Admit($this->old_cat, 'CAT')))
		{
			throw new Exception($GLOBALS['lang']['nor']); //Skoñcz
		}
	}

	//Koniec
	function apply()
	{
		//Ilo¶æ pozycji w kategorii
		if($this->id)
		{
			if($this->old_cat != $this->data['cat'])
			{
				SetItems($this->old_cat,-1);
				if($this->data['access']==1) SetItems($this->data['cat'],1);
			}
			else
			{
				if($this->old['access'] > $this->data['access']) SetItems($this->old_cat,1);
				if($this->old['access'] < $this->data['access']) SetItems($this->old_cat,-1);
			}
		}
		else
		{
			if($this->data['access']==1) SetItems($this->data['cat'],1);
		}

		#Najnowsze
		if(isset($_GET['act'])) Latest((int)$_GET['act']);

		#OK
		try
		{
			$GLOBALS['db']->commit(); return true;
		}
		catch(PDOException $e)
		{
			throw new Exception($e->errorInfo[2]);
		}
	}
}