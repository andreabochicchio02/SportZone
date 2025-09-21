let popup;

function start(){
    popup = document.getElementsByClassName('popup');

    salva_partita();            
    salva_risultato();

    /*Bottoni per chiudere i popup*/
    const closePopupButton = document.getElementsByClassName('chiudi');
    closePopupButton[0].addEventListener('click', () => {
        chiudipopup(0);
    });
    closePopupButton[1].addEventListener('click', () => {
        chiudipopup(1);
    });

    /*Disattiva l'evento dei bottone nei form*/
    let form_match = document.getElementById("form_partita");
    form_match.addEventListener("submit", prevent);
    let form_ris = document.getElementById("form_risultato");
    form_ris.addEventListener("submit", prevent);
}

/*E' chiamata per aggiungere partita*/
function popup_partite(){
    popup[0].style.display = 'flex';
}

/*E' chiamata per aggiungere risultato*/
function popup_risultati(p1, p2){
    popup[1].style.display = 'flex';

    document.getElementById("p1").textContent =  p1;;
    document.getElementById("p2").textContent = p2;
}

/*E' chiamata per chiudere il pupup partita o risultato*/
function chiudipopup(index){
    popup[index].style.display = 'none';
}

/*Disabilita funzione input tipo submit*/
function prevent(event){
    event.preventDefault();
}

function salva_partita(){
    const p1 = document.getElementById('partecipante1');
    const p2 = document.getElementById('partecipante2');
    const errore = document.getElementById('error_partita');
    const salva = document.getElementsByClassName('invio')[0];
    salva.addEventListener('click', function(event){
        event.preventDefault();
        if(p1.value === p2.value){   
            errore.textContent = "Partecipante 1 e Partecipante 2 coincidono";
        } else {
            invio_partita();
        }
    }); 
}

async function invio_partita(){
    const ingresso = document.getElementById("form_partita");
    const errore = document.getElementById('error_partita');

    const dati = new FormData(ingresso);

    try { 
        let r = await fetch('../php/richieste/ins_partite.php', {
            method: 'POST',
            body: dati
        });

        let d = await r.json();

        if (d.partita === true) {
            window.location.href = '../php/crea_match.php';
        } else {
            throw new Error(d.error);
        }
    } 
    catch (e) { 
        /*Errore server*/
        errore.textContent = e.message;
    }
}


function salva_risultato(){
    const r1 = document.getElementById('punti1');
    const r2 = document.getElementById('punti2');
    const errore = document.getElementById('error_ris');

    const salva = document.getElementsByClassName('invio')[1];
    salva.addEventListener('click', function(event){
        event.preventDefault();
        if(r1.value==="" || r2.value===""){   
            errore.textContent = "Tutti i campi sono obbligatori";
        } 
        if(r1.value < 0 || r2.value < 0){   
            errore.textContent = "Non è possobile mettere risultato negativo";
        }else {
            invio_risultato();         /*Passo l'elemento bottone perchè mi servirà dopo*/
        }
    });
}

async function invio_risultato(){
    const ingresso = document.getElementById("form_risultato");
    const errore = document.getElementById('error_ris');

    const dati = new FormData(ingresso);

    let part1 = document.getElementById("p1").textContent;
    let part2 = document.getElementById("p2").textContent;
    dati.append('id1', part1);
    dati.append('id2', part2);
    
    try { 
        let r = await fetch('../php/richieste/ins_punteggio.php', {
            method: 'POST',
            body: dati
        });

        let d = await r.json();

        if (d.risultato === true) {
            window.location.href = '../php/crea_match.php';
        } else {
            throw new Error(d.error);
        }
    } 
    catch (e) { 
        errore.textContent = e.message;
    }
}