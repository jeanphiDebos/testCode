<script type="text/javascript" src="./jquery-1.11.1.min.js"></script>
<select name="select" id="CheminFichierSon">
<?php 
$handle = @opendir("./son/"); 
if ($handle === false) echo "probleme sur l'ouverture du dossier './son/'"; 
while(false !== ($element = readdir($handle))) {
	if ($element != "." && $element != "..") {
		if (!is_dir($element)) {
?>
	<option value="<?php echo "./son/".$element?>"><?php echo $element?></option>
<?php 
		}
	}
}
closedir($handle);
?>	
</select>

<button id="playSon">lancer son</button>
<script>
$(document).ready(function(){
	$("#playSon").click(
	function() {
		jQuery.ajax({
			type: "GET",
			url: "./requeteAJAX.php",
			data :{
				action : "jouerSon",
				cheminSon : $("#CheminFichierSon").val()
			},
			success: function(data, textStatus, jqXHR) {
				if (data == "") console.info("succes");
				else console.error("erreur : "+data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.error("erreur");
			}
		});
	});
});
</script>