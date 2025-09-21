/*Usato come paramentro per lavorare dinamicamente 
con la pagina iniziale o con la barra di navigazione 
usando stesse funzioni e variabili*/
const BARRA = 0;
const PAGINA = 1;

let SCOPRI;
let CERCA, ACCEDI, NASCOSTO;

const INPUT = document.getElementsByClassName("torneo");


function begin(){
    /*Crea i pulsanti della barra di navigazione*/
    creapulsanti();

    /*Dopo aver scrollato la pagina compare la nav bar*/
    window.addEventListener("scroll", bar);

    
    /*Al click di "SCOPRI DI PIÙ" scrolla la pagina*/
    SCOPRI = document.getElementById("scopri");
    SCOPRI.addEventListener("click", scrolling);

    CERCA = document.getElementsByClassName("cerca"); 
    ACCEDI = document.getElementsByClassName("accedi");
    NASCOSTO = document.getElementsByTagName("div");
    
    /*Al click apre l'input per cercare il torneo*/
    CERCA[BARRA].addEventListener("click", () =>  apri(BARRA));
    CERCA[PAGINA].addEventListener("click", () =>  apri(PAGINA));

    /*Al click apre l'input per creare il torneo*/
    ACCEDI[BARRA].addEventListener("click", login);
    ACCEDI[PAGINA].addEventListener("click", login);

    /*Gestisce il click dell'opzione torneo e la richiesta*/
    let invio = document.getElementsByClassName("invio");
    invio[BARRA].addEventListener("click", () => torneo(BARRA));
    invio[PAGINA].addEventListener("click", () => torneo(PAGINA));

    /*Al click toglie l'input per cercare il torneo*/
    let esc = document.getElementsByClassName("chiudi");
    esc[BARRA].addEventListener("click", () => chiudi(BARRA));
    esc[PAGINA].addEventListener("click", () => chiudi(PAGINA));
}

/*Crea il pulsanti della barra di navigazione*/
function creapulsanti(){
    let pulsanti = document.getElementsByTagName("nav")[0];
    let clone = pulsanti.cloneNode(true);

    let root = document.getElementById("bar_nav");
    root.appendChild(clone);

    INPUT[BARRA].setAttribute("list", "tornei0");
    let lista = document.getElementsByTagName("datalist")[BARRA];
    lista.id = "tornei0";
}

/*Dopo aver scrollato la pagina esce la nav bar*/
function bar(){
    let navig = document.querySelector("header");
    if (window.scrollY > 600) {
        navig.classList.remove("hide");
    }else {
        navig.classList.add("hide");
    }
}

/*Al click di "SCOPRI DI PIÙ" scrolla la pagina*/
function scrolling(){
    window.scrollBy(0, 400);
    SCOPRI.disabled = true;
}


/*Al click apre l'input per cercare il torneo*/
function apri(index){
    NASCOSTO[index].classList.add("nascosto");
    NASCOSTO[index].classList.remove("hide");
    CERCA[index].classList.add("hide");
    ACCEDI[index].classList.add("hide");
}


/*Al click apre l'input per creare il torneo*/
function login() {
    window.location.href = 'php/login.php';
}

/*Gestisce il click dell'opzione torneo e la richiesta*/
function torneo(index) {
    //Controllo che è tra le opzioni
    let opzioni = document.getElementById("tornei0").options;
    let optionFound = false;
    for (let i = 0; i < opzioni.length; i++) {
        if (INPUT[index].value.toLowerCase() === opzioni[i].value.toLowerCase()) {
            optionFound = true;
            search(opzioni[i].value);       //Inizia la richiesta
            break;
        }
    }
    
    if (!optionFound) {
        INPUT[index].value = "";
        INPUT[index].setAttribute("placeholder", "Torneo non esiste, clicca e cerca tra le opzioni");
    }
}

/*Al click toglie l'input per cercare il torneo*/
function chiudi(index){
    NASCOSTO[index].classList.add("hide");
    NASCOSTO[index].classList.remove("nascosto");
    CERCA[index].classList.remove("hide");
    ACCEDI[index].classList.remove("hide");

    INPUT[index].setAttribute("placeholder", "");
}

/*Richiesta*/
async function search(ingresso){
    const url = 'php/richieste/ric_torneo.php?' + 'tornei=' + ingresso;

    try {
        let r = await fetch(url);
        let d = await r.json();

        if (d.ricerca === true) {
            window.location.href = 'php/torneo.php?' + 'torneo=' + ingresso;
        } else {
            throw new Error(d.error);
        }
    } 
    catch (e) { 
        window.location.href = 'HTML/404.html';
    }
}

