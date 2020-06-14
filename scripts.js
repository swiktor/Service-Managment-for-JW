// DodajSprawozdanie.php

var i = 0;

function czyLiczba(idPola) {
    var liczby = /^[0-9]+$/;
    var poleTestowane = document.getElementById(idPola);

    if (!poleTestowane.value.match(liczby)) {
        poleTestowane.classList.remove("zielona_obwodka");
        poleTestowane.classList.add("czerwona_obwodka");
        poleTestowane.focus();
    } else {
        poleTestowane.classList.remove("czerwona_obwodka");
        poleTestowane.classList.add("zielona_obwodka");
    }

}

function czyZnak(idPola) {
    var litery = /^[AaĄąBbCcĆćDdEeĘęFfGgHhIiJjKkLlŁłMmNnŃńOoÓóPpRrSsŚśTtUuWwYyZzŹźŻż]+$/;
    var poleTestowane = document.getElementById(idPola);
    if (!poleTestowane.value.match(litery)) {
        poleTestowane.classList.remove("zielona_obwodka");
        poleTestowane.classList.add("czerwona_obwodka");
        poleTestowane.focus();
    } else {
        poleTestowane.classList.remove("czerwona_obwodka");
        poleTestowane.classList.add("zielona_obwodka");
    }
}

function obecnaDataGodzina() {
    var tzoffset = (new Date()).getTimezoneOffset() * 60000; //offset in milliseconds
    var localISOTime = (new Date(Date.now() - tzoffset)).toISOString().slice(0, -1);
    var localISOTimeWithoutSeconds = localISOTime.slice(0, 16);
    document.getElementById("kiedy_sluzba_od").value = localISOTimeWithoutSeconds;
}