<?php
/**
 *	GeSHi implementation for phpuush
 *
 *	@author Blake <blake@totalru.in>
 *	@author PwnFlakes <pwnflak.es>
 *	@author Westie <westie@typefish.co.uk>
 *
 *	@version: 0.1
 */
 
 
class Handler_GeSHi
{
	public function __construct()
	{
		global
			$aGlobalConfiguration;
			
		require_once $aGlobalConfiguration['files']['handlers'] . 'geshi/geshi.php';
	}

	public function onFileRequest(&$pUpload, $_SEO)
	{
		global
			$aGlobalConfiguration,
			$pFunctions;
		
		if(in_array(strtolower($_SEO[2]), unserialize(file_get_contents($aGlobalConfiguration["files"]["handlers"] . "geshi/langs.ob"))))
		{
			$sCacheItem = $aGlobalConfiguration["files"]["upload"] . "cache/geshi-" . strtolower($_SEO[2]) . "-" . $pUpload->file_hash . ".html";
			$sRender = null;
			
			if(!file_exists($sCacheItem))
			{
				$sContents = file_get_contents($pUpload->local_path);
				
				$pGeshi = new Geshi($sContents, $_SEO[2]);
				$sRender = $pGeshi->parse_code();
				
				file_put_contents($sCacheItem, $sRender);
			}
			else
			{
				$sRender = file_get_contents($sCacheItem);
			}
			
			if(md5($sRender) != $pUpload->file_hash)
			{
				header("Cache-Control: public");
				header("Last-Modified: ".date("r", filemtime($sCacheItem)));
				header("Content-Length: ".filesize($sCacheItem));
				header("Content-Type: text/html");
				header("Content-Transfer-Encoding: binary");
				header("Content-MD5: ".md5_file($sCacheItem));
				header("Content-Disposition: inline; filename=".$pFunctions->quote($pUpload->file_name));
			
				echo $sRender;
				return true;
			}
		}
	}
}