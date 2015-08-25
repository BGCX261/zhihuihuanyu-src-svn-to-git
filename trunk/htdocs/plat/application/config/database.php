<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = "write";
$active_record = TRUE;

$db['read']['hostname'] = "127.0.0.1";
$db['read']['username'] = "root";
$db['read']['password'] = "123";
$db['read']['database'] = "sms_plat";
$db['read']['dbdriver'] = "mysql";
$db['read']['dbprefix'] = "";
$db['read']['pconnect'] = TRUE;
$db['read']['db_debug'] = TRUE;
$db['read']['cache_on'] = FALSE;
$db['read']['cachedir'] = "";
$db['read']['char_set'] = "utf8";
$db['read']['dbcollat'] = "utf8_general_ci";

$db['write']['hostname'] = "127.0.0.1";
$db['write']['username'] = "root";
$db['write']['password'] = "123";
$db['write']['database'] = "sms_plat";
$db['write']['dbdriver'] = "mysql";
$db['write']['dbprefix'] = "";
$db['write']['pconnect'] = TRUE;
$db['write']['db_debug'] = TRUE;
$db['write']['cache_on'] = FALSE;
$db['write']['cachedir'] = "";
$db['write']['char_set'] = "utf8";
$db['write']['dbcollat'] = "utf8_general_ci";

$db['query']['hostname'] = "10.55.17.240";
$db['query']['username'] = "gateway";
$db['query']['password'] = "4d5SqYXTx1BQYLKN";
$db['query']['database'] = "8888_smslog";
$db['query']['dbdriver'] = "mysql";
$db['query']['dbprefix'] = "";
$db['query']['pconnect'] = TRUE;
$db['query']['db_debug'] = TRUE;
$db['query']['cache_on'] = FALSE;
$db['query']['cachedir'] = "";
$db['query']['char_set'] = "";
$db['query']['dbcollat'] = "";

$db['query_month']['hostname'] = "10.55.17.158";
$db['query_month']['username'] = "gateway";
$db['query_month']['password'] = "4d5SqYXTx1BQYLKN";
$db['query_month']['database'] = "monthreport";
$db['query_month']['dbdriver'] = "mysql";
$db['query_month']['dbprefix'] = "";
$db['query_month']['pconnect'] = TRUE;
$db['query_month']['db_debug'] = TRUE;
$db['query_month']['cache_on'] = FALSE;
$db['query_month']['cachedir'] = "";
$db['query_month']['char_set'] = "gbk";
$db['query_month']['dbcollat'] = "gbk_chinese_ci";

/* End of file database.php */
/* Location: ./application/config/database.php */ 