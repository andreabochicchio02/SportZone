function start(){
    const num_righe = document.getElementById("numero");
    const bottone = document.getElementById("myButton");
    const errore = document.querySelector(".error");

    num_righe.addEventListener("input", function(){
        if(num_righe.value <1){
            errore.textContent = "Input non valido";
            return;
        }
        else{
            errore.textContent = "";
        }

        let ingresso= num_righe.value;
        generaTabella(ingresso);
    });

    bottone.addEventListener("click", function(event){
        event.preventDefault();
        const input = document.getElementsByClassName("casella");
        
        for (let i = 0; i < input.length; i++) {
            const inputValue = input[i].value.trim();               /*Elimina spazi bianchi*/
            if(inputValue === "") {
                errore.textContent = "Non lasciare righe vuote";
                return;
            }
        }
        
        invio();
    });

}


function generaTabella(righe) {
    const tabella = document.getElementById("ingressi");


    while (tabella.firstChild) {
        tabella.removeChild(tabella.firstChild);
    }

    for (let i = 0; i <= righe; i++) {
        const row = document.createElement("tr");
        tabella.appendChild(row);
        const casella = (i===0)? document.createElement("th") : document.createElement("td");
        casella.id = "casella" + i;
        if(i===0){
            casella.textContent="Nome partecipante";
        } else {
            const cella = document.createElement("input");
            cella.setAttribute("class", "casella");
            cella.setAttribute("name", "casella[]");                /*array per prendere i dati in php*/
            casella.appendChild(cella);
        }
        row.appendChild(casella);
    }
}


async function invio(){
    const ingresso = document.getElementById("contenitore");
    const error = document.querySelector(".error");

    const dati = new FormData(ingresso);

    try { 
        let r = await fetch('../php/richieste/ins_partecipanti.php', {
            method: 'POST',
            body: dati
        });

        let d = await r.json();

        if (d.partecipanti === true) {
            window.location.href = '../php/crea_match.php';
        } else {
            throw new Error(d.error);
        }
    } 
    catch (e) { 
        /*Errore server*/
        error.textContent = e.message;
    }
}


