<h2>Shakha Maps</h2>
<p>All <strong>active</strong> shakhas in US.
<div id="map" style="width: 100%; height: 600px"></div>
<p>&nbsp</p>
<h2>Shakha Mapped to Counties</h2>
<p>All <strong>active</strong> shakhas mapped to counties in US.
<div id="map_counties" style="width: 100%; height: 600px"></div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
   integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
   crossorigin=""/>

<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
   integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
   crossorigin=""></script>

<!-- <link rel="stylesheet" href="http://eric.clst.org/explore/maps/leaflet/dist/leaflet.css" /> -->
<!--[if lte IE 8]><link rel="stylesheet" href="http://eric.clst.org/explore/maps/leaflet/dist/leaflet.ie.css" /><![endif]-->

<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script> -->
<!-- <script src="http://eric.clst.org/explore/maps/leaflet/dist/leaflet.js"></script> -->
<!-- <script type="text/javascript" src="http://maps.stamen.com/js/tile.stamen.js"></script> -->
<!-- <script type="text/javascript" src="http://eric.clst.org/explore/maps/leaflet/leafletTextMarker.js"></script> -->
<!-- <link rel="stylesheet" href="counties.css" /> -->

<!-- <script type="text/javascript" src="https://sampark.lndo.site/xmlrpc_client/geodata_json"></script> -->
<script type="text/javascript" src="/xmlrpc_client/shakha_geodata_places"></script>
<!-- <script type="text/javascript" src="counties/ecounties-out.js"></script> -->
<!-- <script type="text/javascript" src="counties.js"></script> -->
<script type="text/javascript" src="<?=site_url();?>javascript/shakha_maps.js"></script>
<script type="text/javascript" src="<?=site_url();?>xmlrpc_client/geodata_json"></script>
<script type="text/javascript" src="<?=site_url();?>javascript/ecounties-out.js"></script>
<script type="text/javascript" src="<?=site_url();?>javascript/shakha_counties.js"></script>

