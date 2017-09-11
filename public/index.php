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
$isProductionEnvironment = false;
//Krossover Token is the token you get after authenticating with our API
//with POST /oauth/token
$krossoverToken = 'f540dce4d276cac65001b5d3816f62b64409aba5';
//Hockey.TV client id is 12
$clientId = 12;
//Hockey TV user information
$userId = 366901;
$teamId = 918056;

//Video Information (path relative to this document + name)
$fileName = 'sample.mp4';
$filePath = '../videos/';

//Information required to create the game
//There are a few assumptions we're making here
//1. You have the home and away team ids
//2. All games are of type scouting
//3. All teams are properly set up on our database
$homeTeamId = 455642;
$awayTeamId = 12150;
$type = Krossover\Models\Game::TYPE_SCOUTING;
$gender = Krossover\Models\Game::GENDER_MALE;
$sportId = Krossover\Models\Sport::ICE_HOCKEY_SPORT_ID;
$datePlayed = new \DateTime();


//We upload the video
$uploader = new Krossover\Uploader($credentials, $isProductionEnvironment, $krossoverToken, $clientId);
$uploader->uploadFile($fileName, $filePath);
$videoGuid = $uploader->getGuid();

//We create the game
$game = new Krossover\Game(
    $datePlayed,
    $type,
    $gender,
    $teamId,
    $userId,
    $sportId,
    $isProductionEnvironment,
    $krossoverToken,
    $clientId
);
//We set up the games
//$id, $score, $primaryJerseyColor, $secondaryJerseyColor
$game->setHomeTeam($homeTeamId, 0, '#FFFFFF', '#FF0000');
$game->setAwayTeam($awayTeamId, 0, '#000000');
//We pass the guid of the video we just uploaded
$game->setVideo($videoGuid);
//And submit for breakdown
$game->saveGameAndSubmitForBreakdown();
