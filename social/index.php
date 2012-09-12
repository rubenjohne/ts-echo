<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Fan Only | Special Offers</title>
	<link type="text/css" rel="stylesheet" href="css/styles.css"/>
</head>

<body>
<?php require 'src/facebook.php'; 



$app_id = "387233371314564";
$app_secret = "f0052c8c495063aefea0023220817612";

$facebook = new Facebook(array('appId' => $app_id, 'secret' => $app_secret, 'cookie' => true));

$signed_request = $facebook->getSignedRequest();

$page_id = $signed_request["page"]["id"];
$page_admin = $signed_request["page"]["admin"];
$like_status = $signed_request["page"]["liked"];
$country = $signed_request["user"]["country"];
$locale = $signed_request["user"]["locale"];



if ($like_status) { ?>
	
    <img src="images/LandingPage_NoPromo.jpg" alt="Promo"/>
    <!---
    <div id="likedbox">
		<span id="promodates">FRIDAY, JUNE 22<sup>ST</sup> - FRIDAY, JULY 6<sup>TH</sup></span>  
        <span id="promocode">ENTER CODE <span class="code">FB0622</span> AT CHECKOUT</span>  
        <span id="promodiscount">TO RECEIVE A 40% DISCOUNT* ON ALL STYLES</span>
        <span id="promocategory">WITHIN THE <a href="http://shop.tadashishoji.com/special-offers.html" class="code" target="_blank">FANS ONLY</a> SECTION ON</span>
        
        <a id="shopLink" href="http://shop.tadashishoji.com" target="_blank">SHOP.TADASHISHOJI.COM</a>
    </div>
    -->

<?php }


else {  ?>
 
	<img src="images/LandingPage.jpg"/>

<?php }




?>

</body>
</html>