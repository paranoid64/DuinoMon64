<?php
/*
# Duino coin
#http://c64.xxx.de/c

#you need mod rewrite rules:

#Options +FollowSymLinks
RewriteEngine On

#RewriteCond %{SCRIPT_FILENAME} !-f
#RewriteCond %{SCRIPT_FILENAME} !-d

#Duino Coin
RewriteRule c duino_coin.php [L,QSA]
RewriteRule c/ duino_coin.php [L,QSA]
RewriteRule c/*$ duino_coin.php [L,QSA]
*/


setlocale(LC_TIME, "de_DE.utf8");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$com = chr(129); //Orange
$font = chr(30); //green
$head = chr(158);//yellow
$error = chr(28);//red

//DUINO API
$link = 'https://server.duinocoin.com';

$cachefolder = "/cache/duino";
$feedcache_max_age=300;

$balance = '/balances';
$statistics = '/statistics';

$url = explode("/",$_SERVER['REQUEST_URI']);

$help_page = $head."COMMANDS:".chr(13);
$help_page .= $com."HEL ".$font."(HELP)".chr(13);
$help_page .= $com."STA ".$font."(STATISTIC)".chr(13);
$help_page .= $com."PRI ".$font."(PRICE)".chr(13);
$help_page .= $com."SER ".$font."(SERVER)".chr(13);
$help_page .= $com."DEV ".$font."(DEVICE STATISTIC)".chr(13);
$help_page .= $com."TOP ".$font."(TOP10 USERS)".chr(13);
$help_page .= $com."BAL <WALLET USER> ".$font."(BALANCE USER)".chr(13);
$help_page .= $com."EXI".$font."(EXIT)".chr(13);
$help_page = strtolower($help_page );

if (isset($url[2])) {

    $cmd = explode(".", $url[2]);
    $subcmd = "";

    if(is_array($cmd) && count($cmd)==2){
        $command=$cmd[0];
        $subcmd=$cmd[1];
    } else {
        $command=$url[2];
    }

    $command = strtolower($command);

    switch ($command) {
      case '':
            echo $help_page;
        break;
      case 'exi':
            exit();
        break;
      case 'hel':
            echo $help_page;
        break;
      case 'top':
            /***************************************
            *              TOP                     *
            ****************************************/
            $feedcache_path = __DIR__.$cachefolder.'/'.$command.'.cache';
            if(!file_exists($feedcache_path) or filemtime($feedcache_path) < (time() - $feedcache_max_age)) {
                $json = file_get_contents($link.$statistics);
                $obj = json_decode($json);
                $page = "";
                foreach($obj->{'Top 10 richest miners'} as $key => $val) {
                    $page .= $head.'PLACE '.$key.chr(13).$font.$val.chr(13);
                }
                 file_put_contents($feedcache_path , strtolower($page));
            }
            echo file_get_contents($feedcache_path);

        break;
      case 'ser':
            /***************************************
            *            SERVER                     *
            ****************************************/
            $feedcache_path = __DIR__.$cachefolder.'/'.$command.'.cache';
            if(!file_exists($feedcache_path) or filemtime($feedcache_path) < (time() - $feedcache_max_age)) {
                $json = file_get_contents($link.$statistics);
                $obj = json_decode($json);
                $page = $head."Server CPU usage:".$font.$obj->{'Server CPU usage'}.chr(13);
                $page .= $head."Server RAM usage:".$font.$obj->{'Server RAM usage'}.chr(13);
                $page .= $head."Server version:".$font.$obj->{'Server version'}.chr(13);
                $page .= $head."Kolka Banned:".$font.$obj->Kolka->Banned.chr(13);
                $page .= $head."Kolka Jailed:".$font.$obj->Kolka->Jailed.chr(13);
                $page .= $head."active:".$font.$obj->Gratka->active.chr(13);
                $page .= $head."bought items:".$font.$obj->Gratka->bought_items.chr(13);
                $page .= $head."cached ips:".$font.$obj->Gratka->cached_ips.chr(13);
                $page .= $head."Net energy usage:".$font.$obj->{'Net energy usage'}.chr(13);
                $page .= $head."Open threads:".$font.$obj->{'Open threads'}.chr(13);
                file_put_contents($feedcache_path , strtolower($page));
            }
            echo file_get_contents($feedcache_path);
        break;
      case 'dev':
            /***************************************
            *            DEVICES                     *
            ****************************************/
            $feedcache_path = __DIR__.$cachefolder.'/'.$command.'.cache';
            if(!file_exists($feedcache_path) or filemtime($feedcache_path) < (time() - $feedcache_max_age)) {
                $json = file_get_contents($link.$statistics);
                $obj = json_decode($json);
                $page = $head."All:".$font.$obj->{'Miner distribution'}->All.chr(13);
                $page .= $head."Arduino:".$font.$obj->{'Miner distribution'}->Arduino.chr(13);
                $page .= $head."ESP32:".$font.$obj->{'Miner distribution'}->ESP32.chr(13);
                $page .= $head."ESP8266:".$font.$obj->{'Miner distribution'}->ESP8266.chr(13);
                $page .= $head."RPi:".$font.$obj->{'Miner distribution'}->RPi.chr(13);
                $page .= $head."Web:".$font.$obj->{'Miner distribution'}->Web.chr(13);
                $page .= $head."Phone:".$font.$obj->{'Miner distribution'}->Phone.chr(13);
                $page .= $head."Other:".$font.$obj->{'Miner distribution'}->Other.chr(13);
                $page .= $head."CPU:".$font.$obj->{'Miner distribution'}->CPU.chr(13);
                $page .= $head."GPU:".$font.$obj->{'Miner distribution'}->GPU.chr(13);
                file_put_contents($feedcache_path , strtolower($page));
            }
            echo file_get_contents($feedcache_path);
        break;
      case 'pri':
            /***************************************
            *            PRICE                     *
            ****************************************/
            $feedcache_path = __DIR__.$cachefolder.'/'.$command.'.cache';
            if(!file_exists($feedcache_path) or filemtime($feedcache_path) < (time() - $feedcache_max_age)) {
                $json = file_get_contents($link.$statistics);
                $obj = json_decode($json);
                $page = $head."Duco FluffySwap:".$font.number_format($obj->{'Duco FluffySwap price'}, 10, '.', ',').chr(13);
                $page .= $head."Duco Furim:".$font.number_format($obj->{'Duco Furim price'}, 10, '.', ',').chr(13);
                $page .= $head."Duco Node-S:".$font.number_format($obj->{'Duco Node-S price'}, 10, '.', ',').chr(13);
                $page .= $head."Duco PancakeSwap:".$font.number_format($obj->{'Duco PancakeSwap price'}, 10, '.', ',').chr(13);
                $page .= $head."Duco SunSwap:".$font.number_format($obj->{'Duco SunSwap price'}, 10, '.', ',').chr(13);
                $page .= $head."Duco SushiSwap:".$font.number_format($obj->{'Duco SushiSwap price'}, 10, '.', ',').chr(13);
                $page .= $head."Duco UbeSwap:".$font.number_format($obj->{'Duco UbeSwap price'}, 10, '.', ',').chr(13);
                $page .= $head."Duco:".$font.number_format($obj->{'Duco price'}, 10, '.', ',').chr(13);
                $page .= $head."Duco price BCH:".$font.number_format($obj->{'Duco price BCH'}, 10, '.', ',').chr(13);
                $page .= $head."Duco price NANO:".$font.number_format($obj->{'Duco price NANO'}, 10, '.', ',').chr(13);
                $page .= $head."Duco price TRX:".$font.number_format($obj->{'Duco price TRX'}, 10, '.', ',').chr(13);
                $page .= $head."Duco price XMG:".$font.number_format($obj->{'Duco price XMG'}, 10, '.', ',').chr(13);
                file_put_contents($feedcache_path , strtolower($page));
            }
            echo file_get_contents($feedcache_path);

        break;
      case 'sta':
            /***********************************************
            *             STATISTICD                       *
            ************************************************/
            $feedcache_path = __DIR__.$cachefolder.'/'.$command.'.cache';
            if(!file_exists($feedcache_path) or filemtime($feedcache_path) < (time() - $feedcache_max_age)) {
                $json = file_get_contents($link.$statistics);
                $obj = json_decode($json);
                $page = $head."Active connections:".$font.$obj->{'Active connections'}.chr(13);
                $page .= $head."All-time mined DUCO:".$font.$obj->{'All-time mined DUCO'}.chr(13);
                $page .= $head."Current difficulty:".$font.$obj->{'Current difficulty'}.chr(13);
                $page .= $head."DUCO-S1 hashrate:".$font.$obj->{'DUCO-S1 hashrate'}.chr(13);
                $page .= $head."Last block hash:".$font.chr(13).$obj->{'Last block hash'}.chr(13);
                $page .= $head."Last sync:".$font.$obj->{'Last sync'}.chr(13);
                $page .= $head."Last update:".$font.$obj->{'Last update'}.chr(13);
                $page .= $head."Mined blocks:".$font.$obj->{'Mined blocks'}.chr(13);
                $page .= $head."Net energy usage:".$font.$obj->{'Net energy usage'}.chr(13);
                $page .= $head."Open threads:".$font.$obj->{'Open threads'}.chr(13);
                $page .= $head."Pool hashrate:".$font.$obj->{'Pool hashrate'}.chr(13);
                $page .= $head."Registered users:".$font.$obj->{'Registered users'}.chr(13);
                $page .= $head."transaction_count".$font.$obj->{'transaction_count'}.chr(13);
                file_put_contents($feedcache_path , strtolower($page));
            }
            echo file_get_contents($feedcache_path);
        break;
      case 'bal':
            /***********************************************
            *             BALANCE USER                     *
            ************************************************/
            if(!empty($subcmd)){
                $feedcache_path = __DIR__.$cachefolder.'/user_'.md5($subcmd).'.cache';
                if(!file_exists($feedcache_path) or filemtime($feedcache_path) < (time() - $feedcache_max_age)) {
                    $json = file_get_contents($link.$balance."/".$subcmd);
                    $obj = json_decode($json);
                    if($obj->success==true){
                        $bal_page = $head."USER:".$font.$obj->result->username.chr(13);
                        $bal_page .= $head."DUCOS:".$font.$obj->result->balance.chr(13);
                        $bal_page .= $head."STAKE:".$font.$obj->result->stake_amount.chr(13);

                        if($obj->result->stake_amount){
                            $bal_page .= $head."STAKE DATE:".$font.date("Y/m/d",$obj->result->stake_date).chr(13);
                        }

                        if($obj->result->verified=="yes"){
                            $bal_page .= $head."VERIFIED BY:".$font.$obj->result->verified_by.chr(13);
                            $bal_page .= $head."VERIFIED:".$font.date("Y/m/d",$obj->result->verified_date).chr(13);
                        } else {
                            $bal_page .= $head."VERIFIED:".$error."NO".chr(13);
                        }
                        $bal_page .= $head."CREATED:".$font.$obj->result->created.chr(13);
                        $bal_page .= $head."LAST LOGIN:".$font.date("Y/m/d",$obj->result->last_login).chr(13);
                        $bal_page .= $head."WARNINGS:".$font.$obj->result->warnings.chr(13);
                     } else {
                        $bal_page .= $error."wallet user not found".chr(13);
                     }
                     file_put_contents($feedcache_path , strtolower($bal_page));
                }
                echo file_get_contents($feedcache_path);
            } else {
                echo $error.strtolower("Please specify user!").$font.chr(13)."e.g.: bal paranoid64";
            }
        break;
      default:
        echo strtolower($head.'Unknown command.'.$error.chr(13).'enter help or press return!').$font.chr(13);
    }
} else {
    echo $help_page;
}

?>
