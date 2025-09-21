let INPUT, BUTTON, ERRORS;  // Dichiarazioni delle variabili globali
let STATO = [true, true, true, true];       /*Tiene traccia della validità degli input*/

function begin() {

    /*PRENDO GLI ELEMENTI DEL FORM*/
    INPUT = document.querySelectorAll("input");
    BUTTON = document.getElementById("myButton");
    ERRORS = document.querySelectorAll(".error");

    /*PER IDENTIFICARE I VARI INPUT*/
    const [user, mail, pass, conf_pass] = [0, 1, 2, 3];

    /*CONTROLLI USERNAME*/
    const userReg = /^[a-zA-Z0-9]{8,}$/;
    INPUT[user].addEventListener("input", function () {
        if (userReg.test(INPUT[user].value))
            giusto(user);
    });
    INPUT[user].addEventListener("blur", function () {
        if(!userReg.test(INPUT[user].value)){
            sbagliato(user);
            ERRORS[user].textContent = "Almeno 8 caratteri e nessun simbolo speciale";
        }
    });


    /*CONTROLLI EMAIL*/
    const mailReg = /^(.+)@([^\.].*)\.([a-z]{2,})$/;
    INPUT[mail].addEventListener("input", function () {
        if (mailReg.test(INPUT[mail].value)) {
            giusto(mail);
        }
    });
    INPUT[mail].addEventListener("blur", function () {
        if(!mailReg.test(INPUT[mail].value)){
            sbagliato(mail);
            ERRORS[mail].textContent = "Email non valida";
        }
    });



    /*CONTROLLI PASSWORD*/
    const passReg = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/;
    INPUT[pass].addEventListener("input", function () {
        if (passReg.test(INPUT[pass].value)) {
            giusto(pass);
        }
    });
    INPUT[pass].addEventListener("blur", function () {
        if (!passReg.test(INPUT[pass].value)) {
            sbagliato(pass);
            ERRORS[pass].textContent = "Almeno 8 caratteri con maiuscola, minuscola e numero";
        }
    });


    /*CONTROLLI CONFERMA PASSWORD*/
    INPUT[conf_pass].addEventListener("input", function () {
        if (INPUT[pass].value === INPUT[conf_pass].value) {
            giusto(conf_pass);
        }
    });
    INPUT[conf_pass].addEventListener("blur", function () {
        if(INPUT[pass].value !== INPUT[conf_pass].value){
            sbagliato(conf_pass);
            ERRORS[conf_pass].textContent = "Le password non corrispondono";
        }
    });


    /*MOSTRA PASSWORD*/
    let view = document.getElementById("view_pass");
    view.addEventListener("click", (event) => {
        event.preventDefault();         /*Previene l'azione di default*/
        mostra(pass);
    });

    /*MOSTRA CONFERMA PASSWORD*/
    let view_conf = document.getElementById("view_conf");
    view_conf.addEventListener("click", (event) => {
        event.preventDefault();         /*Previene l'azione di default*/
        mostra(conf_pass);
    });


    /*RICHIESTA PHP*/
    BUTTON.addEventListener("click", registrazione);
}


/*Funzione utilità in caso di input corretto*/
function giusto(input) {
    INPUT[input].classList.remove("errorstyle");
    ERRORS[input].textContent = "";
    ERRORS[input].className = "error";

    STATO[input] = true;

    /*Se STATO è tutto true abilita bottone*/
    let tutti_true = true;
    for (let i = 0; i < STATO.length; i++) {
        if (!STATO[i]) {
            tutti_true = false;
            break;
        }
    }
    if(tutti_true) BUTTON.disabled=false;
}

/*Funzione utilità in caso di input sbagliato*/
function sbagliato(input) {
    INPUT[input].classList.add("errorstyle");
    ERRORS[input].className = "error active";
    STATO[input] = false;

    /*Se almeno uno STATO è false disabilita bottone*/
    for (let i = 0; i < STATO.length; i++) {
        if (!STATO[i]) {
            BUTTON.disabled=true
            break;
        }
    }
}

/*Funzione utilità per mostrare o nascondere password*/
function mostra(select){
    if(INPUT[select].type === "password"){
        INPUT[select].type = "text";
    }else{
        INPUT[select].type = "password";
    }
}


/*Richiesta*/
async function registrazione(){
    const ingresso = document.getElementById("container");
    const dati = new FormData(ingresso);
    
    try { 
        let r = await fetch('../php/richieste/registrazione.php', {
            method: 'POST',
            body: dati,
        });

        let d = await r.json();

        if (d.register === true) {
            window.location.href = '../php/pannello.php';
        } else {
            throw new Error(d.error);
        }
    } 
    catch (e) { 
        /*Errore server*/
        ERRORS[4].textContent = e.message;
    }
}
