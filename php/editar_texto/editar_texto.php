<!--
Sitio Web Creado por ITred Spa.
Direccion: Guido Reni #4190
Pedro Agui Cerda - Santiago - Chile
contacto@itred.cl o itred.spa@gmail.com
https://www.itred.cl
Creado, Programado y Diseñado por ITred Spa.
BPPJ
-->

<!-- ---------------------------------------------------------------------------------------------------------------------
    ------------------- INICIO ITred Spa editar_texto.php --------------------
   --------------------------------------------------------------------------------------------------------------------- -->
   <?php
        //CONEXION A BASE DE DATOS ITred Spa
        $mysqli = new MYSQLI('localhost','root','','itredspa_bd');
    ?>
<!-- ---------------------
-- FIN CONEXION BD --
--------------------- --> 

<?php
    // Cargar los archivos CSS y JS específicos para esta página
    $sweet_alert = "js/sweetAlert.js";
    $css_file = "../../css/editar_texto/editar_texto.css";
    $js_file = "../../js/editar_texto/editar_texto.js";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Se define el titulo de la pagina dinamicamente -->
    <title><?php $page_title ?></title>
    <!-- Se agregan dinamicamente las hojas de estilos -->
    <link rel="stylesheet" href="<?php echo $css_file; ?>">
    <!-- Se agregan dinamicamente los scripts de js -->
    <script src="<?php echo $js_file; ?>"></script>
    
</head>

   <div id="estilos" class="estilos">
                <div class="estilos__titulo"></div>
                    
                <div class="estilos__contenedor1">
                    <div class="contenedor__fuentes">
                        <div class="fuentes__titulo">Estilo Fuentes</div>
                        <div class="fuentes__select">
                            <label for="">Fuentes</label>
                            <select id="selectFuentes">
                                <option selected>Seleccionar</option>
                            </select>
                        </div>

                        <div class="cargarFuenteForm">
                            <form id="cargarFuenteForm">
                                <label for="archivoFuente">Agregar nueva fuente</label>
                                <input type="file" id="archivoFuente" action="php/cargar_fuente.php" name="fileUpload[]" accept=".ttf,.otf" />
                                <button type="submit">Agregar Fuente</button>
                            </form>
                        </div>

                        <div class="contenedor__tamanio">
                            <div class="tamanio__titulo">Tamaño de Letra</div>
                            <div class="tamanio__select">
                                
                            <select id="selectTamanio">
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12" selected>12</option> <!-- Selección predeterminada -->
                                <option value="14">14</option>
                                <option value="16">16</option>
                                <option value="18">18</option>
                                <option value="20">20</option>
                                <option value="22">22</option>
                                <option value="24">24</option>
                                <option value="26">26</option>
                                <option value="28">28</option>
                                <option value="36">36</option>
                                <option value="48">48</option>
                                <option value="72">72</option>
                            </select>

                            

                            </div>
                        </div>
                        <div class="container-btn-modificar_texto">
                            <div class="btn__style_texto"  onclick="mostrarMenuCambiarColorTexto()">Cambiar color texto</div>
                            <div class="btn__style_texto" onclick="abrirMenuTexto()">Posicion texto</div> 
                            <div class="btn__style_texto"  onclick="abrirMenuVinetas()">Viñeta</div>
                            <div class="btn__style_texto"onclick="abrirMenuAnimacion()" >Animacion </div>
                            <div class="btn__style_texto" onclick="restablecerCambios()">Restablecer cambios</div>  
                            
                        </div>


                        <div id="MenuVinetas" class="MenuSeleccionado" style="display: none;">
                            <div class="seleccionado">
                                <div class="seleccionado__container-title">
                                    <div class="seleccionado__titulo">Seleccionar Viñetas</div>
                                    <div id="MenuVinetas__cerrar" class="seleccionado__cerrar" onclick="cerrarMenuVinetas()">X</div>
                                </div>
                                <div id="MenuVinetas__contenido" class="seleccionado__contenido">
                                    <div class="seleccionado__contenedor"></div>
                                </div>
                            </div>
                        </div>







                        <div id="MenuAnimacion" class="MenuSeleccionado" style="display: none;">
                            <div class="seleccionado">
                                <div class="seleccionado__container-title">
                                    <div class="seleccionado__titulo">Seleccionar Animación</div>
                                    <div id="MenuAnimacion__cerrar" class="seleccionado__cerrar" onclick="cerrarMenuAnimacion()">X</div>
                                </div>
                                <div id="MenuAnimacion__contenido" class="seleccionado__contenido">
                                    <div class="seleccionado__contenedor"></div>
                                </div>
                            </div>
                        </div>



                        <div id="MenuColorTexto" class="MenuSeleccionado">
                            <div class="seleccionado">
                                <div class="seleccionado_color__container-title">
                                    <div class="seleccionado_color_titulo">Titulo</div>
                                    <div id="seleccionado_color__cerrar" class="seleccionado__cerrar">X</div> 
                                </div>
                                <div id="seleccionado_color__contenido" class="seleccionado__contenido">
                                    <div class="seleccionado_color__contenedor"></div>
                                </div>
                            </div>
                        </div>

                        <div id="MenuTexto" class="MenuTexto">
                            <div class="seleccionado">
                                <div class="seleccionado__container-title">
                                    <div class="seleccionado_titulo">Posición del Texto</div>
                                    <div id="seleccionado_texto__cerrar" class="seleccionado__cerrar" onclick="cerrarMenuTexto()">X</div> 
                                </div>
                                <div id="seleccionado_texto__contenido" class="seleccionado__contenido">
                                    <div class="seleccionado__contenedor">
                                    <label for="selectBotonesTexto">Seleccionar botón:</label>
                                    <select id="selectBotonesTexto" onchange="actualizarBotonSeleccionado()">
                                        <option value="">Seleccione un botón</option>
                                            <!-- Opciones de botones se añadirán dinámicamente con JavaScript -->
                                    </select>
                                    
                                    <div id="alineacionTextoContainer" style="display: none;">
                                        <label for="alineacionTexto">Alineación del texto:</label>
                                        <select id="alineacionTexto" onchange="cambiarAlineacionTexto()">
                                            <option value="left">Izquierda</option>
                                            <option value="center">Centro</option>
                                            <option value="right">Derecha</option>
                                            <option value="justify">Justificado</option>
                                        </select>
                                        <br><br>
                                        <label for="alineacionVertical">Alineación vertical:</label>
                                        <select id="alineacionVertical" onchange="cambiarAlineacionVertical()">
                                            <option value="flex-start">Arriba</option>
                                            <option value="center">Centro</option>
                                            <option value="flex-end">Abajo</option>
                                        </select>
                                        <br><br>
                                        <button onclick="aplicarAlineacionATodos()">Aplicar a todos</button>
                                        <button onclick="deshacerCambiosEnTodos()">Deshacer cambios</button>






                                    </div>





                                    </div>
                                </div>
                            </div>
                        </div>


                    
                    </div>
                    

                    

                    <div class="contenedor__letras">
                        <div for="" class="letras__titulo">Estilos Letras</div>
                        <div class="letras__inputs">
                            <div class="inputs__xd">
                                <label for="" class="input__estilo">Negrita:</label>
                                <input type="checkbox" id=checkboxNegrita  onchange="toggleNegrita(this)">                                 
                            </div>
                                
                            <div class="inputs__xd">
                                <label for="" class="input__estilo">Cursiva:</label>
                                <input type="checkbox" id="checkboxCursiva" onchange="toggleCursiva(this)">
                            </div>
                                
                            <div class="inputs__xd">
                                <label for="" class="input__estilo">Subrayar:</label>
                                <input type="checkbox" id="checkboxSubrayar" onchange="toggleSubrayado(this)">                            
                            </div>
                            
                        </div>
                       
                    </div>
                    <button id="cerrarMenu">Cerrar</button>    
                </div>
               
    </div> 


<!-- -------------------------------------------------------------------------------------------------------------
   -------------------------------------- FIN ITred Spa editar_texto.php ------------------------------------------------
   --------------------------------------------------------------------------------------------------------------->

<!--
Sitio Web Creado por ITred Spa.
Direccion: Guido Reni #4190
Pedro Agui Cerda - Santiago - Chile
contacto@itred.cl o itred.spa@gmail.com
https://www.itred.cl
Creado, Programado y Diseñado por ITred Spa.
BPPJ
-->