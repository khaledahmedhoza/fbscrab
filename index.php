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
//$loginUrl = $helper->getLoginUrl('https://fbscrab.herokuapp.com/fb-callback.php', $permissions);

try {
	if (isset($_SESSION['localhost_app_token'])) {
		$accessToken = $_SESSION['localhost_app_token'];
	} else {
  		$accessToken = $helper->getAccessToken();
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
 	// When Graph returns an error
 	echo 'Graph returned an error: ' . $e->getMessage();
  	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
 	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
  	exit;
 }
if (isset($accessToken)) {
	if (isset($_SESSION['localhost_app_token'])) {
		$fb->setDefaultAccessToken($_SESSION['localhost_app_token']);
	} else {
		// getting short-lived access token
		$_SESSION['localhost_app_token'] = (string) $accessToken;
	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();
		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['localhost_app_token']);
		$_SESSION['localhost_app_token'] = (string) $longLivedAccessToken;
		// setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['localhost_app_token']);
	}
	// redirect the user back to the same page if it has "code" GET variable
	if (isset($_GET['code'])) {
		header('Location: ./');
	}
	// getting basic info about user
	try {
		$profile_request = $fb->get('/me?fields=name,first_name,last_name,birthday,website,location');
		$profile = $profile_request->getGraphNode()->asArray();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		session_destroy();
		// redirecting user back to app login page
		header("Location: ./");
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	
	// printing $profile array on the screen which holds the basic info about user
	echo $profile['birthday']->format('d-m-Y');
	echo $profile['website'];
	echo $profile['location']['name'];
  	// Now you can redirect to another page and use the access token from $_SESSION['localhost_app_token']
} else {
	// replace your website URL same as added in the developers.facebook.com/apps e.g. if you used http instead of https and you used non-www version or www version of your website then you must add the same here
	$loginUrl = $helper->getLoginUrl('http://fbscrab.herokuapp.com/', $permissions);
	echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
}

?>