/**
 * @file
 * Global utilities.
 *
 */

 // variables

 const secretariado = document.querySelector('.list-secretariado');
 const listFormacionContinuo = document.querySelector('.list-formacion-continua');
 const listFormacionIntenacional = document.querySelector('.list-formacion-internacional');



// comprobamos si existe el listado de secretariado.

// BLOQUE DE FORMACIÓN INICIAL

 if (secretariado) {
    const listSecretariado = document.querySelectorAll('.list-secretariado li div');
    listSecretariado.forEach(allElementos); 
    const nuevoIcono = document.querySelectorAll('.list-secretariado li div span.icono');
    nuevoIcono.forEach(nuevosIconos);   
 }

 // BLOQUE DE FORMACIÓN CONTINUA

 if (listFormacionContinuo) {

    const listContinuo = document.querySelectorAll('.list-formacion-continua li div');
    listContinuo.forEach(allElementos); 
    const nuevoIcono = document.querySelectorAll('.list-formacion-continua li div span.icono');
    nuevoIcono.forEach(nuevosIconos);

 }

 // BLOQUE DE FORMACIÓN INTERNACIONAL

 if (listFormacionIntenacional) {

    const listIntenacional = document.querySelectorAll('.list-formacion-internacional li div');
    listIntenacional.forEach(allElementos); 
    const nuevoIcono = document.querySelectorAll('.list-formacion-internacional li div span.icono');
    nuevoIcono.forEach(nuevosIconos);

 }

 // BLOQUE DE FORMACIÓN ABIERTA

 // list-formacion-internacional

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