/*dirección de la API*/
const url = "https://rickandmortyapi.com/api/character/";
/*Una función asíncrona puede esperar respuestas usando await.*/
async function obtenerPersonajes() {
    try {
        /*Se conecta a Internet y solicita los datos.*/
        const respuesta = await fetch(url);
        /*Transforma la respuesta en un objeto JavaScript.*/
        const datos = await respuesta.json();
        /*Extrae el arreglo de personajes.*/
        const personajes = datos.results;
        /*Selecciona el lugar donde se mostrarán los resultados, en este caso en tarjetas*/
        const cardPersonajes = document.getElementById("personajes");
        
        /*recorre con un ciclo todos los Personaje para mostar la información individualmente.*/
        personajes.forEach(personaje => {
            /*Crea dinámicamente un nuevo elemento HTML y agrega los datos correspondientes.*/
            const card = document.createElement("div");
            card.className = "card";
            card.innerHTML = `
            <img src="${personaje.image}" alt="${personaje.name}">
            <h2>${personaje.name}</h2>  
            <p>Especie: ${personaje.species} Estado: ${personaje.status}</p>
            <p>Genero: ${personaje.gender}</p>
            <p>Localización: ${personaje.location.name}</p>
            <p>Origen: ${personaje.origin.name}</p>
            <button>Más información</button>
          `;
            personaje.episode.forEach(capitulo =>{
                console.log(`${personaje.name} ${capitulo}`)
            })
            cardPersonajes.appendChild(card);           
        });
        
    } catch (error) {
        /*Captura errores y evita que el programa se detenga.*/
        console.error("Error al obtener personajes:", error);
    }
}
/*ejecuta la función*/
obtenerPersonajes();
