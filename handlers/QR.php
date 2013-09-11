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
	public function __construct()
	{
		global
			$aGlobalConfiguration;
			
		require_once $aGlobalConfiguration['files']['handlers'] . 'qrlib/qrlib.php';
	}

	public function onFileRequest(&$pUpload, $_SEO)
	{
		if(!strcasecmp($_SEO[2], 'qr') && $pUpload->file_size < 2048)
		{
			return QRcode::png(file_get_contents($pUpload->local_path));
		}
	}
}