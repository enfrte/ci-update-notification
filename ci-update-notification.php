<?php

/** 
 * Check CodeIgniter release for updates - Run as a cron job
 */

$local_version_file = 'ci_version.txt'; 
if(!file_exists($local_version_file)) { file_put_contents($local_version_file, ''); } // create for first run

$update_feed_address = 'https://github.com/bcit-ci/CodeIgniter/releases.atom';
$xmlstr = file_get_contents($update_feed_address);
$feed = new SimpleXMLElement($xmlstr);
$latest_version =  $feed->entry[0]->id;

$file_contents = file_get_contents($local_version_file); 
if(empty($file_contents)) { file_put_contents($local_version_file, $latest_version); } // empty on first run 

if(strcmp($latest_version, $file_contents) !== 0) { 
    // Release versions not matched - therefore new version available. Send notification 
    $to = "your.mail@gmail.com";
    $subject = "CodeIgniter Update Available";
    $txt = "There is a CodeIgniter update available. Version: " . substr($latest_version, strrpos($latest_version, '/') + 1);
    $headers = "From: admin@yourdomain.com" . "\r\n"; // . "CC: somebodyelse@example.com";

    if(!mail($to,$subject,$txt,$headers)) { echo "Failed to send mail."; } 
    
    file_put_contents($local_version_file, $latest_version); // add the latest version back to the local datastore 
}

