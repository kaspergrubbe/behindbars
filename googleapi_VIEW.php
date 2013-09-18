<?
require_once('classes/MysqlConnector.class.php');
?>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAylVIq-WVTfiXh0eVBVvVNBQXTdR1bvCnKGL8fNG-oAwz7VAAHxTxfQEdfiU-YwTdHC3v2kGQsVgxIA" type="text/javascript"></script>
    <script type="text/javascript">
    	
		//<![CDATA[
    	function load() {
    		if (GBrowserIsCompatible()) {
    			var map = new GMap2(document.getElementById("map"));
    			map.addControl(new GLargeMapControl());
    			map.addControl(new GMapTypeControl());
    			map.setCenter(new GLatLng(55.683585, 12.570076), 13);

    			// ==================================================
    			// A function to create a tabbed marker and set up the event window
    			// This version accepts a variable number of tabs, passed in the arrays htmls[] and labels[]
    			function createTabbedMarker(point,htmls,labels) {
    				var marker = new GMarker(point);
    				GEvent.addListener(marker, "click", function() {
    					// adjust the width so that the info window is large enough for this many tabs
    					if (htmls.length > 2) {
    						htmls[0] = '<div style="width:'+htmls.length*88+'px">' + htmls[0] + '</div>';
    					}
    					var tabs = [];
    					for (var i=0; i<htmls.length; i++) {
    						tabs.push(new GInfoWindowTab(labels[i],htmls[i]));
    					}
    					marker.openInfoWindowTabsHtml(tabs);
    				});
    				return marker;
    			}
    			// ==================================================
	<?php
			$db = new MysqlConnector();
			$db->execute_query("SELECT id,navn,coordinates FROM bmx_spot_guide WHERE coordinates != ''");
			while($row = $db->fetchrow(3))
			{
			$cords = explode(";",$row['coordinates']);
			echo"
    			var point = new GLatLng($cords[1], $cords[0]);
			var marker = createTabbedMarker(point, [\"<div class=bubbletext><b>$row[navn]</b><br/><div class=bubblelink><a href=index.php?site=spot&id=$row[id]>Se mere p&aring; BehindBars</a></div></div>\"],[\"Information\"]);
    			map.addOverlay(marker);
			";
			}
	?>
    		}
    	}
  //]]>
    </script>
<?php
// behindbars.dk: ABQIAAAAylVIq-WVTfiXh0eVBVvVNBQXTdR1bvCnKGL8fNG-oAwz7VAAHxTxfQEdfiU-YwTdHC3v2kGQsVgxIA
// 0x002a.dk: ABQIAAAAylVIq-WVTfiXh0eVBVvVNBTj_jDnZZe-Zg8qsxtQB3pyvsqYDRSJOyinyI1INyLIw0CAbcNXCBQuhg
?>
