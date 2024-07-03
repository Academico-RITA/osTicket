        </div>
    </div>
    <div id="footer">
        <p><?php echo __('Copyright &copy;'); ?> <?php echo date('Y'); ?> <?php
        echo Format::htmlchars((string) $ost->company ?: 'osTicket.com'); ?> - <?php echo __('All rights reserved.'); ?></p>
        <a id="poweredBy" href="https://osticket.com" target="_blank"><?php echo __('Helpdesk software - powered by osTicket'); ?></a>
    </div>
<div id="overlay"></div>
<div id="loading">
    <h4><?php echo __('Please Wait!');?></h4>
    <p><?php echo __('Please wait... it will take a second!');?></p>
</div>
<?php
if (($lang = Internationalization::getCurrentLanguage()) && $lang != 'en_US') { ?>
    <script type="text/javascript" src="<?php echo ROOT_PATH; ?>ajax.php/i18n/<?php
        echo $lang; ?>/js"></script>
<?php } ?>
<script type="text/javascript">
    getConfig().resolve(<?php
        include INCLUDE_DIR . 'ajax.config.php';
        $api = new ConfigAjaxAPI();
        print $api->client(false);
    ?>);
</script>

<script>
$(document).ready(() => {
        let proyectos =$($('#ticketForm select')[3]).children();
        $($('#ticketForm select')[2])
.on('change', () => {
    $(document.querySelector('select[data-placeholder="Seleccionar Proyecto Curricular"]')).children().remove();
    let facultad = $(document.querySelector('select[data-placeholder="Seleccionar Facultad"]')).find(":selected").val();
    let rango = [];
    switch (facultad){
      case ("1"):
        // Facultad ASAB
        rango = [49,50,51,52,54,86,97];
        break;
      case ("2"):
        // Sede Bosa (Puede cambiar el nombre a facultad de ciencias y salud)
         rango = [];
        break;
      case ("3"):
        // Ciencias y educación
        rango = [44,45,71,72,73,74,75,76,77,79,81,82,83,84,85,92,94,87,91,55,57,70,83,85,66,70,61,63,64,121,97];
        break;
      case ("4"):
        // Externos a la universidad
        rango = [97];
        break;
      case ("5"):
        // Ingenieria
        rango = [29,32,33,35,40,56,59,60,68,69,21,22,24,25,26,27,78,89,90,95,97,122];
        break;
      case ("6"):
        // Medio ambiente
        rango = [28,39,42,43,58,62,67,80,93,100,102,104,53,46,47,48,123,97];
        break;
      case ("7"):
        // Otras dependencias
        rango = [97];
        break;
      case ("8"):
        // Facultad tecnologica
        rango = [30,31,34,36,37,41,99,103,105,106,65,23,97,105,124,103,125,88];
        break;
      case ("117"):
        // Ciencias matematicas y naturales
        rango = [118,119,96,120,97];
        break;
      default:
        rango = [21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106];
        break;
    }
    $(document.querySelector('select[data-placeholder="Seleccionar Proyecto Curricular"]')).append('<option>Seleccione un proyecto curricular</option>');

    for(let i = 0; i<proyectos.length; i++){
        rango.forEach(proyect => {
            if (parseInt(proyectos[i].value) == proyect) {
                $(document.querySelector('select[data-placeholder="Seleccionar Proyecto Curricular"]')).append("<option value="+proyectos[i].value+">"+proyectos[i].label+"</option>");
            }
        });
    }
        $($(document.querySelector('select[data-placeholder="Seleccionar Proyecto Curricular"]')).children()[0]).prop('selected', true);
        });
});
</script>

<!-- herramientas -->
<script>
  var urlParams = new URLSearchParams(window.location.search);
  var valorPorDefecto = urlParams.get('herramientas');
  var miSelect = document.querySelector('select[data-placeholder="Herramientas Especializadas"]');
  for (var i = 0; i < miSelect.options.length; i++) {
    if (miSelect.options[i].value === valorPorDefecto) {
      miSelect.options[i].selected = true;
      break;
    }
  } 
</script>
<!-- maquinas y laboratorios -->
<script>
  var urlParams = new URLSearchParams(window.location.search);
  var valorPorDefecto = urlParams.get('laboratorios');
  var miSelect = document.querySelector('select[data-placeholder="Servicio Solicitado"]');
  for (var i = 0; i < miSelect.options.length; i++) {
    if (miSelect.options[i].value === valorPorDefecto) {
      miSelect.options[i].selected = true;
      break;
    }
  }
</script>
<!-- video conferencia -->
<script>
  var urlParams = new URLSearchParams(window.location.search);
  var valorPorDefecto = urlParams.get('streaming');
  var miSelect = document.querySelector('select[data-placeholder="Tipo streaming"]');
  for (var i = 0; i < miSelect.options.length; i++) {
    if (miSelect.options[i].value === valorPorDefecto) {
      miSelect.options[i].selected = true;
      break;
    }
  }
</script>
<!-- Apoyo para la investigacion -->
<script>
  var urlParams = new URLSearchParams(window.location.search);
  var valorPorDefecto = urlParams.get('investigacion');
  var miSelect = document.querySelector('select[data-placeholder="Apoyo Solicitado"]');
  for (var i = 0; i < miSelect.options.length; i++) {
    if (miSelect.options[i].value === valorPorDefecto) {
      miSelect.options[i].selected = true;
      break;
    }
  }
</script>

</body>
</html>
