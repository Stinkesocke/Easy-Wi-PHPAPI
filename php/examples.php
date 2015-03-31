<?
/**
* File: examples.php
* Author: Marcus Ripkens - Stinkesocke - Game-Experts.de
* Contact: <rm@game-experts.de>
*
* OHNE JEDE GEWAEHRLEISTUNG
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
**/

define('APIPath','/pfad/zu/easyAPI.php'); // Das ist der absolute Pfad zum API Script
define('APIUrl','http://easywi.meinedomain.de/api.php'); // Das ist die URL der Easy Wi API
define('APIUser','Username'); // Das ist der Username der Easy Wi API
define('APIPassword','Passwort'); // Das ist das Passwort der Easy Wi API 


require_once( APIPath );

$api = new easyAPI(APIUrl, APIUser, APIPassword);

# Voiceserver erstellen:

$api->voiceserver->active = true;
$api->voiceserver->private = false;
$api->voiceserver->slots = 12;
$api->voiceserver->shorten = array('ts3');
$api->voiceserver->autoRestart = true;
$api->voiceserver->tsdns = true;
$api->voiceserver->create();

# Gameserver erstellen:

$api->gameserver->active = true;
$api->gameserver->private = false;
$api->gameserver->slots = 12;
$api->gameserver->shorten = array('csgo','css');
$api->gameserver->minram = 512;
$api->gameserver->maxram = 1024;
$api->gameserver->autoRestart = true;
$api->gameserver->installGames = 'A';
$api->gameserver->create();

# User erstellen:

$api->user->active = true;
$api->user->email = 'test@meinedomain.de';
$api->user->username = 'Username';
$api->user->password = 'Password';
$api->user->mail_serverdown = true;
$api->user->mail_ticket = true;
$api->user->create();

