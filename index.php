<?php
/**
 *	phpuush service for PHP
 *
 *	@author Blake <blake@totalru.in>
 *	@author PwnFlakes <pwnflak.es>
 *	@author Westie <westie@typefish.co.uk>
 *
 *	@version: 0.1
 */


/**
 *	Include our classes
 */
include "classes/functions.php";
include "classes/database.php";
include "classes/element.php";
include "classes/handlers.php";
include "classes/user.php";
include "classes/upload.php";


/**
 *	Include our configuration files
 */
include "configuration.php";


/**
 *	Attempt initialisation of the MySQL connection
 */
try
{
	$pDatabase = Database::getInstance();
}
catch(Exception $pException)
{
	echo "-1";
	exit;
}


/**
 *	Do some defining stuff
 */
$pFunctions = Functions::getInstance();
$_SEO = $pFunctions->translateRequestURI();


/**
 *	Add a bit of branding - branding is always cool!
 */
header("X-Powered-By: phpuush");


/**
 *	Delegate to our controllers
 */
try
{	
	if(isset($_SEO[0]))
	{
		if($_SEO[0] == "api")
		{
			if(file_exists("controllers/api/{$_SEO[1]}.php"))
			{
				require "controllers/api/{$_SEO[1]}.php";
			}
			else
			{
				throw new Exception("API method does not exist.");
			}
		}
		elseif($_SEO[0] == "dl")
		{
			return "85";
		}
		elseif($_SEO[0] == "page")
		{
			if(file_exists("controllers/page/{$_SEO[1]}.php"))
			{
				require "controllers/page/{$_SEO[1]}.php";
			}
			else
			{
				throw new Exception("API method does not exist.");
			}
		}
		else
		{
			require "controllers/file-handler.php";
		}
	}
	else
	{
		echo "This is a phpuush endpoint.";
	}
}
catch(Exception $pException)
{
	echo "-1";
}


$pDatabase->close();
exit;
