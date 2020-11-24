/**
 * @file
 * Global utilities.
 *
 */

 const secretariado = document.querySelector('.list-secretariado');
 const listFormacionContinuo = document.querySelector('.list-formacion-continua');



// comprobamos si existe el listado de secretariado.

 if (secretariado) {
    const listSecretariado = document.querySelectorAll('.list-secretariado li div');
    listSecretariado.forEach(allElementos); 
    const nuevoIcono = document.querySelectorAll('.list-secretariado li div span.icono');
    console.log (nuevoIcono);
    nuevoIcono.forEach(nuevosIconos);   
 }

 if (listFormacionContinuo) {

    const listSecretariado = document.querySelectorAll('.list-formacion-continua li div');
    listSecretariado.forEach(allElementos); 
    const nuevoIcono = document.querySelectorAll('.list-formacion-continua li div span.icono');
    console.log (nuevoIcono);
    nuevoIcono.forEach(nuevosIconos);

 }

// FUNCIONES 

 function allElementos (capa) {

    const icono = document.createElement('span');
    icono.classList.add('icono', 'documento');
    capa.prepend(icono);
 }

 function nuevosIconos (iconos) {

    const iconoNuevo = document.createElement('i');
    iconoNuevo.classList.add('far','fa-file-alt', 'fa-2x');
    iconoNuevo.style.color = '#256cc8';
    iconos.append(iconoNuevo);

 }

 // insertamos los iconos en los distintos elementos.
/* function allElementos(iconos) {
    const iconoNuevo = document.createElement('i');
    iconoNuevo.classList.add('far','fa-file-alt', 'fa-2x');
    iconoNuevo.style.color = '#256cc8';
    iconos.prepend(iconoNuevo);

    console.log(iconos);

 }*/