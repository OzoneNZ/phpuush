<?php
/**
 *	Database class for phpuush
 *
 *	@author Blake <blake@totalru.in>
 *	@author PwnFlakes <pwnflak.es>
 *	@author Westie <westie@typefish.co.uk>
 *
 *	@version: 0.1
 */
 

class Database
{
	/**
	 *	Database query count. Useful for statistics?
	 */
	public
		$iCount = 0;
		
	
	/**
	 *	PDO connection object
	 */
	public
		$pMySQL;
		
		
	/**
	 *	Singleton method
	 */
	public static function getInstance()
	{
		static $pInstance = null;
		
		if($pInstance === null)
		{
			global $aGlobalConfiguration;
			
			$pInstance = new Database($aGlobalConfiguration["mysql"]);
		}
		
		return $pInstance;
	}
	
	
	/**
	 *	Database constructor, PDO connection initialiser.
	 */
	public function __construct(array $aCredentials)
	{
		$this->pMySQL = new PDO
		(
			'mysql:host=' . $aCredentials['hostname'] . ';dbname=' . $aCredentials['database'],
			$aCredentials['username'],
			$aCredentials['password']
		);
	}
	
	
	/**
	 *	Manually-implemented close method, since we're no longer extending SQLite3
	 */
	public function close()
	{
		if(is_object($this->pMySQL))
		{
			$this->pMySQL = null;
		}
	}
	
	
	/**
	 *	Customised query method, with built in prepared statements if necessary.
	 */
	public function query($sQuery, array $aArguments = null)
	{
		++$this->iCount;
		
		if($aArguments === null)
		{
			return $this->pMySQL->query($sQuery);
		}
		
		$pStatement = $this->pMySQL->prepare($sQuery);
		
		if($pStatement->execute($aArguments))
		{
			return $pStatement;
		}
		
		return false;
	}
	
	
	/**
	 *	Customised exec to allow support for prepared stuff.
	 */
	public function exec($sQuery, array $aArguments = null)
	{
		if($aArguments === null)
		{
			return $this->pMySQL->query($sQuery);
		}
		
		$pResult = $this->query($sQuery, $aArguments);
		return $pResult !== false;
	}
	
	
	/**
	 *	Method to fetch fields from database, as objects.
	 */
	public function fetch($sQuery, array $aArguments = null)
	{
		$aReturn = array();
		$pResult = $this->query($sQuery, $aArguments);
		
		if($pResult)
		{
			foreach($pResult->fetchAll(PDO::FETCH_ASSOC) as $aResult)
			{
				$aReturn[] = (object) $aResult;
			}
			
			return $aReturn;
		}
		
		return null;
	}
	
	
	/**
	 *	Shorthand quote method
	 */
	public function quote($sString)
	{
		return $this->pMySQL->quote($sString);
	}
	
	
	/**
	 *	Customised insert method
	 */
	public function insert($sTable, $aValues = array())
	{
		$aKeys = array_keys($aValues);
		$aFragments = array();
		
		foreach($aValues as $sKey => $sValue)
		{
			$aFragments[] = ":{$sKey}";
		}

		$this->exec("INSERT INTO {$sTable} (".implode(", ", $aKeys).") VALUES (".implode(", ", $aFragments).")", $aValues);
		return $this->pMySQL->lastInsertId();
	}
}