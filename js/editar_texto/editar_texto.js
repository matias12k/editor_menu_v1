/*
Sitio Web Creado por ITred Spa.
Direccion: Guido Reni #4190
Pedro Agui Cerda - Santiago - Chile
contacto@itred.cl o itred.spa@gmail.com
https://www.itred.cl
Creado, Programado y Diseñado por ITred Spa.
BPPJ
*/

/* ---------------------------------------------------------------------------------------------------------------------
   ------------------- INICIO ITred Spa crear.css ----------------------------------------------------------------------
   --------------------------------------------------------------------------------------------------------------------- */

   let Fuentes = [];

    document.addEventListener('DOMContentLoaded', function() {
        
        cargarFuentes();

        document.addEventListener('DOMContentLoaded', function() {
            let formElement = document.getElementById('cargarFuenteForm');
            if (formElement) {
                formElement.addEventListener('submit', function(event) {
                    event.preventDefault();  // Evitar el envío del formulario y la navegación de página
        
                    let formData = new FormData(this);
                    fetch('../../php/cargarfuente/cargar_fuente.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.error) {
                            console.error('Error:', result.error);
                        } else {
                            console.log('Nuevas fuentes:', result.newFonts);
                            let selectFuentes = document.getElementById('selectFuentes');
                            if (selectFuentes) {
                                result.newFonts.forEach(fuente => {
                                    let option = document.createElement('option');
                                    option.value = fuente;
                                    option.textContent = fuente;
                                    selectFuentes.appendChild(option);
                                });
                            } else {
                                console.error('Elemento con ID "selectFuentes" no encontrado.');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            } else {
                console.error('Elemento con ID "cargarFuenteForm" no encontrado.');
            }
        });
        
       
    
      
      // Mostrar el menú de color de texto
      document.querySelector('.btn__style_texto').addEventListener('click', () => {
          let menuColorTexto = document.getElementById('MenuColorTexto');
          let contenedor = menuColorTexto.querySelector('.seleccionado_color__contenedor');
          CambiarColorTexto(contenedor);
          menuColorTexto.style.display = 'block';
      });
  
      // Cerrar el menú de color de texto
      document.getElementById('seleccionado_color__cerrar').addEventListener('click', () => {
          document.getElementById('MenuColorTexto').style.display = 'none';
      });
  
      // Mostrar u ocultar el menú de estilos
      var boton = document.getElementById('btnModificarTextoMenu');
      var menu = document.getElementById('estilos');
      boton.addEventListener('click', function() {
          if (menu.style.display === 'none' || menu.style.display === '') {
              menu.style.display = 'flex';
          } else {
              menu.style.display = 'none';
          }
      });
  
      // Cerrar el menú de estilos
      const cerrarMenu = document.getElementById('cerrarMenu');
      cerrarMenu.addEventListener('click', function() {
          document.getElementById('estilos').style.display = 'none';
      });
  
      // Aplicar negrita a elementos con la clase 'menu_anchor'
      document.getElementById('checkboxNegrita').addEventListener('change', function() {
          let isChecked = this.checked;
          let textos = document.getElementsByClassName('menu_anchor');
          Array.from(textos).forEach(texto => {
              texto.style.fontWeight = isChecked ? 'bold' : 'normal';
          });
      });

      //hace funcionar el boton de cerrar menu
      document.addEventListener('DOMContentLoaded', function() {
         const cerrarMenu = document.getElementById('cerrarMenu');
         cerrarMenu.addEventListener('click', function() {
          document.getElementById('estilos').style.display = 'none';
         });
      });

      var boton = document.getElementById('btnModificarTextoMenu');

      var menu = document.getElementById('estilos');
  
      boton.addEventListener('click', function() {
          if (menu.style.display === 'none' || menu.style.display === '') {
              menu.style.display = 'flex';
          } else {
              menu.style.display = 'none';
          }
      });
  
  
  
      // Función para manejar el envío del formulario y agregar nuevas fuentes
      document.getElementById('cargarFuenteForm').addEventListener('submit', function(event) {
          event.preventDefault();  // Evitar el envío del formulario y la navegación de página
      
          let formData = new FormData(this);
      
          fetch('../../php/cargar_fuente/cargar_fuente.php', {
              method: 'POST',
              body: formData
          })
          .then(response => response.json())
          .then(result => {
              if (result.error) {
                  console.error('Error:', result.error);
              } else {
                  console.log('Nuevas fuentes:', result.newFonts);
                  let selectFuentes = document.getElementById('selectFuentes');
                  result.newFonts.forEach(fuente => {
                      let option = document.createElement('option');
                      option.value = fuente;
                      option.textContent = fuente;
                      selectFuentes.appendChild(option);
                  });
              }
          })
          .catch(error => {
              console.error('Error:', error);
          });
      });

    });  

















//Funciones del modificar texto
// Función para cargar fuentes desde la base de datos
// Función para cargar las fuentes desde la base de datos
function cargarFuentes() {
    fetch('../../php/fuentes/listar_fuentes/listar_fuentes.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok.');
        }
        return response.text();  // Obtener la respuesta como texto
    })
    .then(text => {
        
        try {
            let fuentes = JSON.parse(text);  // Intentar convertir la respuesta en JSON
            let selectFuentes = document.getElementById('selectFuentes');
            if (selectFuentes) {
                selectFuentes.innerHTML = '<option selected>Seleccionar</option>';  // Limpiar y reestablecer la opción predeterminada

                fuentes.forEach(fuente => {
                    let option = document.createElement('option');
                    option.value = fuente;
                    option.textContent = fuente;
                    selectFuentes.appendChild(option);
                });
            } else {
                console.error('Elemento con ID "selectFuentes" no encontrado.');
            }
        } catch (error) {
            console.error('Error al parsear el JSON:', error);
        }
    })
    .catch(error => {
        console.error('Error al cargar las fuentes:', error);
    });
} 
function CambiarFuente() {
    let selectFuentes = document.getElementById('selectFuentes');

    selectFuentes.addEventListener('change', function() {
        let selectedFont = this.value;
        let textos = document.getElementsByClassName('menu_anchor');
        
        // Cambiar la fuente en los textos con la clase 'menu_anchor'
        Array.from(textos).forEach(texto => {
            texto.style.fontFamily = selectedFont;
        });

        // Guardar la fuente seleccionada en la base de datos
        guardarFuenteSeleccionada(selectedFont);
    });

    // Disparar el evento 'change' para aplicar la fuente seleccionada al cargar la página
    selectFuentes.dispatchEvent(new Event('change'));
}


function guardarFuenteSeleccionada(fuente) {
    fetch('../php/fuentes/guardar_fuente.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ fuente: fuente })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            console.log('Fuente guardada con éxito:', fuente);
        } else {
            console.error('Error al guardar la fuente:', result.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
function agregarFuente(nombreFuente, fuenteUrl) {
    // Verificar si la fuente ya existe
    let existingFonts = Array.from(document.querySelectorAll('#selectFuentes option')).map(option => option.value);
    if (!existingFonts.includes(nombreFuente)) {
        let id = Fuentes.length ? Fuentes[Fuentes.length - 1].id + 1 : 1;
        Fuentes.push({ id, nombre: nombreFuente });

        // Agregar la fuente al navegador
        const style = document.createElement('style');
        style.textContent = `
            @font-face {
                font-family: '${nombreFuente}';
                src: url('${fuenteUrl}');
            }
        `;
        document.head.appendChild(style);

        // Actualizar el listado de fuentes sin recargar la página
        let selectFuentes = document.getElementById('selectFuentes');
        let option = document.createElement('option');
        option.value = nombreFuente;
        option.textContent = nombreFuente;
        selectFuentes.appendChild(option);
    }
}
function actualizarFuentes() {
    fetch('../php/fuentes/actualizar_fonts.php')
        .then(response => response.text())
        .then(result => {
            console.log(result); // Mensaje de éxito de PHP
            cargarFuentes(); // Cargar la lista de fuentes
        })
        .catch(error => {
            console.error('Error:', error);
        });
}












function CambiarTamanioTexto() {
    let selectTamanio = document.getElementById('selectTamanio');
    let textos = document.getElementsByClassName('menu_anchor');
 
    if (!selectTamanio) {
        console.error('El elemento selectTamanio no se encuentra en el DOM');
        return;
    }
 
    selectTamanio.addEventListener('change', function() {
        let nuevoTamanio = this.value + 'px';
 
        Array.from(textos).forEach(texto => {
            texto.style.fontSize = nuevoTamanio;
        });
    });
 
    // Inicializar el tamaño de texto
    let valorInicial = selectTamanio.value + 'px';
    Array.from(textos).forEach(texto => {
        texto.style.fontSize = valorInicial;
    });
}


function toggleNegrita(checkbox) {
    // Obtener todos los elementos con la clase 'menu_anchor'
    let textos = document.getElementsByClassName('menu_anchor');
 
    // Convertir la colección de elementos a un array y aplicar el estilo a cada uno
    Array.from(textos).forEach(texto => {
        // Establecer el estilo de fuente en negrita si el checkbox está marcado, sino, en normal
        texto.style.fontWeight = checkbox.checked ? 'bold' : 'normal';
    });
 }
function toggleCursiva(checkbox) {
   let textos = document.getElementsByClassName('menu_anchor');
   Array.from(textos).forEach(texto => {
       texto.style.fontStyle = checkbox.checked ? 'italic' : 'normal';
   });
}

function toggleSubrayado(checkbox) {
   let textos = document.getElementsByClassName('menu_anchor');
   Array.from(textos).forEach(texto => {
       texto.style.textDecoration = checkbox.checked ? 'underline' : 'none';
   });
}

       
function showMenu(action) {
   const menu = document.getElementById('MenuSeparar');
   const closeBtn = document.getElementById('seleccionado_separar__cerrar');
   
   // Limpiar contenido anterior
   const contenedor = menu.querySelector('.seleccionado__contenedor');
   contenedor.innerHTML = '';
   
   // Mostrar menú flotante
   menu.style.display = 'block';

   // Configurar la acción a realizar
   if (action === 'SepararContenedores') {
       SepararContenedores(contenedor);
   }

   // Evento para cerrar el menú
   closeBtn.addEventListener('click', () => {
       menu.style.display = 'none';
   });
}

function mostrarMenuCambiarColorTexto() {
   const menuColorTexto = document.getElementById('MenuColorTexto');
   const contenedor = menuColorTexto.querySelector('.seleccionado_color__contenedor');
   menuColorTexto.style.display = 'block';
   CambiarColorTexto(contenedor);
}

function cerrarMenuColorTexto() {
   const menuColorTexto = document.getElementById('MenuColorTexto');
   menuColorTexto.style.display = 'none';
}

function CambiarColorTexto(contenedor) {
   const tituloSeleccionado = document.querySelector('.seleccionado_color_titulo');
   let seleccionado__contenedor = contenedor;

   // Limpiar el contenedor
   seleccionado__contenedor.innerHTML = '';

   // Crear y agregar los elementos para la selección del botón
   let divNombre = document.createElement('div');
   divNombre.textContent = 'Seleccionar botón:';

   let selectBotones = document.createElement('select');
   let defaultOption = document.createElement('option');
   defaultOption.textContent = 'Seleccione un botón';
   defaultOption.disabled = true;
   defaultOption.selected = true;
   selectBotones.appendChild(defaultOption);

   let botones = document.querySelectorAll('.menu_anchor');
   botones.forEach((boton, index) => {
       let option = document.createElement('option');
       option.value = index;
       option.textContent = boton.textContent;
       selectBotones.appendChild(option);
   });

   // Crear y agregar el campo para cambiar el color del texto
   let inputColorContainer = document.createElement('div');
   inputColorContainer.style.display = 'none';

   let divColor = document.createElement('div');
   divColor.textContent = 'Color del Texto:';

   let inputColor = document.createElement('input');
   inputColor.type = 'color';
   inputColor.value = '#000000'; // Inicializar con color negro

   inputColorContainer.appendChild(divColor);
   inputColorContainer.appendChild(inputColor);

   // Botón para quitar cambios
   let divClearButton = document.createElement('div');
   divClearButton.style.marginTop = '10px';
   divClearButton.style.display = 'none';

   let clearButton = document.createElement('button');
   clearButton.textContent = 'Quitar Cambios';

   divClearButton.appendChild(clearButton);

   // Botón para aplicar color a todos los botones
   let divApplyAllButton = document.createElement('div');
   divApplyAllButton.style.marginTop = '10px';
   divApplyAllButton.style.display = 'none';

   let applyAllButton = document.createElement('button');
   applyAllButton.textContent = 'Aplicar a Todos';

   divApplyAllButton.appendChild(applyAllButton);

   // Añadir todos los elementos al contenedor
   seleccionado__contenedor.appendChild(divNombre);
   seleccionado__contenedor.appendChild(selectBotones);
   seleccionado__contenedor.appendChild(inputColorContainer);
   seleccionado__contenedor.appendChild(divClearButton);
   seleccionado__contenedor.appendChild(divApplyAllButton);

   // Mostrar el contenedor de color y botones adicionales cuando se selecciona un botón
   selectBotones.addEventListener('change', () => {
       inputColorContainer.style.display = 'block';
       divClearButton.style.display = 'block';
       divApplyAllButton.style.display = 'block';
   });

   // Aplicar el color automáticamente cuando cambia el valor del input
   function applyColor() {
       let selectedIndex = selectBotones.value;
       if (selectedIndex !== '') {
           let boton = botones[selectedIndex];
           boton.style.color = inputColor.value;
       }
   }

   inputColor.addEventListener('input', applyColor);

   // Quitar cambios de color para todos los botones
   clearButton.addEventListener('click', () => {
       botones.forEach(boton => {
           boton.style.color = '';
       });
       // Restablecer el color en el selector
       inputColor.value = '#000000';
   });

   // Aplicar color a todos los botones
   applyAllButton.addEventListener('click', () => {
       let color = inputColor.value;
       botones.forEach(boton => {
           boton.style.color = color;
       });
   });

   tituloSeleccionado.textContent = 'Cambiar Color del Texto del Botón';
}

let botonSeleccionado = null; // Variable para almacenar el índice del botón seleccionado
let estilosOriginales = []; // Array para guardar los estilos originales de los botones

// Función para abrir el menú de texto y configurar las opciones de botones
function abrirMenuTexto() {
   document.getElementById('MenuTexto').style.display = 'block';
   actualizarOpcionesBotones(); // Actualiza las opciones del selector de botones
   guardarEstilosOriginales(); // Guarda los estilos originales de los botones
}

// Función para cerrar el menú de texto
function cerrarMenuTexto() {
   document.getElementById('MenuTexto').style.display = 'none';
}

// Función para actualizar las opciones del selector de botones
function actualizarOpcionesBotones() {
   let selectBotones = document.getElementById('selectBotonesTexto');
   let botones = document.querySelectorAll('.menu_anchor');

   // Limpiar opciones anteriores
   selectBotones.innerHTML = '<option value="">Seleccione un botón</option>';

   // Añadir opciones para cada botón encontrado con la clase 'menu_anchor'
   botones.forEach((boton, index) => {
       let option = document.createElement('option');
       option.value = index;
       option.textContent = boton.textContent || `Botón ${index + 1}`;
       selectBotones.appendChild(option);
   });

   // Ocultar contenedor de alineación hasta que se seleccione un botón
   document.getElementById('alineacionTextoContainer').style.display = 'none';
}

// Función para actualizar el botón seleccionado desde el selector
function actualizarBotonSeleccionado() {
   let selectBotones = document.getElementById('selectBotonesTexto');
   botonSeleccionado = selectBotones.value;

   // Mostrar contenedor de alineación solo si hay un botón seleccionado
   document.getElementById('alineacionTextoContainer').style.display = botonSeleccionado ? 'block' : 'none';
}

// Función para cambiar la alineación horizontal del texto en el botón seleccionado
function cambiarAlineacionTexto() {
   let alineacion = document.getElementById('alineacionTexto').value;
   let botones = document.querySelectorAll('.menu_anchor');

   if (botonSeleccionado !== null) {
       let boton = botones[botonSeleccionado];
       boton.style.textAlign = alineacion; // Aplica la alineación seleccionada
   }
}

// Función para cambiar la alineación vertical del texto en el botón seleccionado
function cambiarAlineacionVertical() {
   let alineacionVertical = document.getElementById('alineacionVertical').value;
   let botones = document.querySelectorAll('.menu_anchor');

   if (botonSeleccionado !== null) {
       let boton = botones[botonSeleccionado];
       boton.style.display = 'flex';
       boton.style.alignItems = alineacionVertical; // Aplica la alineación vertical seleccionada
   }
}

// Función para aplicar las configuraciones de alineación a todos los botones
function aplicarAlineacionATodos() {
   let alineacion = document.getElementById('alineacionTexto').value;
   let alineacionVertical = document.getElementById('alineacionVertical').value;
   let botones = document.querySelectorAll('.menu_anchor');

   botones.forEach(boton => {
       boton.style.textAlign = alineacion; // Aplica la alineación horizontal a todos los botones
       boton.style.display = 'flex';
       boton.style.alignItems = alineacionVertical; // Aplica la alineación vertical a todos los botones
   });
}

// Función para deshacer todos los cambios aplicados y restaurar los estilos originales
function deshacerCambiosEnTodos() {
   let botones = document.querySelectorAll('.menu_anchor');

   botones.forEach((boton, index) => {
       boton.style.textAlign = estilosOriginales[index].textAlign; // Restaura la alineación horizontal original
       boton.style.alignItems = estilosOriginales[index].alignItems; // Restaura la alineación vertical original
       boton.style.display = estilosOriginales[index].display; // Restaura la propiedad display original
   });
}

// Función para guardar los estilos originales de los botones antes de aplicar cambios
function guardarEstilosOriginales() {
   let botones = document.querySelectorAll('.menu_anchor');
   estilosOriginales = Array.from(botones).map(boton => ({
       textAlign: boton.style.textAlign,
       alignItems: boton.style.alignItems,
       display: boton.style.display
   }));
}








function mostrarMenuAnimacion(contenedor) {
   // Limpiar el contenido del contenedor
   contenedor.innerHTML = '';

   // Crear y agregar los elementos para la selección del botón
   let divNombre = document.createElement('div');
   divNombre.textContent = 'Seleccionar botón:';

   let selectBotones = document.createElement('select');
   let defaultOption = document.createElement('option');
   defaultOption.textContent = 'Seleccione un botón';
   defaultOption.disabled = true;
   defaultOption.selected = true;
   selectBotones.appendChild(defaultOption);

   let botones = document.querySelectorAll('.menu_anchor');
   botones.forEach((boton, index) => {
       let option = document.createElement('option');
       option.value = index;
       option.textContent = boton.textContent || `Botón ${index + 1}`;
       selectBotones.appendChild(option);
   });

   contenedor.appendChild(divNombre);
   contenedor.appendChild(selectBotones);

   // Contenedor para los elementos adicionales (animación, duración y checkboxes)
   let elementosAdicionales = document.createElement('div');
   elementosAdicionales.style.display = 'none';

   // Crear y agregar el selector de animación
   let divAnimacion = document.createElement('div');
   divAnimacion.textContent = 'Seleccionar Animación:';

   let selectAnimacion = document.createElement('select');
   selectAnimacion.id = 'selectAnimacion';
   let opcionesAnimacion = ['ninguna', 'zoom', 'girar', 'desvanecer'];
   opcionesAnimacion.forEach(opcion => {
       let animOption = document.createElement('option');
       animOption.value = opcion.toLowerCase();
       animOption.textContent = opcion.charAt(0).toUpperCase() + opcion.slice(1);
       selectAnimacion.appendChild(animOption);
   });

   elementosAdicionales.appendChild(divAnimacion);
   elementosAdicionales.appendChild(selectAnimacion);

   // Crear y agregar el selector de duración de animación
   let divDuracion = document.createElement('div');
   divDuracion.textContent = 'Duración (segundos):';

   let inputDuracion = document.createElement('input');
   inputDuracion.type = 'number';
   inputDuracion.id = 'selectDuracion';
   inputDuracion.min = 0.1;
   inputDuracion.step = 0.1;
   inputDuracion.value = 0.5;

   elementosAdicionales.appendChild(divDuracion);
   elementosAdicionales.appendChild(inputDuracion);

   // Crear y agregar los checkboxes para la animación
   let divOpciones = document.createElement('div');
   divOpciones.textContent = 'Opciones de Animación:';

   let checkboxCargar = document.createElement('input');
   checkboxCargar.type = 'checkbox';
   checkboxCargar.id = 'animacionCargar';

   let labelCargar = document.createElement('label');
   labelCargar.htmlFor = 'animacionCargar';
   labelCargar.textContent = 'Aplicar al cargar la página';

   let checkboxHover = document.createElement('input');
   checkboxHover.type = 'checkbox';
   checkboxHover.id = 'animacionHover';

   let labelHover = document.createElement('label');
   labelHover.htmlFor = 'animacionHover';
   labelHover.textContent = 'Aplicar al pasar el mouse';

   elementosAdicionales.appendChild(divOpciones);
   elementosAdicionales.appendChild(checkboxCargar);
   elementosAdicionales.appendChild(labelCargar);
   elementosAdicionales.appendChild(document.createElement('br'));
   elementosAdicionales.appendChild(checkboxHover);
   elementosAdicionales.appendChild(labelHover);

   // Botón para aplicar la animación a un botón seleccionado
   let aplicarAnimacionBtn = document.createElement('button');
   aplicarAnimacionBtn.textContent = 'Aplicar Animación al Botón Seleccionado';

   // Botón para aplicar la animación a todos los botones
   let aplicarAnimacionTodosBtn = document.createElement('button');
   aplicarAnimacionTodosBtn.textContent = 'Aplicar Animación a Todos los Botones';

   elementosAdicionales.appendChild(aplicarAnimacionBtn);
   elementosAdicionales.appendChild(aplicarAnimacionTodosBtn);

   contenedor.appendChild(elementosAdicionales);

   // Mostrar opciones de animación y duración cuando se selecciona un botón
   selectBotones.addEventListener('change', () => {
       elementosAdicionales.style.display = 'block';
   });

   // Aplicar animación al botón seleccionado al hacer clic en el botón de aplicar
   aplicarAnimacionBtn.addEventListener('click', () => {
       let selectedIndex = selectBotones.value;
       let selectedBoton = botones[selectedIndex];
       let animacion = selectAnimacion.value;
       let duracion = inputDuracion.value;
       let aplicarCargar = checkboxCargar.checked;
       let aplicarHover = checkboxHover.checked;

       // Remover cualquier clase de animación anterior
       selectedBoton.classList.remove('animacion-zoom', 'animacion-girar', 'animacion-desvanecer');
       selectedBoton.style.animation = '';

       // Aplicar la nueva clase de animación si no es 'ninguna'
       if (animacion !== 'ninguna') {
           if (aplicarCargar) {
               selectedBoton.classList.add(`animacion-${animacion}`);
               selectedBoton.style.animationDuration = `${duracion}s`;
           }
           if (aplicarHover) {
               selectedBoton.addEventListener('mouseover', () => {
                   selectedBoton.style.animation = `${animacion} ${duracion}s ease-in-out forwards`;
               });
               selectedBoton.addEventListener('mouseout', () => {
                   selectedBoton.style.animation = '';
               });
           }
       }
   });

   // Aplicar animación a todos los botones al hacer clic en el botón de aplicar a todos
   aplicarAnimacionTodosBtn.addEventListener('click', () => {
       let animacion = selectAnimacion.value;
       let duracion = inputDuracion.value;
       let aplicarCargar = checkboxCargar.checked;
       let aplicarHover = checkboxHover.checked;

       botones.forEach(boton => {
           // Remover cualquier clase de animación anterior
           boton.classList.remove('animacion-zoom', 'animacion-girar', 'animacion-desvanecer');
           boton.style.animation = '';

           // Aplicar la nueva clase de animación si no es 'ninguna'
           if (animacion !== 'ninguna') {
               if (aplicarCargar) {
                   boton.classList.add(`animacion-${animacion}`);
                   boton.style.animationDuration = `${duracion}s`;
               }
               if (aplicarHover) {
                   boton.addEventListener('mouseover', () => {
                       boton.style.animation = `${animacion} ${duracion}s ease-in-out forwards`;
                   });
                   boton.addEventListener('mouseout', () => {
                       boton.style.animation = '';
                   });
               }
           }
       });
   });
}
function abrirMenuAnimacion() {
   // Muestra el menú de animación
   document.getElementById('MenuAnimacion').style.display = 'block';

   // Llama a la función para mostrar las opciones del menú de animación
   const contenedor = document.querySelector('#MenuAnimacion .seleccionado__contenedor');
   mostrarMenuAnimacion(contenedor);
}

function cerrarMenuAnimacion() {
   // Oculta el menú de animación
   document.getElementById('MenuAnimacion').style.display = 'none';
}
// Función para mostrar el menú de viñetas
function abrirMenuVinetas() {
   document.getElementById('MenuVinetas').style.display = 'block';
   const contenedor = document.querySelector('#MenuVinetas .seleccionado__contenedor');
   mostrarMenuVinetas(contenedor);
}

// Función para cerrar el menú de viñetas
function cerrarMenuVinetas() {
   document.getElementById('MenuVinetas').style.display = 'none';
}

// Función para mostrar el contenido del menú de viñetas
function mostrarMenuVinetas(contenedor) {
   // Limpiar el contenido del contenedor
   contenedor.innerHTML = '';

   // Crear y agregar los elementos para la selección del botón
   let divNombre = document.createElement('div');
   divNombre.textContent = 'Seleccionar botón:';

   let selectBotones = document.createElement('select');
   let defaultOption = document.createElement('option');
   defaultOption.textContent = 'Seleccione un botón';
   defaultOption.disabled = true;
   defaultOption.selected = true;
   selectBotones.appendChild(defaultOption);

   let botones = document.querySelectorAll('.menu_anchor');
   botones.forEach((boton, index) => {
       let option = document.createElement('option');
       option.value = index;
       option.textContent = boton.textContent || `Botón ${index + 1}`;
       selectBotones.appendChild(option);
   });

   contenedor.appendChild(divNombre);
   contenedor.appendChild(selectBotones);

   // Contenedor para las opciones de viñetas
   let opcionesVinetasContenedor = document.createElement('div');
   opcionesVinetasContenedor.style.display = 'none';
   contenedor.appendChild(opcionesVinetasContenedor);

   // Crear y agregar los elementos para la selección del estilo de viñetas
   let divVinetas = document.createElement('div');
   divVinetas.textContent = 'Seleccionar estilo de viñetas:';

   let selectVinetas = document.createElement('select');
   let defaultVinetasOption = document.createElement('option');
   defaultVinetasOption.textContent = 'Seleccione un estilo de viñetas';
   defaultVinetasOption.disabled = true;
   defaultVinetasOption.selected = true;
   selectVinetas.appendChild(defaultVinetasOption);

   // Diferentes estilos de viñetas (similares a los de Word)
   let estilosVinetas = ['•', '◦', '▪', '▫', '✓', '✔', '❖', '➢'];
   estilosVinetas.forEach(estilo => {
       let option = document.createElement('option');
       option.value = estilo;
       option.textContent = estilo;
       selectVinetas.appendChild(option);
   });

   opcionesVinetasContenedor.appendChild(divVinetas);
   opcionesVinetasContenedor.appendChild(selectVinetas);

   // Botón para agregar viñetas al botón seleccionado
   let agregarVinetasBtn = document.createElement('button');
   agregarVinetasBtn.textContent = 'Agregar Viñetas';
   opcionesVinetasContenedor.appendChild(agregarVinetasBtn);

   // Botón para quitar viñetas del botón seleccionado
   let quitarVinetasBtn = document.createElement('button');
   quitarVinetasBtn.textContent = 'Quitar Viñetas';
   opcionesVinetasContenedor.appendChild(quitarVinetasBtn);

   // Lógica para agregar viñetas al botón seleccionado
   agregarVinetasBtn.addEventListener('click', () => {
       let botonSeleccionadoIndex = selectBotones.value;
       let estiloVinetaSeleccionado = selectVinetas.value;
       if (botonSeleccionadoIndex !== '' && estiloVinetaSeleccionado !== '') {
           let botonSeleccionado = botones[botonSeleccionadoIndex];
           if (!botonSeleccionado.textContent.trim().startsWith(estiloVinetaSeleccionado)) {
               botonSeleccionado.textContent = estiloVinetaSeleccionado + ' ' + botonSeleccionado.textContent.trim();
           }
       }
   });

   // Lógica para quitar viñetas del botón seleccionado
   quitarVinetasBtn.addEventListener('click', () => {
       let botonSeleccionadoIndex = selectBotones.value;
       if (botonSeleccionadoIndex !== '') {
           let botonSeleccionado = botones[botonSeleccionadoIndex];
           let textoBoton = botonSeleccionado.textContent.trim();
           let estiloVineta = selectVinetas.value;
           if (textoBoton.startsWith(estiloVineta)) {
               botonSeleccionado.textContent = textoBoton.slice(estiloVineta.length).trim();
           }
       }
   });

   // Mostrar opciones de viñetas al seleccionar un botón
   selectBotones.addEventListener('change', () => {
       opcionesVinetasContenedor.style.display = 'block';
   });
}
function restablecerCambios() {
   // Restaurar el tamaño de texto
   let selectTamanio = document.getElementById('selectTamanio');
   let textos = document.getElementsByClassName('menu_anchor');
   let valorInicial = selectTamanio.value + 'px';
   Array.from(textos).forEach(texto => {
       texto.style.fontSize = valorInicial;
   });

   // Restaurar la fuente
   let selectFuentes = document.getElementById('selectFuentes');
   let fuenteInicial = selectFuentes.value;
   Array.from(textos).forEach(texto => {
       texto.style.fontFamily = fuenteInicial;
   });

   // Restaurar el color del texto
   let botones = document.querySelectorAll('.menu_anchor');
   botones.forEach(boton => {
       boton.style.color = ''; // Restaurar el color por defecto
   });

   // Restaurar negrita
   Array.from(textos).forEach(texto => {
       texto.style.fontWeight = ''; // Restaurar el peso por defecto
   });

   // Restaurar cursiva
   Array.from(textos).forEach(texto => {
       texto.style.fontStyle = ''; // Restaurar el estilo por defecto
   });

   // Restaurar subrayado
   Array.from(textos).forEach(texto => {
       texto.style.textDecoration = ''; // Restaurar la decoración por defecto
   });

   // Restaurar alineación del texto
   Array.from(textos).forEach(texto => {
       texto.style.textAlign = ''; // Restaurar la alineación por defecto
       texto.style.alignItems = ''; // Restaurar la alineación vertical por defecto
       texto.style.display = ''; // Restaurar la propiedad display por defecto
   });

   // Restaurar viñetas
   botones.forEach(boton => {
       let texto = boton.textContent.trim();
       let estilosVinetas = ['•', '◦', '▪', '▫', '✓', '✔', '❖', '✿'];
       estilosVinetas.forEach(estilo => {
           if (texto.startsWith(estilo)) {
               boton.textContent = texto.slice(estilo.length).trim();
           }
       });
   });

   // Restaurar animaciones
   Array.from(textos).forEach(texto => {
       texto.style.animation = ''; // Restaurar animaciones por defecto
       texto.style.transition = ''; // Restaurar transiciones por defecto
   });

   
}
//Fin Funciones del modificar texto


function makeElementDraggable(elementId) {
    const draggableElement = document.getElementById(elementId);
    let offsetX = 0;
    let offsetY = 0;
    let initialX = 0;
    let initialY = 0;
    let isDragging = false;

    function onMouseMove(e) {
        if (isDragging) {
            const windowWidth = window.innerWidth;
            const elementWidth = draggableElement.offsetWidth;

            let newLeft = e.clientX - offsetX;
            let newTop = e.clientY - offsetY;

            // Limitar movimiento a la izquierda y derecha
            if (newLeft < 0) newLeft = 0;
            if (newLeft + elementWidth > windowWidth) newLeft = windowWidth - elementWidth;

            // Limitar movimiento hacia arriba
            if (newTop < 0) newTop = 0;

            // Actualizar la posición del elemento
            draggableElement.style.left = `${newLeft}px`;
            draggableElement.style.top = `${newTop}px`;
        }
    }

    function onMouseUp() {
        if (isDragging) {
            isDragging = false;
            document.body.style.cursor = 'auto';
            document.removeEventListener('mousemove', onMouseMove);
            document.removeEventListener('mouseup', onMouseUp);
        }
    }

    draggableElement.addEventListener('mousedown', (e) => {
        isDragging = true;
        initialX = draggableElement.offsetLeft;
        initialY = draggableElement.offsetTop;
        offsetX = e.clientX - initialX;
        offsetY = e.clientY - initialY;
        document.body.style.cursor = 'move';

        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);
    });
}



/* ---------------------------------------------------------------------------------------------------------------------
   -------------------------------------- FIN ITred Spa crear.css ------------------------------------------------------
   --------------------------------------------------------------------------------------------------------------------- */

/*
Sitio Web Creado por ITred Spa.
Direccion: Guido Reni #4190
Pedro Agui Cerda - Santiago - Chile
contacto@itred.cl o itred.spa@gmail.com
https://www.itred.cl
Creado, Programado y Diseñado por ITred Spa.
BPPJ
*/