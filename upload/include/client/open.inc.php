<?php
if(!defined('OSTCLIENTINC')) die('Access Denied!');
$info=array();
if($thisclient && $thisclient->isValid()) {
    $info=array('name'=>$thisclient->getName(),
                'email'=>$thisclient->getEmail(),
                'phone'=>$thisclient->getPhoneNumber());
}

$info=($_POST && $errors)?Format::htmlchars($_POST):$info;

$form = null;
if (!$info['topicId']) {
    if (array_key_exists('topicId',$_GET) && preg_match('/^\d+$/',$_GET['topicId']) && Topic::lookup($_GET['topicId']))
        $info['topicId'] = intval($_GET['topicId']);
    else
        $info['topicId'] = $cfg->getDefaultTopicId();
}

$forms = array();
if ($info['topicId'] && ($topic=Topic::lookup($info['topicId']))) {
    foreach ($topic->getForms() as $F) {
        if (!$F->hasAnyVisibleFields())
            continue;
        if ($_POST) {
            $F = $F->instanciate();
            $F->isValidForClient();
        }
        $forms[] = $F;
    }
}

?>
<h1><?php echo __('Open a New Ticket');?></h1><br><br>
<h3 style="text-align: center;"><?php echo __('Conozca todos nuestros servicios y despúes de click en "SOLICITAR": <a href="https://rita.udistrital.edu.co/consultar-servicios/" target="_blank">Conocer Servicios</a>.');?></h3>
<p><?php echo __('Please fill in the form below to open a new ticket.');?></p>
<form id="ticketForm" method="post" action="open.php" enctype="multipart/form-data">
  <?php csrf_token(); ?>
  <input type="hidden" name="a" value="open">
  <table width="800" cellpadding="1" cellspacing="0" border="0">
    <!-- Formulario temas de ayuda -->
  <tbody>
    <tr><td colspan="2"><hr />
        <div class="form-header" style="margin-bottom:0.5em">
        <h3><?php echo __('Help Topic'); ?></h3> 
        <b><?php echo __('Servicios'); ?></b> <font class="error">*&nbsp;<small>(OBLIGATORIO)</small>&nbsp;<?php echo $errors['topicId']; ?></font>
        </div>
    </td></tr>
    <tr>
        <td colspan="2">
            <select id="topicId" name="topicId" onchange="javascript:
                    var data = $(':input[name]', '#dynamic-form').serialize();
                    $.ajax(
                      'ajax.php/form/help-topic/' + this.value,
                      {
                        data: data,
                        dataType: 'json',
                        success: function(json) {
                          $('#dynamic-form').empty().append(json.html);
                          $(document.head).append(json.media);
                          elemento = 'Gestión de cambios';
                          $('option:contains('+elemento+')').remove();
                        }
                      });">
                <option value="" selected="selected">&mdash; <?php echo __('Select a Help Topic');?> &mdash;</option>
                <?php
                if($topics=Topic::getPublicHelpTopics()) {
                    foreach($topics as $id =>$name) {
                        echo sprintf('<option value="%d" %s>%s</option>',
                                $id, ($info['topicId']==$id)?'selected="selected"':'', $name);
                    }
                } else { ?>
                    <option value="0" ><?php echo __('General Inquiry');?></option>
                <?php
                } ?>
            </select>
            <br><br> 
            ¿No sabe cuál elegir y ya visito nuestra página de servicios? <a href="https://rita.udistrital.edu.co/consultar-servicios" target="_blank">Haga click aqui</a><br><br> 
            <em style="color:gray;display:inline-block">Recuerde elegir correctamente el tema de ayuda, ya que esto ayudara a nuestro equipo a responder su solicitud en el menor tiempo posible.<br><br></em>
        </td>
    </tr>
    </tbody>
    <tbody>
<?php

    //Formulario de usuarios, si esta loggeado no lo coloca
        if (!$thisclient) {
            $uform = UserForm::getUserForm()->getForm($_POST);
            if ($_POST) $uform->isValid();
            $uform->render(false);
        }
        else { ?>
            <tr><td colspan="2"><hr /></td></tr>
        <tr><td><?php echo __('Email'); ?>:</td><td><?php
            echo $thisclient->getEmail(); ?></td></tr>
        <tr><td><?php echo __('Client'); ?>:</td><td><?php
            echo Format::htmlchars($thisclient->getName()); ?></td></tr>
        <?php } ?>
    </tbody>
    <?php 
    try {
        ?>
        <tbody id="dynamic-form">
        <?php foreach ($forms as $form) {
            include(CLIENTINC_DIR . 'templates/dynamic-form.tmpl.php');
        } ?>
        </tbody><?php 
    } catch (\Throwable $th) {
        ?><script>$("#topicId").val("");</script><?php 
    }
    ?>
    
    <tbody>
    <?php
    if($cfg && $cfg->isCaptchaEnabled() && (!$thisclient || !$thisclient->isValid())) {
        if($_POST && $errors && !$errors['captcha'])
            $errors['captcha']=__('Please re-enter the text again');
        ?>
    <tr class="captchaRow">
        <td class="required"><?php echo __('CAPTCHA Text');?>:</td>
        <td>
            <span class="captcha"><img src="captcha.php" border="0" align="left"></span>
            &nbsp;&nbsp;
            <input id="captcha" type="text" name="captcha" size="6" autocomplete="off">
            <em><?php echo __('Enter the text shown on the image.');?></em>
            <font class="error">*&nbsp;<?php echo $errors['captcha']; ?></font>
        </td>
    </tr>
    <?php
    } ?>
    <tr><td colspan=2>&nbsp;</td></tr>
    </tbody>
  </table>
  <div style="text-align: justify;font-size:13px;">
      <p style="display: -webkit-inline-box; text-align: justify; padding-right: 50px; padding-left: 50px;">
       Al crear el ticket entendemos que ha leído y esta de acuerdo con los términos y condiciones de uso y tratamiento de datos, implementados por la Red de Investigaciones de Tecnología Avanzada de la Universidad Distrital Francisco José de Caldas. <a href="http://sgral.udistrital.edu.co/xdata/rec/res_2013-727.pdf" target="_blank">Ver términos y condiciones.</a>
        </p>
<p style="display: -webkit-inline-box; text-align: justify; padding-right: 50px; padding-left: 50px;">
La información suministrada en este formulario está protegida por la Ley de Habeas Data. La Red de Investigaciones de Tecnología Avanzada, garantiza la confidencialidad de los datos personales facilitados por los usuarios y su tratamiento de acuerdo con la legislación sobre protección de datos de carácter personal <a href="http://www.secretariasenado.gov.co/senado/basedoc/ley_1581_2012.html" target="_blank">(Ley 1581 de 2012)</a> y la <a href="http://sgral.udistrital.edu.co/xdata/rec/res_2013-727.pdf" target="_blank">Resolución 727 de 2013</a> de la Universidad Distrital Francisco José de Caldas; siendo de uso exclusivo de la Universidad y trasladados a terceros con autorización previa del usuario.
</p>
  <p>
      <b>Mayor Información: </b>
  <br>
Red de Investigaciones de Tecnología Avanzada<br>
Carrera 8 No. 40 -62. Bogotá, D.C. Colombia<br>
Horario de atención: Lunes a Viernes de 8:00 a.m. a 5:00 p.m. <br>
Teléfono: (+57 1) 3239300 ext 1310 - 1374</p>
      </div>
<hr/>
  <p class="buttons" style="text-align:center;">
        <input type="submit" value="<?php echo __('Create Ticket');?>">
        <input type="reset" name="reset" value="<?php echo __('Reset');?>">
        <input type="button" name="cancel" value="<?php echo __('Cancel'); ?>" onclick="javascript:
            $('.richtext').each(function() {
                var redactor = $(this).data('redactor');
                if (redactor && redactor.opts.draftDelete)
                    redactor.deleteDraft();
            });
            window.location.href='index.php';">
  </p>
</form>
<script>
var url = window.location.href; 
var n = url.indexOf("#");
$(document).ready(function(){
    if(n>0){        
        goTheme(decodeURIComponent(url.substring(n+1)));
    }
})
function goTheme(tema){
	console.log(tema);
        $('#topicId option:contains('+tema+')').prop('selected', true);
        $('#topicId').trigger("change");
    }
</script>
