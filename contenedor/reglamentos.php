<?php
    include "../includes/_event_log.php";
    include "../includes/user_session.php";
    $user = new UserSession();
    $e = new evento();
    $e->e_log_inicio_evento($user->getCurrentUser(), 10);
    // $e->e_log_inicio_evento($user->getCurrentUser(), 10);
    $_reglamentoestudiantil="http://uecologica.edu.bo/pdf/REGLAMENTOESTUDIANTILUNE.pdf";
    $_reglamentodocente="http://uecologica.edu.bo/pdf/reglamento_docente.pdf";
    $_reglamentobecas="http://uecologica.edu.bo/pdf/BECAS.pdf";
?>
<div uk-grid>
        <div class="uk-width-6-7@m">
            <div class="blog-post single-post">
                <div class="blog-post-content">
                    <div align="center">
                        <h3>REGLAMENTOS UNE</h3>
                    </div>
                    <br>
                    <div class="container">
                      <div class="row">
                        <div class="icon-set-container">                      
                            <div class="glyph fs1">
                                <div class="clearfix bshadow0 pbs">
                                  <a target="_blank\" href='<?=$_reglamentoestudiantil?>' onclick="registrar_evento_recurso('<?=$_reglamentoestudiantil?>', 11)" >
                                    <span class="icon-material-outline-picture-as-pdf">
                                    </span>
                                    <span class="mls"> REGLAMENTO ESTUDIANTIL</span>
                                    </a>
                                </div>
                            </div>
                        
                            <div class="glyph fs1">
                                <div class="clearfix bshadow0 pbs">
                                  <a target="_blank\" target="_blank\" href='<?=$_reglamentodocente?>'  onclick="registrar_evento_recurso('<?=$_reglamentodocente?>', 12)" >
                                    <span class="icon-material-outline-picture-as-pdf">
                                    </span>
                                    <span class="mls"> REGLAMENTO DOCENTE</span>
                                    </a>
                                </div>
                            </div>
                                           
                            <div class="glyph fs1">
                                <div class="clearfix bshadow0 pbs">
                                    <a target="_blank\" href='<?=$_reglamentobecas?>' onclick="registrar_evento_recurso('<?=$_reglamentobecas?>', 13)" >
                                        <span class="icon-material-outline-picture-as-pdf">
                                        </span>
                                        <span class="mls"> REGLAMENTO BECAS</span>
                                    </a>
                                </div>
                            </div>                        
                        </div>
                      </div>
                    </div> 
                </div>
            </div>
        </div>
</div>
<div id="mod_test"></div>