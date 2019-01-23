<h2>Shakha Maps</h2>
<p>All <strong>active</strong> shakhas in US.</p>
<div id="map" style="width: 100%; height: 600px"></div>
<p>&nbsp</p>
<h2>Shakha Mapped to Counties</h2>
<p>All <strong>active</strong> shakhas mapped to counties in US | <span style="color: #662E9B;">HINDU POPULATION CENTER (> 200 pop)</span></p>

<div id="map_counties" style="width: 100%; height: 600px"></div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
   integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
   crossorigin=""/>

<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
   integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
   crossorigin=""></script>

<script type="text/javascript" src="<?=site_url();?>xmlrpc_client/geodata_json"></script>
<script type="text/javascript" src="/xmlrpc_client/shakha_geodata_places"></script>
<script type="text/javascript" src="<?=site_url();?>javascript/hindu_population_centers.js"></script>
<script type="text/javascript" src="<?=site_url();?>javascript/ecounties-out.js"></script>
<script type="text/javascript" src="<?=site_url();?>javascript/shakha_maps.js"></script>
<script type="text/javascript" src="<?=site_url();?>javascript/shakha_counties.js"></script>

