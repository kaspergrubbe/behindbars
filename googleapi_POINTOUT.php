    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAylVIq-WVTfiXh0eVBVvVNBQXTdR1bvCnKGL8fNG-oAwz7VAAHxTxfQEdfiU-YwTdHC3v2kGQsVgxIA" type="text/javascript"></script>
    <script type="text/javascript">
    	//<![CDATA[
function load() {
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map"));
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.setCenter(new GLatLng(55.683585, 12.570076), 13);

		GEvent.addListener(map, "click", function(overlay, point) {
			if(overlay) {
				map.removeOverlay(overlay);
				document.getElementById("coordinates").value="";
			}
			if (point) {
				map.clearOverlays();
				map.panTo(point);     		
				var marker = new GMarker(point, {draggable: true});

				GEvent.addListener(marker, "dragend", function() {
					map.panTo(marker.getPoint());
					var newPoint = marker.getPoint();
					document.getElementById("coordinates").value=newPoint.x+";"+newPoint.y;
				});
	
				map.addOverlay(marker);
				document.getElementById("coordinates").value=point.x+";"+point.y;
			}
		});
	}
}
  //]]>
</script>
