
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Behindbars.dk map</title>
    <style type="text/css">
			html, body {
				height:100%;
				width:100%;
				font-family: tahoma, verdana, sans-serif;
				overflow:hidden;
			}
			body {
				margin:0px;
			}
			div.bubbletext {
				font-size: 0.8em;
			}			

			.bubblelink {
				text-align: right;
			}
		</style>
	</style>
	<? include('googleapi_VIEW.php'); ?>	
  </head>
  <body onload="load()" onunload="GUnload()">
    <div id="map" style="width: 100%; height: 100%;"></div>
    <div id="message"></div>
  </body>
</html>
<?php
// behindbars.dk: ABQIAAAAylVIq-WVTfiXh0eVBVvVNBQXTdR1bvCnKGL8fNG-oAwz7VAAHxTxfQEdfiU-YwTdHC3v2kGQsVgxIA
// 0x002a.dk: ABQIAAAAylVIq-WVTfiXh0eVBVvVNBTj_jDnZZe-Zg8qsxtQB3pyvsqYDRSJOyinyI1INyLIw0CAbcNXCBQuhg
?>
