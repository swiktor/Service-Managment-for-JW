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