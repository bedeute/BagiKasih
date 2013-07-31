<html>
<head>
<title>Welcome to CodeIgniter</title>

<style type="text/css">

body {
 background-color: #fff;
 margin: 40px;
 font-family: Lucida Grande, Verdana, Sans-serif;
 font-size: 14px;
 color: #4F5155;
}

a {
 color: #003399;
 background-color: transparent;
 font-weight: normal;
}

h1 {
 color: #444;
 background-color: transparent;
 border-bottom: 1px solid #D0D0D0;
 font-size: 16px;
 font-weight: bold;
 margin: 24px 0 2px 0;
 padding: 5px 0 6px 0;
}

code {
 font-family: Monaco, Verdana, Sans-serif;
 font-size: 12px;
 background-color: #f9f9f9;
 border: 1px solid #D0D0D0;
 color: #002166;
 display: block;
 margin: 14px 0 14px 0;
 padding: 12px 10px 12px 10px;
}
.floatleft {
	float:left;
	margin-right:10px;
}
</style>
</head>
<body>

<h1>Selamat datang di CodeIgniter!</h1>
<?php
if(isset($tolak)) echo "<p>".$tolak."</p>";
else {
	if(isset($fbuser)) { 
		echo '<h3><img src="'.$fbuser['avatar'].'" alt="" class="floatleft" />Halo '.$fbuser['nama'].'</h3>';
	}
	else { ?>
		<p><a href="https://graph.facebook.com/oauth/authorize?client_id=<?php echo $app_id ?>&redirect_uri=<?php echo site_url("/facebook_connect/fb") ?>&scope=email,user_birthday,user_location,friends_birthday">Klik disini untuk login ke facebook</a></p>
	<?php }
} ?>
</body>
</html>