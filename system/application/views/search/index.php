 <script>
  $(function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
        ui.jqXHR.error(function() {
          ui.panel.html(
            "Please try again" );
        });
      }
    });
  });
  </script> 
 <div id="result ">
 <h2>Search result for: <?php echo $term ?></h2>
 </div>
<div id="tabs">
  <ul>
    <li><a href="<?php echo site_url().'search/getSwayamsevak/'.$term ?>">Swayamsevak</a></li>
    <li><a href="<?php echo site_url().'search/getShakha/'.$term ?>">Shakha</a></li>    
  </ul>   
</div>

