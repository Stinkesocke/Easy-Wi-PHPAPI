<?
class easyAPI
{
    public $gameserver;
    public $voiceserver;
    public $mysqlserver;
    public $user;

    public $type;
    public $xml;
    public $xmlkey;

    function __construct($url, $user, $password)
    {
        $this->gameserver = new gameserver($url, $user, $password);
        $this->voiceserver = new voiceserver($url, $user, $password);
        $this->mysqlserver = new mysqlserver($url, $user, $password);
        $this->user = new easyuser($url, $user, $password);
    }

    function createElement($data, $name)
    {
        if(isset($data))
        {
            if(is_array($data))
            {
                foreach($data as $dataitem)
                {
                    if($dataitem === true) {$dataitem = 'Y';} elseif($dataitem === false) {$dataitem = 'N';}
                    $listServerXML = $this->xml->createElement($name, $dataitem);
                    $this->xmlkey->appendChild($listServerXML);
                }
            }
            else
            {
                if($data === true) {$data = 'Y';} elseif($data === false) {$data = 'N';}
                $listServerXML = $this->xml->createElement($name, $data);
                $this->xmlkey->appendChild($listServerXML);
            }
        }
        else
        {
                $listServerXML = $this->xml->createElement($name);
                $this->xmlkey->appendChild($listServerXML);
        }
    }

    function sendData($type, $url, $user, $pwd)
    {
        $xml = $this->createXML();
        $data = 'pwd='.urlencode($pwd).'&user='.urlencode($user).'&xml='.urlencode(base64_encode($xml)).'&type='.urlencode($type);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output=curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}

class gameserver extends easyAPI
{
    public $url;
    public $user;
    public $pwd;

    public $action;
    public $active;
    public $private;
    public $slots;
    public $shorten;
    public $primary;
    public $identifyUserBy;
    public $localUserID;
    public $externalUserID;
    public $username;
    public $identifyServerBy;
    public $localServerID;
    public $externalServerID;
    public $taskset;
    public $eacallowed;
    public $brandname;
    public $tvenable;
    public $pallowed;
    public $name;
    public $homeDirLabel;
    public $hdd;
    public $ip;
    public $port;
    public $port2;
    public $port3;
    public $port4;
    public $port5;
    public $minram;
    public $maxram;
    public $hostID;
    public $cores;
    public $coreCount;
    public $customID;
    public $hostExternalID;
    public $initialpassword;
    public $installGames;
    public $autoRestart;
    public $ftpUser;

    function __construct($url, $user, $password)
    {
        $this->url = $url;
        $this->user = $user;
        $this->pwd = $password;

        $this->type = 'server';
        $this->typesend = 'gserver';

        $this->active = true;
        $this->private = true;
        $this->slots = 1;
        $this->eacallowed = true;
        $this->tvenable = true;
        $this->pallowed = true;
        $this->installGames = 'A';
        $this->autoRestart = true;

        $imp = new DOMImplementation;
        $dtd = $imp->createDocumentType($this->type, '', '');
        $this->xml = $imp->createDocument("", "", $dtd);
        $this->xml->encoding = 'UTF-8';
    }

    function create()
    {
        $this->action = 'add';
        $response =  simplexml_load_string($this->sendData($this->typesend, $this->url, $this->user, $this->pwd));
        return $response->action == 'success' ? true : $response->errors;
    }

    function delete()
    {
        $this->action = 'del';
        $response =  simplexml_load_string($this->sendData($this->typesend, $this->url, $this->user, $this->pwd));
        return $response->action == 'success' ? true : $response->errors;
    }

    function changeActiveState($state)
    {
        $this->action = 'mod';
        $this->active = $state;
        $response =  simplexml_load_string($this->sendData($this->typesend, $this->url, $this->user, $this->pwd));
        return $response->action == 'success' ? true : $response->errors;
    }

    function createXML()
    {
        $this->xmlkey = $this->xml->createElement($this->type);
        $this->createElement($this->action, 'action');
        $this->createElement($this->active, 'active');
        $this->createElement($this->private, 'private');
        $this->createElement($this->shorten, 'shorten');
        $this->createElement($this->primary, 'primary');
        $this->createElement($this->slots, 'slots');
        $this->createElement($this->identifyUserBy, 'identify_user_by');
        $this->createElement($this->localUserID, 'user_localid');
        $this->createElement($this->externalUserID, 'user_externalid');
        $this->createElement($this->username, 'username');
        $this->createElement($this->identifyServerBy, 'identify_server_by');
        $this->createElement($this->localServerID, 'server_local_id');
        $this->createElement($this->externalServerID, 'server_external_id');
        $this->createElement($this->taskset, 'taskset');
        $this->createElement($this->eacallowed, 'eacallowed');
        $this->createElement($this->brandname, 'brandname');
        $this->createElement($this->tvenable, 'tvenable');
        $this->createElement($this->pallowed, 'pallowed');
        $this->createElement($this->name, 'name');
        $this->createElement($this->homeDirLabel, 'home_label');
        $this->createElement($this->hdd, 'hdd');
        $this->createElement($this->ip, 'ip');
        $this->createElement($this->port, 'port');
        $this->createElement($this->port2, 'port2');
        $this->createElement($this->port3, 'port3');
        $this->createElement($this->port4, 'port4');
        $this->createElement($this->port5, 'port5');
        $this->createElement($this->minram, 'minram');
        $this->createElement($this->maxram, 'maxram');
        $this->createElement($this->hostID, 'master_server_id');
        $this->createElement($this->cores, 'cores');
        $this->createElement($this->coreCount, 'coreCount');
        $this->createElement($this->customID, 'customID');
        $this->createElement($this->hostExternalID, 'master_server_external_id');
        $this->createElement($this->initialpassword, 'initialpassword');
        $this->createElement($this->installGames, 'installGames');
        $this->createElement($this->autoRestart, 'autoRestart');
        $this->createElement($this->ftpUser, 'ftpUser');
        $this->xml->appendChild($this->xmlkey);
        $this->xml->formatOutput = true;
        return $this->xml->saveXML();
    }
}

class voiceserver extends easyAPI
{
    public $url;
    public $user;
    public $pwd;

    public $action;
    public $private;
    public $active;
    public $username;
    public $name;
    public $shorten;
    public $slots;
    public $ip;
    public $port;
    public $identifyUserBy;
    public $localUserID;
    public $identifyServerBy;
    public $externalUserID;
    public $localServerID;
    public $externalServerID;
    public $max_download_total_bandwidth;
    public $max_upload_total_bandwidth;
    public $maxtraffic;
    public $forcebanner;
    public $forcebutton;
    public $forceservertag;
    public $forcewelcome;
    public $lendserver;
    public $backup;
    public $masterServerID;
    public $masterServerExternalID;
    public $flexSlots;
    public $flexSlotsFree;
    public $flexSlotsPercent;
    public $tsdns;
    public $dns;
    public $autoRestart;

    function __construct($url, $user, $password)
    {
        $this->url = $url;
        $this->user = $user;
        $this->pwd = $password;

        $this->type = 'voice';
        $this->typesend = 'voice';

        $this->active = true;
        $this->slots = 1;
        $this->autoRestart = true;

        $imp = new DOMImplementation;
        $dtd = $imp->createDocumentType($this->type, '', '');
        $this->xml = $imp->createDocument("", "", $dtd);
        $this->xml->encoding = 'UTF-8';
    }

    function create()
    {
        $this->action = 'add';
        $response =  simplexml_load_string($this->sendData($this->typesend, $this->url, $this->user, $this->pwd));
        return $response->action == 'success' ? true : $response->errors;
    }

    function delete()
    {
        $this->action = 'del';
        $response =  simplexml_load_string($this->sendData($this->typesend, $this->url, $this->user, $this->pwd));
        return $response->action == 'success' ? true : $response->errors;
    }

    function changeActiveState($state)
    {
        $this->action = 'mod';
        $this->active = $state;
        $response =  simplexml_load_string($this->sendData($this->typesend, $this->url, $this->user, $this->pwd));
        return $response->action == 'success' ? true : $response->errors;
    }

    function createXML()
    {
        $this->xmlkey = $this->xml->createElement($this->type);
        $this->createElement($this->action, 'action');
        $this->createElement($this->private, 'private');
        $this->createElement($this->port, 'port');
        $this->createElement($this->active, 'active');
        $this->createElement($this->ip, 'address');
        $this->createElement($this->max_download_total_bandwidth, 'max_download_total_bandwidth');
        $this->createElement($this->max_upload_total_bandwidth, 'max_upload_total_bandwidth');
        $this->createElement($this->maxtraffic, 'maxtraffic');
        $this->createElement($this->forcebanner, 'forcebanner');
        $this->createElement($this->forcebutton, 'forcebutton');
        $this->createElement($this->forceservertag, 'forceservertag');
        $this->createElement($this->forcewelcome, 'forcewelcome');
        $this->createElement($this->lendserver, 'lendserver');
        $this->createElement($this->backup, 'backup');
        $this->createElement($this->identifyServerBy, 'identify_server_by');
        $this->createElement($this->localServerID, 'server_local_id');
        $this->createElement($this->externalServerID, 'server_external_id');
        $this->createElement($this->shorten, 'shorten');
        $this->createElement($this->slots, 'slots');
        $this->createElement($this->identifyUserBy, 'identify_user_by');
        $this->createElement($this->localUserID, 'user_localid');
        $this->createElement($this->externalUserID, 'user_externalid');
        $this->createElement($this->username, 'username');
        $this->createElement($this->name, 'name');
        $this->createElement($this->tsdns, 'tsdns');
        $this->createElement($this->dns, 'usedns');
        $this->xml->appendChild($this->xmlkey);
        $this->xml->formatOutput = true;
        return $this->xml->saveXML();
    }
}

class mysqlserver extends easyAPI
{
    public $url;
    public $user;
    public $pwd;

    public $action;
    public $active;
    public $username;
    public $identifyUserBy;
    public $localUserID;
    public $identifyServerBy;
    public $externalUserID;
    public $localServerID;
    public $externalServerID;

    function __construct($url, $user, $password)
    {
        $this->url = $url;
        $this->user = $user;
        $this->pwd = $password;

        $this->type = 'mysql';
        $this->typesend = 'mysql';

        $this->active = true;

        $imp = new DOMImplementation;
        $dtd = $imp->createDocumentType($this->type, '', '');
        $this->xml = $imp->createDocument("", "", $dtd);
        $this->xml->encoding = 'UTF-8';
    }

    function create()
    {
        $this->action = 'add';
        $response =  simplexml_load_string($this->sendData($this->typesend, $this->url, $this->user, $this->pwd));
        return $response->action == 'success' ? true : $response->errors;
    }

    function delete()
    {
        $this->action = 'del';
        $response =  simplexml_load_string($this->sendData($this->typesend, $this->url, $this->user, $this->pwd));
        return $response->action == 'success' ? true : $response->errors;
    }

    function changeActiveState($state)
    {
        $this->action = 'mod';
        $this->active = $state;
        $response =  simplexml_load_string($this->sendData($this->typesend, $this->url, $this->user, $this->pwd));
        return $response->action == 'success' ? true : $response->errors;
    }

    function createXML()
    {
        $this->xmlkey = $this->xml->createElement($this->type);
        $this->createElement($this->action, 'action');
        $this->createElement($this->active, 'active');
        $this->createElement($this->identifyServerBy, 'identify_server_by');
        $this->createElement($this->localServerID, 'server_local_id');
        $this->createElement($this->externalServerID, 'server_external_id');
        $this->createElement($this->identifyUserBy, 'identify_user_by');
        $this->createElement($this->localUserID, 'user_localid');
        $this->createElement($this->externalUserID, 'user_externalid');
        $this->createElement($this->username, 'username');
        $this->xml->appendChild($this->xmlkey);
        $this->xml->formatOutput = true;
        return $this->xml->saveXML();
    }
}

class easyuser extends easyAPI
{
    public $url;
    public $user;
    public $pwd;

    public $action;
    public $active;
    public $identify_by;
    public $username;
    public $external_id;
    public $localid;
    public $email;
    public $password;
    public $vname;
    public $name;
    public $phone;
    public $handy;
    public $fax;
    public $city;
    public $cityn;
    public $street;
    public $streetn;
    public $salutation;
    public $birthday;
    public $country;
    public $fdlpath;
    public $mail_backup;
    public $mail_gsupdate;
    public $mail_securitybreach;
    public $mail_serverdown;
    public $mail_ticket;
    public $mail_vserver;

    function __construct($url, $user, $password)
    {
        $this->url = $url;
        $this->user = $user;
        $this->pwd = $password;

        $this->type = 'users';
        $this->typesend = 'user';

        $this->active = true;

        $imp = new DOMImplementation;
        $dtd = $imp->createDocumentType($this->type, '', '');
        $this->xml = $imp->createDocument("", "", $dtd);
        $this->xml->encoding = 'UTF-8';
    }

    function create()
    {
        $this->action = 'add';
        $response =  simplexml_load_string($this->sendData($this->typesend, $this->url, $this->user, $this->pwd));
        return $response->action == 'success' ? true : $response->errors;
    }

    function delete()
    {
        $this->action = 'del';
        $response =  simplexml_load_string($this->sendData($this->typesend, $this->url, $this->user, $this->pwd));
        return $response->action == 'success' ? true : $response->errors;
    }

    function changeUserData()
    {
        $this->action = 'mod';
        $response =  simplexml_load_string($this->sendData($this->typesend, $this->url, $this->user, $this->pwd));
        return $response->action == 'success' ? true : $response->errors;
    }

    function createXML()
    {
        $this->xmlkey = $this->xml->createElement($this->type);
        $this->createElement($this->action, 'action');
        $this->createElement($this->identify_by, 'identify_by');
        $this->createElement($this->username, 'username');
        $this->createElement($this->external_id, 'external_id');
        $this->createElement($this->localid, 'localid');
        $this->createElement($this->email, 'email');
        $this->createElement($this->password, 'password');
        $this->createElement($this->active, 'active');
        $this->createElement($this->vname, 'vname');
        $this->createElement($this->name, 'name');
        $this->createElement($this->phone, 'phone');
        $this->createElement($this->handy, 'handy');
        $this->createElement($this->fax, 'fax');
        $this->createElement($this->city, 'city');
        $this->createElement($this->cityn, 'cityn');
        $this->createElement($this->street, 'street');
        $this->createElement($this->streetn, 'streetn');
        $this->createElement($this->salutation, 'salutation');
        $this->createElement($this->birthday, 'birthday');
        $this->createElement($this->country, 'country');
        $this->createElement($this->fdlpath, 'fdlpath');
        $this->createElement($this->mail_backup, 'mail_backup');
        $this->createElement($this->mail_gsupdate, 'mail_gsupdate');
        $this->createElement($this->mail_securitybreach, 'mail_securitybreach');
        $this->createElement($this->mail_serverdown, 'mail_serverdown');
        $this->createElement($this->mail_ticket, 'mail_ticket');
        $this->createElement($this->mail_vserver, 'mail_vserver');
        $this->xml->appendChild($this->xmlkey);
        $this->xml->formatOutput = true;
        return $this->xml->saveXML();
    }
} 
