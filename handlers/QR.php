<?php
/**
 *	QR Code output handler for phpuush
 *
 *	@author Blake <blake@totalru.in>
 *	@author PwnFlakes <pwnflak.es>
 *	@author Westie <westie@typefish.co.uk>
 *
 *	@version: 0.1
 */
 
 
class Handler_QR
{
	public function onFileRequest(&$pUpload, $_SEO)
	{
		global
			$aGlobalConfiguration,
			$pFunctions;
	
		if(!strcasecmp($_SEO[2], 'qr') && $pUpload->file_size < 1024)
		{
			$sCacheItem = $aGlobalConfiguration["files"]["upload"]."cache/qr-".strtolower($_SEO[0])."-".$pUpload->file_hash.".png";
			$sRender = null;
			
			if(!file_exists($sCacheItem))
			{
				$sRender = file_get_contents("http://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=".urlencode(file_get_contents($pUpload->local_path)));
				file_put_contents($sCacheItem, $sRender);
			}
			else
			{
				$sRender = file_get_contents($sCacheItem);
			}
			
			header("Cache-Control: public");
			header("Last-Modified: ".date("r", filemtime($sCacheItem)));
			header("Content-Length: ".filesize($sCacheItem));
			header("Content-Type: image/png");
			header("Content-Transfer-Encoding: binary");
			header("Content-MD5: ".md5_file($sCacheItem));
			header("Content-Disposition: inline; filename=".$pFunctions->quote($pUpload->file_name));
		
			echo $sRender;
			return true;
		}
	}
}