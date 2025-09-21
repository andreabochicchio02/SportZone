let INPUT, BUTTON, ERRORS;  // Dichiarazioni delle variabili globali


//In tutte le funzioni il paramentro input indica: 0 => Usenname, 1 => Password

function begin() {
    /*PRENDO GLI ELEMENTI DEL FORM, UTILIZZERO SPESSO QUESTI VARIABILI*/
    INPUT = document.querySelectorAll("input");
    BUTTON = document.getElementById("myButton");
    ERRORS = document.querySelectorAll(".error");

    /*CONTROLLI USERNAME*/
    let stato0 = true;
    const userReg = /^[a-zA-Z0-9]{8,}$/;
    INPUT[0].addEventListener("input", function () {
        if (userReg.test(INPUT[0].value))                       //Abilita bottone
            giusto(0);
            stato0 = true;
            if(stato0 && stato1){ BUTTON.disabled = false;}
    });
    INPUT[0].addEventListener("blur", function () {             //Controllo input
        if(!userReg.test(INPUT[0].value)){                     
            sbagliato(0);
            ERRORS[0].textContent = "Almeno 8 caratteri e nessun simbolo speciale";
            stato0 = false;
            if(!stato0 || !stato1){ BUTTON.disabled = true;}
        }
    });

    /*CONTROLLI PASSWORD*/
    const passReg = /(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/;
    let stato1=true;
    INPUT[1].addEventListener("input", function () {            //Abilita bottone
        if (passReg.test(INPUT[1].value)) {
            giusto(1);
            stato1 = true;
            if(stato0 && stato1){ BUTTON.disabled = false;}
        }
    });
    INPUT[1].addEventListener("blur", function () {             //Controllo input           
        if (!passReg.test(INPUT[1].value)) {
            sbagliato(1);
            ERRORS[1].textContent = "Almeno 8 caratteri con maiuscola, minuscola e numero";
            stato1 = false;
            if(!stato0 || !stato1){ BUTTON.disabled = true;}
        }
    });

    /*MOSTRA PASSWORD*/
    let view = document.getElementById("view_pass");
    view.addEventListener("click", (event) => {
        event.preventDefault();             /*Previene l'azione di default*/
        mostra();
    });


    /*RICHIESTA PHP*/
    BUTTON.addEventListener("click", accesso);
}

/*Funzione utilità in caso di input corretto*/
function giusto(input) {
    INPUT[input].classList.remove("errorstyle");
    ERRORS[input].textContent = "";
    ERRORS[input].className = "error";
}

/*Funzione utilità in caso di input sbagliato*/
function sbagliato(input) {
    INPUT[input].classList.add("errorstyle");
    ERRORS[input].className = "error active";
}

/*Funzione utilità per mostrare o nascondere password*/
function mostra(){
    if(INPUT[1].type === "password"){
        INPUT[1].type = "text";
    }else{
        INPUT[1].type = "password";
    }
}

async function accesso(){
    const ingresso = document.getElementById("ingressi");
    const dati = new FormData(ingresso);

    try { 
        let r = await fetch('../php/richieste/accesso.php', {
            method: 'POST',
            body: dati
        });

        let d = await r.json();

        if (d.login === true) {
            window.location.href = '../php/pannello.php';
        } else {
            throw new Error(d.error);
        }
    } 
    catch (e) { 
        /*Errore server*/
        ERRORS[2].textContent = e.message;
    }
}
