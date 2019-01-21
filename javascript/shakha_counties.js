// see http://leaflet.cloudmade.com
var map = new L.Map('map_counties');

map.createPane('labels');

// This pane is above markers but below popups
map.getPane('labels').style.zIndex = 650;

// Layers in this pane are non-interactive and do not obscure mouse/touch events
map.getPane('labels').style.pointerEvents = 'none';


var cartodbAttribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="http://cartodb.com/attributions">CartoDB</a>';

var positron = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}.png', {
		attribution: cartodbAttribution
	}).addTo(map);

var positronLabels = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}.png', {
	attribution: cartodbAttribution,
	pane: 'labels'
}).addTo(map);

map.setView(new L.LatLng(37, -95), 4);

//var cloudmadeUrl = 'http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', cloudmadeAttribution = 'Map data &copy; 2011 OpenStreetMap contributors, Imagery &copy; 2011 CloudMade', cloudmade = new L.TileLayer(cloudmadeUrl, {maxZoom: 18, attribution: cloudmadeAttribution});
//map.addLayer(cloudmade);

// see http://maps.stamen.com
// var stamenLayer = new L.StamenTileLayer("watercolor");
// map.addLayer(stamenLayer);

var geojsonMarkerOptions = {
    radius: 4,
    fillColor: "rgba(255,100,100,0.1)",
    color: "rgba(255,100,100,0.7)",
    weight: 1,
    opacity: 1,
    fillOpacity: 0.8
};

var shakha_fips = shakhas_geocoded.map(a => a.state_fips.padStart(2, '0') + a.county_fips.padStart(3,'0'));

var geojsonLayer = new L.GeoJSON(counties, {
    pointToLayer: function (latlng) {
		return new L.CircleMarker(latlng, geojsonMarkerOptions);
    },
	style: styleLayer,
	onEachFeature: onEachFeature,
	filter: filterFeatures
});

function filterFeatures(feature, layer) {
	return shakha_fips.filter(fips => fips === feature.properties.FIPS).length
}

function onEachFeature(feature, layer) {
	if (feature.properties && feature.properties.County){
		let shakha_counts = shakha_fips.filter(fips => fips === feature.properties.FIPS).length
		layer.bindPopup(feature.properties.County + ", " + feature.properties.State 
			+ "<br>FIPS: " + feature.properties.FIPS
			+ "<br>Shakha Counts: " + shakha_counts
		);
	}
    if (feature.properties && feature.properties.style && feature.layer.setStyle) {
        // layer.setStyle(feature.properties.style);
    }
};


// 5050+W+Andrea+Ln,+Phoenix,+AZ,+85083
function styleLayer(feature) {
	// return {
	// 	fillColor: "#d0d570",
	// 	fillOpacity: 0.5,
	// 	weight: 1,
	// 	strokeOpacity: 1.0
	// }
	// console.log(feature)
		// var executions = feature.properties.Executions;
		let shakha_counts = shakha_fips.filter(fips => fips === feature.properties.FIPS).length
		var exO = "0.7";
		var exColor = getExecutionColor(shakha_counts)
		
		// if (feature.properties.FIPS.startsWith("34")) {
		// 	exColor = "#f4a742";
		// }

		style = { 
	        weight: 2,
	        color: 'white',
	        opacity: 1,
	        fillColor: exColor,
			fillOpacity: exO,
			dashArray: '3',
	        // strokeOpacity: 0.5
		};

		if (exColor != '#ffffff') {
			// console.log(style);
		}
		return style;
		// geojsonLayer.addGeoJSON(counties[i]);
		// geojsonLayer.addData(counties[i]);
}

function getExecutionColor(d) {
    return d > 100 ? '#800026' :
           d > 50  ? '#BD0026' :
           d > 5  ? '#E31A1C' :
           d > 3  ? '#FC4E2A' :
           d > 2   ? '#FD8D3C' :
           d > 1   ? '#FEB24C' :
           d > 0   ? '#FED976' :
                      '#FFF';
}

function getColor(d) {
    return d > 1000 ? '#800026' :
           d > 500  ? '#BD0026' :
           d > 200  ? '#E31A1C' :
           d > 100  ? '#FC4E2A' :
           d > 50   ? '#FD8D3C' :
           d > 20   ? '#FEB24C' :
           d > 10   ? '#FED976' :
                      '#FFEDA0';
}

map.addLayer(geojsonLayer);

/*
var markerLocation = new L.LatLng(-84, 38),
	marker = new L.Marker(markerLocation);

map.addLayer(marker);
marker.bindPopup("<b>Hello world!</b><br />I am a popup.").openPopup();


var circleLocation = new L.LatLng(51.508, -0.11),
	circleOptions = {color: '#f03', opacity: 0.7},
	circle = new L.Circle(circleLocation, 500, circleOptions);

circle.bindPopup("I am a circle.");
map.addLayer(circle);


var p1 = new L.LatLng(51.509, -0.08),
	p2 = new L.LatLng(51.503, -0.06),
	p3 = new L.LatLng(51.51, -0.047),
	polygonPoints = [p1, p2, p3],
	polygon = new L.Polygon(polygonPoints);

polygon.bindPopup("I am a polygon.");
map.addLayer(polygon);

map.on('click', onMapClick);

var popup = new L.Popup();

function onMapClick(e) {
	var latlngStr = '(' + e.latlng.lat.toFixed(3) + ', ' + e.latlng.lng.toFixed(3) + ')';

	popup.setLatLng(e.latlng);
	popup.setContent("You clicked the map at " + latlngStr);
	map.openPopup(popup);
}
*/
