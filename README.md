# phpuush

phpuush is an alternative [puush](http://puush.me) implementation in PHP, complete with all the functionality of the original.

Currently, phpuush comes with two storage solutions, SQLite and MySQL.

phpuush works by intercepting requests to the puush service and not forwarding them.

# Prerequisites

Requirements for operation of phpuush differ by the storage system. The following are tested and working:

### Base

* **PHP 5.4** or higher (lower versions such as 5.3 may work but are not guaranteed)
* HTTP server capable of **URL rewriting** (configuration for Apache, nginx and lighttpd is supplied)

### MySQL
* **MySQL 5** or higher
* **PHP PDO** driver with MySQL support (php5-pdo on Debian / Ubuntu and php-pdo on CentOS)

### SQLite
* **SQLite3** PHP library (php5-sqlite on Debian / Ubuntu)
* **Full read/write permissions** on the database file

# Installation

### MySQL

The `phpuush.sql` file located inside the `databases` directory provides the base table structures. You can import this using any method you wish.

For example, you could use this command to import it in Linux:

`mysql -u username -ppassword -h hostname -D database < databases/phpuush.sql`

### SQLite

A blank `phpuush.db-dist` file is provided in the `databases` directory, ready for use. Simply rename this to `phpuush.db`.

# Server Configuration

All configuration directives are stored in `configuration.php`. Changes must be made before phpuush can be used.

### MySQL

The entire `mysql` section will need modification to include your MySQL username, password, hostname and database.

### SQLite

The default value for this directive points to the `databases/phpuush.db` file. If you have already created this, you do not need to make any further changes.

### HTTP Server

There are a lot of different ways to set this up, and sample configurations are supplied in the `setup/httpdconf` directory for:

* Apache with mod_rewrite
* nginx with HttpRewriteModule
* lighttpd with mod_rewrite

Any other HTTP server with rewriting and PHP capability should work fine.

# Registration

You can register new accounts with phpuush by visiting `http://your-domain/page/register`.

Once you have registered, you have the option to make the registration page self-destruct. Please note that if you do not do this, your registration will be publically available.

# Client Configuration

### Windows

Windows configuration is the simplest with the built in `ProxyServer` and `ProxyPort` settings that puush has.

1. Close the puush client if it is open
2. Set the `ProxyServer = ip-address-of-your-phpuush` directive
3. Set the `ProxyPort = port-number-of-your-phpuush` directive
4. Re-open puush and authenticate for the first time

### Mac OS X (r62)

1. Close the puush client if it is open
2. Edit the `/private/etc/hosts` file with elevated privileges
3. Add a new entry with `<ip address of your phpuush> phpuushed`
4. Replace your `puush.app/Contents/MacOS/puush` binary file with the copy supplied in `setup/binaries/OS X/puush`
5. Re-open puush and authenticate for the first time
