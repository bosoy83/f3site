<?php
class Saver
{
	public
		$id=0,
		$data=array(),
		$old=array(),
		$old_cat,
		$error=null;

	function __construct(&$data, $id, $table, $cols='cat,author,access')
	{
		# Stare dane
		if($id)
		{
			$this->old = $GLOBALS['db'] -> query('SELECT '.$cols.' FROM '.PRE.$table.' WHERE ID='.$id) -> fetch(2);
			$this->old_cat = $this->old ? $this->old['cat'] : null;
		}
		else
		{
			$this->old_cat = $data['cat'];
		}

		# ID, dane
		$this->id = $id;
		$this->data =& $data;

		# Dane istniej±?
		if($this->old_cat !== null)
		{
			$GLOBALS['db']->beginTransaction();
		}
		else
		{
			$this->error = $GLOBALS['lang']['noex']; return false;
		}
	}

	# Prawa
	function hasRight($char, $u=UID)
	{
		# Gdy b³±d obecny
		if($this->error !==null ) return false;

		# Prawa
		if(!Admit($char))
		{
			if(!Admit($this->data['cat'], 'CAT', $u) || ($this->data['cat'] != $this->old_cat && !Admit($this->old_cat, 'CAT', $u)))
			{
				$this->error = $GLOBALS['lang']['nor'];
				return false; //Skoñcz
			}
		}
		return true;
	}

	# Koniec
	function apply()
	{
		# B³±d?
		if($this->error !== null) { $GLOBALS['db']->rollBack(); return false; }

		# Ilo¶æ pozycji w kategorii
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

		#OK
		try
		{
			$GLOBALS['db']->commit(); return true;
		}
		catch(PDOException $e)
		{
			$this->error = $e->errorInfo[0];
		}
	}

	//Poka¿ b³±d
	function showError()
	{
		$GLOBALS['content'] -> info($this->error);
	}
}
?>