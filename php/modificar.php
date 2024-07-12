<!--
Sitio Web Creado por ITred Spa.
Direccion: Guido Reni #4190
Pedro Agui Cerda - Santiago - Chile
contacto@itred.cl o itred.spa@gmail.com
https://www.itred.cl
Creado, Programado y Diseñado por ITred Spa.
BPPJ
-->

<!-----------------------------------------------------------------------------------------------------------------------
   ------------------- INICIO ITred Spa crear.php -----------------------------------------------------------------------
   ----------------------------------------------------------------------------------------------------------------------->

<!-- ------------------------
	 -- INICIO CONEXION BD --
     ------------------------ -->
	
<!-- INICIO PHP BASE DE DATOS ITred Spa -->
<?php
        //CONEXION A BASE DE DATOS ITred Spa
        $mysqli = new MYSQLI('localhost','root','','itredspa_bd');
    ?>
<!-- ---------------------
-- FIN CONEXION BD --
--------------------- --> 

<?php
    // Cargar los archivos CSS y JS específicos para esta página
    $css_file = "css/modificar.css";
    $js_file = "js/modificar.js";
?>

<!DOCTYPE html>
<html lang="en">
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
<body>
    
    <div class="container">

        <div class="container__div1">
            <div class="formulario__select">
                <?php
                    // Tu código PHP para generar el JSON aquí
                    // Consulta SQL para obtener datos de la tabla usuarios
                            $sql = "SELECT 
                                    mc.ID AS menu_id,
                                    mc.titulo_menu AS titulo_menu,
                                    c.ID AS columna_id,
                                    c.nombre_columna AS nombre_columna,
                                    c.url_columna AS url_columna,
                                    c.type AS type_number_columna,
                                    sc.type AS type_number_subcolumna,
                                    c.ID AS columna_id,
                                    sc.ID AS subcolumna_id,
                                    sc.nombre_subcolumna AS nombre_subcolumna,
                                    sc.url_subcolumna AS url_subcolumna
                                    FROM menu_crear mc
                                    LEFT JOIN columnas c ON c.idMenu = mc.ID
                                    LEFT JOIN subcolumnas sc ON c.ID = sc.idColumna
                                    GROUP BY mc.ID, mc.titulo_menu, c.ID, c.nombre_columna, c.url_columna, sc.ID, sc.nombre_subcolumna, sc.url_subcolumna
                                    ORDER BY mc.ID, c.ID, sc.ID;
                                ";
                            $resultado = $mysqli->query($sql);

                            if ($resultado->num_rows > 0) {
                                // Array para almacenar los datos
                                $usuarios = array();

                                while ($fila = $resultado->fetch_assoc()) {
                                    // Agregar cada columna al menú correspondiente
                                    $menu_id = $fila['menu_id'];
                                    $columna_id = $fila['columna_id'];
                                    $subcolumna_id = $fila['subcolumna_id'];
                                    // Verificar si el menú ya existe en la lista de menús
                                    if (!isset($menus[$menu_id])) {
                                        $menus[$menu_id] = array(
                                            'ID' => $menu_id,
                                            'titulo_menu' => $fila['titulo_menu'],
                                            'columnas' => array()
                                        );
                                    }
                                    
                                    // Verificar si la columna ya existe en el menú
                                    if (!isset($menus[$menu_id]['columnas'][$columna_id])) {
                                        $menus[$menu_id]['columnas'][$columna_id] = array(
                                            'ID' => $columna_id,
                                            'idMenu' => $menu_id,
                                            'nombre_columna' => $fila['nombre_columna'],
                                            'url_columna' => $fila['url_columna'],
                                            'type' => $fila['type_number_columna'],
                                            'subcolumnas' => array()
                                        );
                                    }
                                
                                    // Verificar si hay una subcolumna y agregarla si es el caso
                                    if (!is_null($subcolumna_id)) {
                                        $menus[$menu_id]['columnas'][$columna_id]['subcolumnas'][$subcolumna_id] = array(
                                            'ID' => $subcolumna_id,
                                            'idColumna' => $columna_id,
                                            'nombre_subcolumna' => $fila['nombre_subcolumna'],
                                            'url_subcolumna' => $fila['url_subcolumna'],
                                            'type'=> $fila['type_number_subcolumna']
                                        );
                                    }
                                }
                                



                                // Convertir el array de usuarios a formato JSON
                                $json_resultados = json_encode(array_values($menus), JSON_PRETTY_PRINT);

                                // Imprimir el JSON
                                //echo $json_resultados;

                                // Imprimir el JSON dentro de una etiqueta <script>
                                echo '<script>';
                                echo 'var json_resultados = ' . $json_resultados . ';';
                                echo '</script>';
                            } else {
                                echo "No se encontraron resultados.";
                            }
                                    
                                $sqlMenu = "SELECT * from menu_crear";

                                $result = $mysqli->query($sqlMenu);

                                if ($result->num_rows > 0) {
                                    echo '<div class="input_columns-container" id="select-container">';
                                    echo '<label for="opciones">Nombre Menu:</label>';
                                    echo '<select id="opciones" name="opciones">';
                                
                                    // La opción por defecto cuando no hay una ID seleccionada
                                    echo '<option value="0">Seleccionar Menu</option>';
                                
                                    // Verifica si el parámetro GET 'selectedMenuId' está presente
                                    $selectedMenuId = isset($_GET['selectedMenuId']) ? $_GET['selectedMenuId'] : null;
                                
                                    while ($row = $result->fetch_assoc()) {
                                        // Verificar si las claves existen en el array $row
                                        if (isset($row["ID"]) && isset($row["titulo_menu"])) {
                                            // Determinar si esta opción debe estar seleccionada
                                            $selected = ($selectedMenuId == $row["ID"]) ? ' selected' : '';
                                            echo '<option value="' . $row["ID"] . '"' . $selected . '>' . $row["titulo_menu"] . '</option>';
                                        }
                                    }
                                
                                    echo '</select>';
                                    echo '</div>';
                                } else {
                                    echo "No se encontraron elementos en el menú.";
                                }

                ?>

                <?php
                    // Comprueba si el parámetro selectedMenuId está en la URL y no está vacío
                    if (isset($_GET['selectedMenuId']) && !empty($_GET['selectedMenuId'])) {
                        $selectedMenuId = $_GET['selectedMenuId'];

                        echo'<div class="container-nombre">';
                        echo'<div>Nombre Menu:</div>';
                        echo'<div class="nombre-menu" id="nombre-menu"></div>';
                        echo'</div>';

                        echo '<script>';
                        echo 'var autoselect = ' . $selectedMenuId . ';';
                        echo '</script>';
                    } else {
                        echo '<script>';
                        echo 'var autoselect = null;';
                        echo '</script>';
                    }
                ?>
            </div>
            <div id="display-none">
                <div class="container-select">
                    <p>Agregar botones al menú:</p>
                    <p class= "texto" id="text-1">Posición</p>
                    <input id="valor" class="input-num_columns" type="number"  max="20"/>
                    <button id="btnAgregarColumna"> Agregar Boton</button>
                </div>
            </div>

            <form id="contenedor-menu" class="input_names-container"></form>
            
            <div id="container-btn-sytle" class="container-btn-sytle">
                <button id="btnModificarTextoMenu" class="btn-syle__btn">Modificar Texto Menu</button>
                <button id="modificarBtn" class="btn-syle__btn">Modificar Botón</button>
            </div>

            <div id="btn-modificar-boton" class="btn-modifcar-boton">
                <div class="container-title">
                    <div class="title__menu">Menu Bóton</div>
                    <div id="cerrar_menu" class="cerrar__menu">X</div>                    
                </div>
                <div class="container-btn-modificar">
                    <div id="bg-color" class="btn__style" onclick="bgcolor();" >Color Fondo Bóton</div>
                    <div id="bg-color-hover" class="btn__style" onclick="bghover();">Color Fondo Bóton Hover</div>
                    <div id="btn-width" class="btn__style">Ancho Bóton</div>
                    <div id="btn-border" class="btn__style">Borde Bóton</div>
                    <div id="btn-border-width" class="btn__style">Ancho Borde Bóton</div>
                    <div id="delete-all" class="btn__style">Borrar Todo el Formado</div>
                    <div id="bg-img" class="btn__style">Imagen Fondo Bóton</div>
                    <div id="bg-img-hover" class="btn__style">Imagen Fondo Bóton Hover</div>
                    <div id="btn-height" class="btn__style">Alto Bóton</div>
                    <div id="btn-borde-style" class="btn__style">Estilo Borde Bóton</div>
                    <div id="btn-border-color" class="btn__style">Color Borde Bóton</div>
                    <div class="btn__style"></div>    
                </div>
                <div id="MenuSeleccionado" class="MenuSeleccionado">
                    <div class="seleccionado">
                        <div class="seleccionado__container-title">
                            <div class="seleccionado_titulo">titulo</div>
                            <div id="seleccionado__cerrar" class="seleccionado__cerrar">X</div> 
                        </div>
                        <div id="seleccionado__contenido" class="seleccionado__contenido">
                            <div class="seleccionado__contenedor">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="btn-modificar-texto" class="btn-modifcar-texto">
                <div class="container-title">
                    <div class="title__menu">Menu Texto </div>
                    <div id="cerrar_menu_texto" class="cerrar_menu_texto">X</div>                    
                </div>
                <div>
                    <select class="modificar__tipoletra">
                        <option>Tipo Letra</option>
                    </select>
                    <select class="modificar__tamanoletra">
                        <option>11</option>
                    </select>
                </div>
                <div class="container-btn-modificar_texto">
                    <div class="btn__style_texto">Negrita</div>
                    <div class="btn__style_texto">Cursiva</div>
                    <div class="btn__style_texto">Subrayado</div>
                    <div class="btn__style_texto">Color</div>
                    <div class="btn__style_texto">Posición Fila</div>
                    <div class="btn__style_texto">Posicion columna</div>
                    <div class="btn__style_texto">Viñeta</div>
                    <div class="btn__style_texto">Alineación izquierda</div>
                    <div class="btn__style_texto">Alineación centro</div>
                    <div class="btn__style_texto">Alineación derecha</div>
                    <div class="btn__style_texto">Justificado</div>
                    <div class="btn__style_texto">Animación</div>
                    <div class="btn__style_texto">Tiempo Animación</div>
                    <div class="btn__style_texto">Borrar todo el formato</div>      
                </div>
            </div>



            <div id="estilos" class="estilos">
                <div class="estilos__titulo">Estilos Generales</div>
                    
                <div class="estilos__contenedor1">
                    <div class="contenedor__fuentes">
                        <div class="fuentes__titulo">Estilo Fuentes</div>
                        <div class="fuentes__select">
                            <label for="">Fuentes</label>
                            <select id="selectFuentes">
                                <option select>Seleccionar</option>
                            </select>
                        </div>
                    </div>

                    <div class="contenedor__letras">
                        <div for="" class="letras__titulo">Estilos Letras</div>
                        <div class="letras__inputs">
                            <div class="inputs__xd">
                                <label for="" class="input__estilo">Negrita:</label>
                                <input type="checkbox">                                
                            </div>
                                
                            <div class="inputs__xd">
                                <label for="" class="input__estilo">Cursiva:</label>
                                <input type="checkbox">                                
                            </div>
                                
                            <div class="inputs__xd">
                                <label for="" class="input__estilo">Subrayar:</label>
                                <input type="checkbox">                                
                            </div>
                        </div>
                        <button id="cerrarMenu">Cerrar</button>    
                    </div>
                </div>
                
            </div> 

            <div class="container-btn">
                <!--button onClick="getData()">GetData</button-->
                <button onClick="saveNav()">Guardar</button>
                <button id='Delete'>Borrar todo</button>
                <button id="fullscreeenButton" onClick=toggleFullscreen()>Ir al Sitio Web</button>
                <button>Aplicar programa</button>
            </div>
        </div>

        <div class="container__div2">
            <div class="contenet-center">
                <p>Ejemplo</p>
            </div>   
            <div class="contenet-center">
                <div class="menu_example">             
                    <div>
                        <span class="number-btn">1</span>
                        <div class="btn-example">Boton 1</div>
                    </div>
                    <div>
                        <span class="number-btn">2</span>
                        <div class="btn-example">Boton 2</div>
                        <div>
                            <div class="btn-example">SubMenu 1</div>
                        </div>
                        <div>
                            <div class="btn-example">SubMenu 1</div>
                        </div>
                    </div>
                    <div>
                        <span class="number-btn">3</span>
                        <div class="btn-example">Boton 3</div>
                    </div>
                    <div>
                        <span class="number-btn">4</span>
                        <div class="btn-example">Boton 4</div>
                    </div>
                </div>
            </div>

            <div id="prevista" class="menu_preview"></div>
        </div>

        
    </div>

    <?php
// Se ejecuta el código si existe una petición HTTP POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados en la solicitud POST
    $data = json_decode(file_get_contents("php://input"), true);
    // Preparar los datos del título para la inserción en la base de datos
    $titulo_menuID = $mysqli->real_escape_string($data['ID']);
    $titulo_menu = $mysqli->real_escape_string($data['titulo_menu']);

    // Verificar si el título del menú ya existe
    $sqlTitulo = "SELECT ID FROM menu_crear WHERE ID = '$titulo_menuID'";
    $resultTitulo = $mysqli->query($sqlTitulo);
    

    if ($resultTitulo->num_rows > 0) {
        // Obtener el ID del menú existente
        $fila = $resultTitulo->fetch_assoc();
        $id_menu = $fila['ID'];

        // Actualizar el título del menú si es necesario
        $sqlUpdateMenu = "UPDATE menu_crear SET titulo_menu = '$titulo_menu' WHERE ID = $titulo_menuID";
        $mysqli->query($sqlUpdateMenu);

        // Obtener las columnas existentes para el menú
        $sqlColumns = "SELECT ID FROM columnas WHERE idMenu = $id_menu";
        $resultColumns = $mysqli->query($sqlColumns);
        $existingColumns = [];
        while ($row = $resultColumns->fetch_assoc()) {
            $existingColumns[$row['ID']] = $row['ID'];
        }
        

        // Iterar por cada columna enviada en los datos
        foreach ($data['menuData'] as $columna) {
            $nombre_columna = $mysqli->real_escape_string($columna['name']);
            $url_columna = $mysqli->real_escape_string($columna['url']);

            // Verificar si la columna ya existe
            if (isset($columna['id']) && in_array($columna['id'], $existingColumns)) {
                // Actualizar la columna existente
                $column_id = $columna['id'];
                $sqlUpdateColumn = "UPDATE columnas SET nombre_columna = '$nombre_columna', url_columna = '$url_columna' WHERE ID = $column_id";
                $mysqli->query($sqlUpdateColumn);
                unset($existingColumns[$column_id]);
            } else {
                // Insertar nueva columna
                $columnSql = "INSERT INTO columnas (idMenu, nombre_columna, url_columna) VALUES ('$id_menu', '$nombre_columna', '$url_columna')";
                $mysqli->query($columnSql);
                $column_id = $mysqli->insert_id;
            }

            // Manejar las subcolumnas
            $existingSubColumns = [];
            if (isset($columna['subColumns'])) {
                $sqlSubColumns = "SELECT ID FROM subcolumnas WHERE idColumna = $column_id";
                $resultSubColumns = $mysqli->query($sqlSubColumns);
                while ($row = $resultSubColumns->fetch_assoc()) {
                    $existingSubColumns[$row['ID']] = $row['ID'];
                }

                foreach ($columna['subColumns'] as $subcolumna) {
                    $nombre_subcolumna = $mysqli->real_escape_string($subcolumna['name']);
                    $url_subcolumna = $mysqli->real_escape_string($subcolumna['url']);

                    if (isset($subcolumna['id']) && in_array($subcolumna['id'], $existingSubColumns)) {
                        // Actualizar la subcolumna existente
                        $subcolumn_id = $subcolumna['id'];
                        $sqlUpdateSubColumn = "UPDATE subcolumnas SET nombre_subcolumna = '$nombre_subcolumna', url_subcolumna = '$url_subcolumna' WHERE ID = $subcolumn_id";
                        $mysqli->query($sqlUpdateSubColumn);
                        unset($existingSubColumns[$subcolumn_id]);
                    } else {
                        // Insertar nueva subcolumna
                        $subcolumnSql = "INSERT INTO subcolumnas (idColumna, nombre_subcolumna, url_subcolumna) VALUES ('$column_id', '$nombre_subcolumna', '$url_subcolumna')";
                        $mysqli->query($subcolumnSql);
                    }
                }

                // Eliminar subcolumnas no presentes en los datos recibidos
                foreach ($existingSubColumns as $subcolumn_id) {
                    $sqlDeleteSubColumn = "DELETE FROM subcolumnas WHERE ID = $subcolumn_id";
                    $mysqli->query($sqlDeleteSubColumn);
                }
            }
        }

        // Eliminar columnas no presentes en los datos recibidos
        foreach ($existingColumns as $column_id) {
            $sqlDeleteColumn = "DELETE FROM columnas WHERE ID = $column_id";
            $mysqli->query($sqlDeleteColumn);
            $sqlDeleteSubColumns = "DELETE FROM subcolumnas WHERE idColumna = $column_id";
            $mysqli->query($sqlDeleteSubColumns);
        }

        http_response_code(200);
        exit('Datos actualizados correctamente.');
    }
}
?>
</body>
</html>

<?php
    // Cierra la conexión a la base de datos
    $mysqli->close();
?>
<!-- -------------------------------------------------------------------------------------------------------------
   -------------------------------------- FIN ITred Spa crear.php ------------------------------------------------
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