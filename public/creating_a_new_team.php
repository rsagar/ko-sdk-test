<?php
require_once('../vendor/autoload.php');
//This must be updated to the dir where your .env file is
$dotenv = new Dotenv\Dotenv(__DIR__.'/../');
$dotenv->load();

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
//Blue Frame client id is 13
$clientId = getenv('KROSSOVER_CLIENT_ID');

//I use environmental vars to avoid hardcoding sensible information in my code.
//This requires you to create a .env file (check the .env.example file I added to the code)
//Make sure dot env is able to find the file
$username = getenv('KROSSOVER_USERNAME');
$password = getenv('KROSSOVER_PASSWORD');

$auth = new Krossover\Authentication($isProductionEnvironment, $clientId);
$krossoverToken = $auth->getKOOauthToken($username, $password);

//Blue Frame user information
$userId = 536720;
//Team to which game has to be uploaded to
$uploadingForTeamId = 12576;

//Video Information (path relative to this document + name)
$fileName = 'sample.mp4';
$filePath = '../videos/';

//Information required to create the game
//There are a few assumptions we're making here
//1. You have the home and away team ids
//2. All games are of type scouting, unless the game is played by team we are uploading to
//3. All teams are properly set up on our database
$homeTeamId = 12576;
$type = Krossover\Models\Game::TYPE_SCOUTING;
$gender = Krossover\Models\Game::GENDER_MALE;
$sportId = Krossover\Models\Sport::BASKETBALL_SPORT_ID;
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
    $uploadingForTeamId,
    $userId,
    $sportId,
    $isProductionEnvironment,
    $krossoverToken,
    $clientId
);
//We set up the games
//$id, $score, $primaryJerseyColor, $secondaryJerseyColor
$game->setHomeTeam($homeTeamId, 0, '#FFFFFF', '#FF0000');

//If we don't know one of the team ids, we can create it
//This must be done only when the stats for the other team are not important and
//the team is only going to be used once
//If the team you know is the away team you can use setAwayTeam instead of setHomeTeam
$game->createOpponentTeam("New Team's Name" , 1, '#FF0000');

//We pass the guid of the video we just uploaded
$game->setVideo($videoGuid);
//save the game
$game->saveGame();
