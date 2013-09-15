# phpuush

So, you're probably confused as to what this is. Well, it's a proxy for puush. The developers for puush decided to be all stupid and refused to implement useful features like SFTP and FTP.

So, my absolutely brilliant friend [mave](https://github.com/mave) decided to write a new proxy for puush in node.js. This, as far as we know, was the first alternative implementation of puush.

However, I wanted to experiment in the joys that is hiphop-php. So, I made this! It was designed on Apache, tested on nginx and compiled with hiphop-php.

I think I'm one of the only people in the world apart from Facebook using hiphop-php in a product environment, as it may seem.

# Oh. Okay. What do I do next?

Well, many things. You could pick your nose and eat it - or you could follow some installation cues and get the damned thing working.

## Create the database

MySQL support through the PHP PDO extension has been implemented to replace the SQLite storage. 

Go into the `databases` directory and import `phpuush.sql` into your MySQL database to initialise it for use.

For anyone still looking for a SQLite version, the `sqlite` branch has a final SQLite release.

## More configuration

Here's a sample `configuration.php` file:

    $aGlobalConfiguration = array
	(
		"databases" => array
		(
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

You'll need to enter your MySQL credentials AND change your domain, otherwise your puush links won't be of much use.

## Setting up webservers

Oh, so you actually want this thing to be live, eh? Well, what you need to do is set up whatever webserver you have to either accept connections on another port that is not `80` or listen for `puush.me` - whatever floats your boat. There are a million ways to set it up.

I'll add examples when I can be bothered.

## Setting up your client on Windows - r85

You just edit `%AppData%\puush\puush.ini` to resemble something like this:

    ProxyServer = someproxy
    ProxyPort = someport

And then restart puush.

## Setting up your client on OS X - r62

### Choosing the domain to point to

Thankfully I managed to negate the need for a SSL certificate. This has just made the task from extremely hard to relatively easy.

First thing you have to do is add this to the `/private/etc/hosts` file:

    <address> phpuushed

Then, you replace your puush binary with the one in the repo. You can find this in
`setup/binaries/OS X/puush` - you need to replace the binary in
`puush.app/Contents/MacOS/puush` with the one on this repo.

The only change is that I've changed `https://puush.me/` to `http://phpuushed/`.

## Using the client

You will need to register by going to:

`http://someproxy:someport/page/register`

Don't want to allow anyone else to register? Just rename `controllers/page/register.php` or whatever you want.

Know of any improvements? Hit me!