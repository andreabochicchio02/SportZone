function start(){
    const bottone = document.getElementById("myButton");
    
    bottone.addEventListener("click", function(event){
        event.preventDefault();
        let primo = controllo();            /*Controllo campi obbligatori*/
        let secondo = controllo_data();     /*Controllo sulle date*/
        
        if(primo && secondo)
            invio();
    });


    let ingresso = document.getElementById("container");
    ingresso.addEventListener("submit", (event) =>{
        event.preventDefault();
    });

    /*
    Ci sono delle chiamate di funzione nel file crea_torneo.php 
    per visualizzare o nascondere le info della tipologia di torneo
    */
}

/*Controllo campi obbligatori*/
function controllo(){
    const input = document.getElementById("nome");
    const sport_select = document.getElementById("sport");
    const tipo = document.querySelectorAll("input[type='radio']");
    const errore = document.querySelector(".error");
    

    let ingresso = input.value.trim();

    let checked = false;
    if (tipo[0].checked || tipo[1].checked || tipo[2].checked || tipo[3].checked) {
        checked = true;
    }

    let result = true;
    if(ingresso === "" || sport_select.value === "" || !checked){
        errore.textContent = "Nome, Sport e Tipologia sono campi obbligatori";
        result =  !result; 
    }

    return result;
}

/*Controllo sulle date*/
function controllo_data(){
    const errore = document.querySelector(".error");
    const data1 = document.getElementById("data_inizio"); 
    const data2 = document.getElementById("data_fine"); 
    
    
    let result = true;
    if(data1.value > data2.value){
        errore.textContent = "Data inizio non può essere dopo Data fine";
        result =  !result;
    }

    return result;
}


function mostra(index){
    let nota = document.getElementById("info" + index);
    nota.classList.remove("hide");
}

function nascondi(index){
    let nota = document.getElementById("info" + index);
    nota.classList.add("hide");
}


async function invio(){
    const ingresso = document.getElementById("container");
    const error = document.querySelector(".error");

    const dati = new FormData(ingresso);

    try { 
        let r = await fetch('../php/richieste/ins_torneo.php', {
            method: 'POST',
            body: dati
        });

        let d = await r.json();

        if (d.torneo === true) {
            window.location.href = '../php/crea_partecipanti.php';
        } else {
            throw new Error(d.error);
        }
    } 
    catch (e) { 
        /*Errore server*/
        error.textContent = e.message;
    }
}