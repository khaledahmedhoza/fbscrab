<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'vendor/facebook/graph-sdk/src/Facebook/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '1409354799119860',
  'app_secret' => '7b373ce78d8ad4b4efff8aac25b29ad7',
  'default_graph_version' => 'v2.9',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['user_about_me','public_profile','user_actions.books','user_actions.music','user_actions.fitness','user_actions.news','user_education_history','user_birthday','user_events','user_likes','user_posts','user_relationship_details','user_tagged_places','user_work_history']; // Optional permissions
//id,name,about,age_range,education,favorite_athletes,favorite_teams,gender,location,relationship_status,sports,religion,work,books{about,name,description,category},events{name,description,category},games{name,about,description,category},likes{name,about,description,category},movies{about,name,category,description},music{about,name,category,description},posts{description,story,message},tagged_places,television{about,description,category,name}
$loginUrl = $helper->getLoginUrl('https://fbscrab.herokuapp.com/fb-callback.php', $permissions);

echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';

?>