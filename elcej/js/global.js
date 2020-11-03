/**
 * @file
 * Global utilities.
 *
 */

    var verImag = document.querySelector('#mas-img');
    
    verImag.onclick = function (){
    	var nuevoDiv = document.createElement('div');
	    nuevoDiv.classList.add('nuevo-mensaje');
	    var nuevoEstilo = document.querySelector('.nuevo-mensaje')
	    var contenidoDiv = document.createTextNode("esto es una prueba");
	    nuevoDiv.appendChild(contenidoDiv);

	    verImag.appendChild(nuevoDiv);
    	alert('Esto es una prueba');
    }


    console.log(verImag);

