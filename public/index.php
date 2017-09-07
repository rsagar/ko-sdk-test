<?php
require_once('../vendor/autoload.php');

/**
 * You need to change this to the appropiate values
 */
//You can set up your profile as explained here:
//https://docs.aws.amazon.com/cli/latest/userguide/cli-chap-getting-started.html
//Otherwise you can pass directly your credentials (but this is discouraged
//because the credentials shouldn't be hardcoded in your code).
//For more information about Amazon credentials see:
//https://docs.aws.amazon.com/aws-sdk-php/v3/guide/guide/configuration.html#credentials
$credentials = [
    'profile' => 'default',
];
//All testing should be done outside of production environment
$productionEnvironment = false;
//Krossover Token is the token you get after authenticating with our API
//with POST /oauth/token
$krossoverToken = '9ca0af00073bae677ae491e4c09f4c1bc3ed6df3';
//Hockey.TV client id is 12
$clientId = 12;
//Hockey TV user information
$userId = 366901;
$teamId = 918056;

//Video Information (path relative to this document + name)
$fileName = 'sample.mp4';
$filePath = '../videos/';


//We upload the video
$uploader = new Krossover\Uploader($credentials, $productionEnvironment, $krossoverToken, $clientId);
$uploader->uploadFile($fileName, $filePath);
$videoGuid = $uploader->getGuid();
