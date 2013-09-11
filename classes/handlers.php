<?php
/**
 *	Custom phpuush URL handlers
 *
 *	@author Blake <blake@totalru.in>
 *	@author PwnFlakes <pwnflak.es>
 *	@author Westie <westie@typefish.co.uk>
 *
 *	@version: 0.1
 */
 
class Handlers
{
	public static function loopThrough(&$pUpload, $_SEO)
	{
		global
			$aGlobalConfiguration;
			
		$pDirectory = opendir($aGlobalConfiguration['files']['handlers']);
		
		while($sFile = readdir($pDirectory))
		{
			if(strpos($sFile, '.php') !== false && include($aGlobalConfiguration['files']['handlers'] . $sFile))
			{
				if(self::handOver($pUpload, 'Handler_' . substr($sFile, 0, strpos($sFile, '.php')), $_SEO))
				{
					return true;
				}
			}
		}
		
		return false;
	}
	
	private static function handOver(&$pUpload, $sHandler, $_SEO)
	{
		if(class_exists($sHandler) && method_exists($sHandler, 'onFileRequest'))
		{
			$pInstance = new $sHandler;
			$pInstance->onFileRequest($pUpload, $_SEO);
		}
		else
		{
			return false;
		}
	}
}