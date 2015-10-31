<?php
require 'includes/openid.php';
$_STEAMAPI = "0126131D23E3E2BB24237A733E05595E";

?>
<html>
<head>
	<title>Head title</title>
	<link rel="stylesheet" type="text/css" href="reset.css">
	<link rel="stylesheet" type="text/css" href="test.css">
</head>
<body>
	<ul id="navbar">
		<li><a href="" id="curPage">Current page</a></li>
		<li><a href="" class="otherPage">Other page</a></li>
		<li><a href="" class="otherPage">Other page 2</a></li>
		<li><a href="" class="otherPage">Other page 3</a></li>
		<li><a href="" class="otherPage">Other page 4</a></li>
<?php
echo '<form action="?login" method="post"><input type="image" src="http://cdn.steamcommunity.com/public/images/signinthroughsteam/sits_small.png"></form>';
?>
	</ul>
	<div class="body">
		<div>
<?php
try 
{
    $openid = new LightOpenID('http://ipugs.tf/');
    if(!$openid->mode) 
    {
        if(isset($_GET['login'])) 
        {
            $openid->identity = 'http://steamcommunity.com/openid/?l=english';    // This is forcing english because it has a weird habit of selecting a random language otherwise
            header('Location: ' . $openid->authUrl());
        }

    } 
    elseif($openid->mode == 'cancel') 
    {
        echo 'User has canceled authentication!';
    } 
    else 
    {
        if($openid->validate()) 
        {
                $id = $openid->identity;
                // identity is something like: http://steamcommunity.com/openid/id/76561197960435530
                // we only care about the unique account ID at the end of the URL.
                $ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
                preg_match($ptn, $id, $matches);
                echo "User is logged in (steamID: $matches[1])\n";

                $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$_STEAMAPI&steamids=$matches[1]";
                $json_object= file_get_contents($url);
                $json_decoded = json_decode($json_object);

                foreach ($json_decoded->response->players as $player)
                {
                    echo "
                    <br/>Player ID: $player->steamid
                    <br/>Player Name: $player->personaname
                    <br/>Profile URL: $player->profileurl
                    <br/>SmallAvatar: <img src='$player->avatar'/> 
                    <br/>MediumAvatar: <img src='$player->avatarmedium'/> 
                    <br/>LargeAvatar: <img src='$player->avatarfull'/> 
                    ";
                }

        } 
        else 
        {
                echo "User is not logged in.\n";
        }
    }
} 
catch(ErrorException $e) 
{
    echo $e->getMessage();
}
?>
		</div>
		<div>
			<p>this is a really long paragraph to make sure paragraphs format correctjkldfjlksdajklffal;ksgkljsdfkjlgsdlkjfgjkldsfgksuhghus fdhg sih jg jsfg jgksdf hjgdksfg kjhsfdg hjkdsfjhkg hjksdfgeorg hagj domf emsji egmisergisdfg msmig segi esmioofbmsorgpmsm fm esmrbmiebs mmiesmmi sfmisemivimerig m</p>
		</div>
		<div>
			<h2>Header 2</h2>
			<h3>Header 3</h3>
			<h4>Header 4</h4>
			<h5>Header 5</h5>
		</div>
		<div>
			<ul>
				<li>Item 1</li>
				<li>Item 2</li>
			</ul>
			<ol>
				<li>Item 1</li>
				<li>Item 2</li>
			</ol>
			<ul>
				No li tags here<br>
				newline<br>
				newline2
			</ul>
			<ol>
				No li tags here<br>
				newline<br>
				newline2
			</ol>
		</div>
		<div>
			<a>I'm like 95% sure this is a link</a>
		</div>
	</div>
</body>
</html>