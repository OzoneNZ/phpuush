<?php
/**
 *	File handler for phpuush
 *
 *	@author Blake <blake@totalru.in>
 *	@author PwnFlakes <pwnflak.es>
 *	@author Westie <westie@typefish.co.uk>
 *
 *	@version: 0.1
 */


/**
 *	We'll just load our file.
 */
$pUpload = new Upload();
$pUpload->loadAlias($_SEO[0]);

if(!isset($pUpload->id) || $pUpload->is_deleted)
{
	header("Location: /");
	return;
}


$aHeaders = $pFunctions->getHeaders();

if(isset($aHeaders["If-Modified-Since"]))
{
	$iCachedModificationDate = strtotime($aHeaders["If-Modified-Since"]);

	if($iCachedModificationDate == filemtime($pUpload->local_path))
	{
		$pUpload->incrementViews();
		
		header("Not Modified: Use browser cache", true, 304);
		return;
	}
}


/**
 *	Return things to the server...
 */
if((isset($_GET["height"]) || isset($_GET["width"])) && substr($pUpload->mime_type, 0, 6) != "image/")
{
	return;
}
elseif(isset($_SEO[1]))
{
	if(!$pUpload->verifyProtectAlias(@$_SEO[1]))
	{
		header("Location: /");
		return;
	}
	
	if(isset($_SEO[2]))
	{
		if(!Handlers::loopThrough($pUpload, $_SEO))
		{
			header("Location: /");
			return;
		}
	}
	else
	{
		header("Cache-Control: public");
		header("Last-Modified: ".date("r", filemtime($pUpload->local_path)));
		header("Content-Length: {$pUpload->file_size}");
		header("Content-Type: {$pUpload->mime_type}");
		header("Content-Transfer-Encoding: binary");
		header("Content-MD5: {$pUpload->file_hash}");
		header("Content-Disposition: inline; filename=".$pFunctions->quote($pUpload->file_name));
		
		readfile($pUpload->local_path);
	}
	
	$pUpload->incrementViews();
}
else
{
	header("Location: /");
}