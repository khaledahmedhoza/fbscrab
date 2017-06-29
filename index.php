<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/facebook/graph-sdk/src/Facebook/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '1409354799119860',
  'app_secret' => '7b373ce78d8ad4b4efff8aac25b29ad7',
  'default_graph_version' => 'v2.9',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('https://fbscrab.herokuapp.com/fb-callback.php', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';

?>