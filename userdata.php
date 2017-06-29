<?php

session_start();
require_once 'vendor/facebook/graph-sdk/src/Facebook/autoload.php';

try {
  // Returns a `Facebook\FacebookResponse` object
    $response = $fb->get('/me?fields=id,name', $_SESSION['fb_access_token']);
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

$user = $response->getGraphUser();

echo 'Name: ' . $user['name'];
?>