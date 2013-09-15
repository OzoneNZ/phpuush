<?php
/**
 *	Configuration for phpuush
 *
 *	@author Blake <blake@totalru.in>
 *	@author PwnFlakes <pwnflak.es>
 *	@author Westie <westie@typefish.co.uk>
 *
 *	@version: 0.1
 */


$aGlobalConfiguration = array
(
	"databases" => array
	(
		"sql" => __DIR__."/databases/phpuush.db",
		"mime" => __DIR__."/databases/mime.types",
	),
	
	"files" => array
	(
		"handlers" => __DIR__."/handlers/",
		"upload" => __DIR__."/uploads/",
		"domain" => "http://your.domain.tld",
	),
	
	"mysql" => array
	(
		"hostname" => "localhost",
		"username" => "root",
		"password" => "root",
		"database" => "phpuush"
	)
	
);