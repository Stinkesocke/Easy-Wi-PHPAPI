<?
/**
* File: functions_examples.php
* Author: Marcus Ripkens - Stinkesocke - Game-Experts.de
* Contact: <rm@game-experts.de>
*
* OHNE JEDE GEWAEHRLEISTUNG
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
*	Dieser Code kann in der functions.php Deines Wordpress Templates verwendet werden
*	Die meisten Funktionen funktionieren nur mit dem Woocommerce Subscriptions Plugin
*	Ohne Woocommerce Subscriptions Plugin gehen nur die Basisfunktionen
*	- createUser
*	- deleteUser
*	- changeUserEmail
*	- order_status_changed
**/

#	Grundlegende Variablen Definieren:

define('APIPath','/pfad/zu/easyAPI.php'); // Das ist der absolute Pfad zum API Script
define('APIUrl','http://easywi.meinedomain.de/api.php'); // Das ist die URL der Easy Wi API
define('APIUser','Username'); // Das ist der Username der Easy Wi API
define('APIPassword','Passwort'); // Das ist das Passwort der Easy Wi API 

#	Hooks definieren:

add_action( 'user_register', 'createUser', 10, 1 );
add_action( 'delete_user', 'deleteUser', 10, 1 );
add_action( 'profile_update', 'changeUserEmail', 10, 2 );
add_action( 'woocommerce_order_status_changed', 'order_status_changed');

// Diese Funktionen gehen nur wenn man Woocommerce Subscriptions verwendet !!
add_action( 'activated_subscription', 'enableServer', 0, 2 );
add_action( 'cancelled_subscription', 'deleteServer', 0, 2 );
add_action( 'subscription_expired', 'disableServer', 0, 2 );
add_action( 'subscription_put_on-hold', 'disableServer', 0, 2 );
add_action( 'subscription_trial_end', 'deleteServer', 0, 2 );
add_action( 'scheduled_subscription_payment', 'disableServer', 0, 2 );
add_action( 'scheduled_subscription_expiration', 'disableServer', 0, 2 ); 

#	Funktionen definieren:

function order_status_changed()
{
    global $wpdb;

    // Hier die Bestellung aus dem Woocommerce verarbeiten
    // Empfohlen. WooCommerce API Client Class von Gerhard Potgieter (https://github.com/kloon/WooCommerce-REST-API-Client-Library)
    // Im Woocommerce die API Aktivieren und dann beim Hauptbenutzer API Credentials erzeugen
    // BEISPIEL:
    /*
    require_once('/pafd/zu/class-wc-api-client.php');
    require_once( APIPath );
    $consumer_key = 'ck_1234567890'; // Woocommerce API User Key
    $consumer_secret = 'cs_1234567890'; // Woocommerce API User Secret
    $store_url = 'http://meinedomain.de/'; // Woocommerce API URL
    $wc_api = new WC_API_Client( $consumer_key, $consumer_secret, $store_url );
    $orders = $wc_api->get_orders($params = array( 'status' => 'processing' )); // Holt alle Bestellungen die auf 'In Bearbeitung' stehen

    foreach($orders as $order)
    {
        $id = $o->id;
        $order_number = $o->order_number;
        $username = $o->customer->username;
        $user_id = $o->customer->id;

        $minram = 512;
        $maxram = 1024;
        $branding = true;

        foreach($o->line_items as $item)
        {
            // Example um zum Beispiel die bestellten Slots auszulesen, hier wird Woocommers Addons Plugin benutzt
            // if(!$slots = $wpdb->get_var( "select meta_value from wp_woocommerce_order_itemmeta where order_item_id = " . $item->id . " and meta_key like 'Slots %'" ))
            // {
            //     $slots = 1; // Standardwert wenn keine Slots gefunden
            // }

            if($item->sku == 'ts3') // Voiceserver ist immer ts3 als SKU
            {
                    $api = new easyAPI(APIUrl, APIUser, APIPassword);

                    $api->voiceserver->active = true;
                    $api->voiceserver->private = false;
                    $api->voiceserver->slots = $slots;
                    $api->voiceserver->shorten = array($sku);
                    $api->voiceserver->identifyUserBy = 'user_externalid';
                    $api->voiceserver->externalUserID = $user_id;
                    $api->voiceserver->autoRestart = true;
                    $api->voiceserver->tsdns = true;
                    $api->voiceserver->create();
            }
            else // Na dann wohl ein Gameserver
            {
                $api = new easyAPI(APIUrl, APIUser, APIPassword);
                $api->gameserver->active = true;

                $api->gameserver->private = false;
                $api->gameserver->slots = $slots;
                $api->gameserver->shorten = explode('-',$item->sku);
                $api->gameserver->identifyUserBy = 'user_externalid';
                $api->gameserver->externalUserID = $user_id;
                $api->gameserver->minram = $minram;
                $api->gameserver->maxram = $maxram;
                $api->gameserver->brandname = $branding;
                $api->gameserver->identifyServerBy = 'server_external_id';
                $api->gameserver->externalServerID = $item->id;
                $api->gameserver->autoRestart = true;
                $api->gameserver->installGames = 'A';
                $api->gameserver->create();
            }


        }



        $wc_api->update_order( $id, $data = array( 'status' => 'completed' ));
    }

    */
} 


function enableServer($user_id, $subscription_key) // Diese Funktion geht so nur mit Woocommerce Subscription
{
    global $wpdb;
    require_once ( APIPath );
    $subscription = WC_Subscriptions_Manager::get_subscription( $subscription_key );
    $order_id = $subscription['order_id'];
    $product_id = $subscription['product_id'];
    $status = $subscription['status'];
    $server_id = $wpdb->get_var( "SELECT order_item_id FROM wp_woocommerce_order_items where order_id=$order_id and order_item_type='line_item'" );

    $api = new easyAPI(APIUrl, APIUser, APIPassword);
    $api->gameserver->active = true;
    $api->gameserver->identifyUserBy = 'user_externalid';
    $api->gameserver->externalUserID = $user_id;
    $api->gameserver->identifyServerBy = 'server_external_id';
    $api->gameserver->externalServerID = $server_id;
    $api->gameserver->changeActiveState(true);
} 

function deleteServer($user_id, $subscription_key) // Diese Funktion geht so nur mit Woocommerce Subscription
{
    global $wpdb;

    require_once ( APIPath );

    $subscription = WC_Subscriptions_Manager::get_subscription( $subscription_key );
    $order_id = $subscription['order_id'];
    $product_id = $subscription['product_id'];
    $status = $subscription['status'];
    $server_id = $wpdb->get_var( "SELECT order_item_id FROM wp_woocommerce_order_items where order_id=$order_id and order_item_type='line_item'" );
    $product_id = $wpdb->get_var( "SELECT meta_value FROM wp_woocommerce_order_itemmeta where order_item_id=" . $server_id . " and meta_key = '_product_id'");
    $product_sku = $wpdb->get_var( "SELECT meta_value FROM wp_postmeta where post_id=" . $product_id . " and meta_key = '_sku'");

    $api = new easyAPI(APIUrl, APIUser, APIPassword);
    if($product_sku == 'ts3')
    {
        $api->voiceserver->identifyUserBy = 'user_externalid';
        $api->voiceserver->externalUserID = $user_id;
        $api->voiceserver->identifyServerBy = 'server_external_id';
        $api->voiceserver->externalServerID = $server_id;
        $api->voiceserver->delete();
    }
    else
    {
        $api->gameserver->identifyUserBy = 'user_externalid';
        $api->gameserver->externalUserID = $user_id;
        $api->gameserver->identifyServerBy = 'server_external_id';
        $api->gameserver->externalServerID = $server_id;
        $api->gameserver->delete();
    }
} 

function disableServer($user_id, $subscription_key) // Diese Funktion geht so nur mit Woocommerce Subscription
{
    global $wpdb;

    require_once ( APIPath );

    $subscription = WC_Subscriptions_Manager::get_subscription( $subscription_key );
    $order_id = $subscription['order_id'];
    $product_id = $subscription['product_id'];
    $status = $subscription['status'];
    $server_id = $wpdb->get_var( "SELECT order_item_id FROM wp_woocommerce_order_items where order_id=$order_id and order_item_type='line_item'" );

    $api = new easyAPI(APIUrl, APIUser, APIPassword);
    $api->gameserver->active = false;
    $api->gameserver->identifyUserBy = 'user_externalid';
    $api->gameserver->externalUserID = $user_id;
    $api->gameserver->identifyServerBy = 'server_external_id';
    $api->gameserver->externalServerID = $server_id;
    $api->gameserver->changeActiveState(false);
} 

function deleteUser($user_id)
{
    require_once ( APIPath );

    $user = get_user_by('id',$user_id);
    $user_login = stripslashes($user->user_login);
    $user_email = stripslashes($user->user_email);

    $api = new easyAPI(APIUrl, APIUser, APIPassword);
    $api->user->identify_by = 'external_id';
    $api->user->external_id = $user_id;
    $api->user->delete();
} 

function changeUserEmail($user_id)
{
    require_once ( APIPath );

    $user = get_user_by('id',$user_id);
    $user_login = stripslashes($user->user_login);
    $user_email = stripslashes($user->user_email);

    $api = new easyAPI(APIUrl, APIUser, APIPassword);
    $api->user->email = $user_email;
    $api->user->identify_by = 'external_id';
    $api->user->external_id = $user_id;
    $api->user->changeUserData();
} 

function createUser($user_id)
{
    require_once ( APIPath );

    $user = get_user_by('id',$user_id);
    $user_login = stripslashes($user->user_login);
    $user_email = stripslashes($user->user_email);

    $api = new easyAPI(APIUrl, APIUser, APIPassword);
    $api->user->active = true;
    $api->user->identify_by = 'external_id';
    $api->user->external_id = $user_id;
    $api->user->email = $user_email;
    $api->user->username = $user_login;
    $api->user->password = md5($user_id. '-' . $user_login);
    $api->user->mail_serverdown = true;
    $api->user->mail_ticket = true;
    echo $api->user->create();
}



