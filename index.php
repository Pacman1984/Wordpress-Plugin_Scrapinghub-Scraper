<?php

/**
 * Plugin Name: Scrapinghub Last Job Downloader
 * Description: Downloads the last Job of Scrapionghub Spiders
 * Version: 1.0
 * Author: Sebastian Pachl
 * License: ---
 */
if (! defined('ABSPATH')) exit;
add_action( 'sp_wp_scrapinghub_xml_download', 'sp_scrapinghub_xml_download' );

function sp_scrapinghub_xml_download() {
    $apikey = 'YOUR-API-Key';
    $project = 'YOUR-Project-ID';

    $spiders = array(
        'SpiderName_001',
        'SpiderName_002',
        'SpiderName_etc',
    );
    foreach ($spiders as $spider_name) {

        $url = 'https://dash.scrapinghub.com/api/jobs/list.json?apikey=' .$apikey .'&project=' .$project .'&state=finished&spider=' .$spider_name; 
        $jsondata = wp_remote_retrieve_body(wp_remote_get($url));
        $json = json_decode($jsondata, true);
        $lastid = $json['jobs'][0]['id'];

        $request_url= "https://storage.scrapinghub.com/items/".$lastid ."?apikey=" .$apikey ."&format=xml";

        $destination = plugin_dir_path( __FILE__ )."/uploads/" .$spider_name .".xml";
        file_put_contents( $destination ,wp_remote_retrieve_body(wp_remote_get($request_url)));

    }
}
?>