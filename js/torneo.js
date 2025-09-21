let DATI = new Array();   /*USERO' QUESTA VARIABILE PER I DATI PRESI DAL DB*/

/*Mi servono quando uso i buttoni del calendario*/
let PRIMO_MESE;
let PRIMO_ANNO;
let ULTIMO_MESE;
let ULTIMO_ANNO;

/*Mi serve in varie funzioni*/
const MESI = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];

function start(){
    /*Bottone per visualizzare calendario o risultati*/
    let mod = document.getElementById("mod");
    mod.addEventListener("click", cambia_contesto);

    /*Bottoni per cambiare mese*/    
    let prec = document.getElementById("precedente");
    prec.addEventListener("click", previousMonth);

    let suc = document.getElementById("successivo");
    suc.addEventListener("click", nextMonth);
}

/*Funzione per visualizzare calendario o risultati*/
function cambia_contesto(){
    let mod = document.getElementById("mod");
    let ris = document.getElementById("elenco_partite");
    let cal = document.getElementById("calendario_partite");
    let p = document.getElementById("error");

    /*Inserisce o togliere l'elemento*/
    if(mod.textContent === "Visualizza risultati"){
        ris.classList.remove("hide");
        cal.classList.add("hide");
        mod.textContent = "Visualizza calendario"
        p.textContent = "";
    } else {
        ris.classList.add("hide");
        cal.classList.remove("hide");
        mod.textContent = "Visualizza risultati"

        //Chiamo la funzione asincrona
        richiesta_dati()
        .then((r) =>{       /*Assegno a DATI il parametro risolto della promessa*/
            let data = r;

            /*Tolgo i match in cui non è stata inserita la data*/
            for (let i = 0; i < data.length; i++) {
                if (data[i].Data !== "0000-00-00 00:00:00") {
                    DATI.push(data[i]);
                }
            }

            if(DATI.length === 0){
                cal.classList.add("hide");
                p.textContent = "Non sono state calendarizzate partite";
            } else {                /*Se sono state calnedarizzate partite*/
                p.textContent = "";

                analisi_dati();
                    
                /*Crea il calendario primo mese*/
                crea_calendario(PRIMO_MESE, PRIMO_ANNO);
            }

        })   
    }
}

function analisi_dati(){
    /*La risposta del DB ha già ordinato le partite*/

    /*Vedo il primo e ultimo mese in cui ci sono eventi*/
    PRIMO_MESE = new Date(DATI[0]['Data']).getMonth();
    PRIMO_ANNO = new Date(DATI[0]['Data']).getFullYear();

    let num_dati = DATI.length - 1;
    ULTIMO_MESE = new Date(DATI[num_dati]['Data']).getMonth();
    ULTIMO_ANNO = new Date(DATI[num_dati]['Data']).getFullYear();
}


function crea_calendario(mese, anno) {
    const giorniDelMese = {0: 31, 1: 28, 2: 31, 3: 30, 4: 31, 5: 30, 6: 31, 7: 31, 8: 30, 9: 31, 10: 30, 11: 31};
    const giorni = ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'];

    /*Restituisce quanti giorni ha il mese*/
    let giorni_mese = giorniDelMese[mese];
    if(bisestile(anno) && giorni_mese === 28){
        giorni_mese++;
    }

    /*Restituisce il giorno della settimana del primo giorno del mese */
    let primo_giorno = new Date(anno, mese, 1).getDay();
    if (primo_giorno === 0) {
        primo_giorno = 7;       /*Voglio la domenica sia 7*/
    }


    let thead = document.getElementById("intestazione");
    let tbody = document.getElementById("calendario");

    
    while (thead.firstChild) {
        thead.removeChild(thead.firstChild);
    }
    while (tbody.firstChild) {
        tbody.removeChild(tbody.firstChild);
    }
    
    
    //Inserisco il titolo del mese
    let riga_mese = document.createElement('tr');
    thead.appendChild(riga_mese);
    let casella_mese = document.createElement('th');
    casella_mese.textContent = MESI[mese] + " " + anno;
    casella_mese.id="attuale";
    casella_mese.setAttribute("colspan", "7");
    riga_mese.appendChild(casella_mese);
    
    //Inserisco i giorni della settimana
    let riga_giorni = document.createElement('tr');
    thead.appendChild(riga_giorni);
    for (let i = 0; i < 7; i++) {
        let giorni_sett = document.createElement('th');
        giorni_sett.textContent = giorni[i];
        riga_giorni.appendChild(giorni_sett);
    }

    let currentDay = 1;                 //contatore giorni mese
    let first_next = 1;                 //contatare giorni mese successivo
    
    let previous_mese = (mese + 11) % 12;
    let giorni_previous_mese = giorniDelMese[previous_mese];  //Numero giorni precedente mese
    if(bisestile(anno) && giorni_mese === 28){
        giorni_mese++;
    }

    let row;
    for (let i = 1; i <= 42; i++) {
        //Primo giorno settimana creo nuova riga
        if (i % 7 === 1) {
            row = document.createElement('tr');
            tbody.appendChild(row);
        }

        let cella = document.createElement('td');
        if (i < primo_giorno) {                     //Controllo se inserire giorni mese precedente
            cella.textContent = giorni_previous_mese - primo_giorno + i + 1;    //Calcola il mese precedente da inserire
            cella.classList.add("altro");
        }else if (currentDay <= giorni_mese) {
            cella.textContent = currentDay;

            /*Controllo se ci sono eventi in questo giorno*/
            let eventi = inserisci_eventi(currentDay, mese, anno);

            if(eventi.length > 0){
                cella.classList.add("evento");          /*Cambio la classe alla cella con i match*/

                //Inserisco il numero di eventi
                let num = document.createElement("p");
                let text = eventi.length + " match";
                num.classList.add("visibile"); 
                num.textContent = text;
                cella.appendChild(num);

                let popup = document.createElement("div");
                popup.classList.add("popup");
                cella.appendChild(popup);
            

                /*Inserisco tutti gli eventi*/
                for(let i=0; i<eventi.length; i++){
                    let match = document.createElement("p");

                    /*Prendo solo l'ora e minuti*/
                    let parti = eventi[i]['Data'].split(" ");
                    let tempo = parti[1].split(":");
                    let ora = tempo[0];
                    let minuti = tempo[1];

                    let text = "(" + ora + ":" + minuti + ") " + eventi[i]['Nome1'] + " VS " + eventi[i]['Nome2'];
                    match.textContent = text;
                    popup.appendChild(match);
                }
            }

            currentDay++;

        } else {
            cella.textContent = first_next;
            first_next++;
            cella.classList.add("altro");
        }

        row.appendChild(cella);
    }

    /*Funzione per controllorare se ci sono eventi nei precedenti o successivi*/
    disabilita_bottoni();
}

/*Controllo gli eventi che ci sono il questo giorno*/
function inserisci_eventi(currentDay, mese, anno){
    let eventi = new Array();

    for(let i=0; i<DATI.length; i++){
        let data_elem = new Date(DATI[i]['Data']);
        let day = data_elem.getDate();
        let month = data_elem.getMonth();
        let year = data_elem.getFullYear();

        if(day === currentDay && month === mese && year === anno){
            eventi.push(DATI[i]);                                   //Lo aggiungo all'array eventi
        } else if(month > mese && year == anno || year > anno) {    //Se vado al mese successivo o anno successivo non c'è puù bisogno di scorrere
            break;
        }
    }

    return eventi;
}


function bisestile(anno) {
    return (anno % 4 == 0 && anno % 100 != 0) || (anno % 400 == 0);     /*Questa è la definizioni di anno bisestile*/
}

function disabilita_bottoni(){
    //Prendo mese e anno corrente
    let current = document.getElementById("attuale").textContent.split(" ");
    let mese_attuale= Number(MESI.indexOf(current[0]));
    let anno_attuale= Number(current[1]);


    /*CONTROLLO BOTTONE SUCCESSIVO*/
    let suc = document.getElementById("successivo");
    if(anno_attuale < ULTIMO_ANNO || mese_attuale < ULTIMO_MESE){
        suc.disabled = false;
        suc.setAttribute("title", "Eventi successivi");
    } else{
        suc.disabled = true;
        suc.setAttribute("title", "Non ci sono eventi successivi");
    }


    /*CONTROLLO BOTTONE PRECEDENTE*/
    let prec = document.getElementById("precedente");
    if(anno_attuale > PRIMO_ANNO || mese_attuale > PRIMO_MESE){
        prec.disabled = false;
        prec.setAttribute("title", "Eventi precedenti");
    } else{
        prec.disabled = true;
        prec.setAttribute("title", "Non ci sono eventi precedenti");
    }
}


/*Creo tabella mese successivo*/
function nextMonth() {
    //Prendo mese e anno corrente
    let current = document.getElementById("attuale").textContent.split(" ");

    let mese_attuale= Number(MESI.indexOf(current[0]));
    let anno_attuale= Number(current[1]);

    if(mese_attuale === 11){
        crea_calendario(0, anno_attuale+1);
    }else{
        crea_calendario(mese_attuale+1, anno_attuale);
    }
}

/*Creo tabella precedente*/
function previousMonth() {
    let current = document.getElementById("attuale").textContent.split(" ");

    let mese_attuale= Number(MESI.indexOf(current[0]));
    let anno_attuale= Number(current[1]);

    if(mese_attuale === 0){
        crea_calendario(11, anno_attuale-1);
    }else{
        crea_calendario(mese_attuale-1, anno_attuale);
    }
}



async function richiesta_dati(){
    const url = '../php/richieste/ric_calendario.php';

    try {
        let r = await fetch(url);
        let d = await r.json();

        if (d.ricerca === false) {
            throw new Error(d.error);
        } else {
            return d.dati;
        }
    } 
    catch (e) {
        let p = document.getElementById("error");
        p.textContent = "Errore caricamente calendario"
    } 
}