// DodajSprawozdanie.php
function czyLiczba(idPola) {
    var liczby = /^[0-9]+$/;
    var poleTestowane = document.getElementById(idPola);
    if (!poleTestowane.value.match(liczby)) {
        alert('Można prowadzić tylko liczby!');
        poleTestowane.focus();
    }
}

function czyZnak(idPola) {
    var litery = /^[AaĄąBbCcĆćDdEeĘęFfGgHhIiJjKkLlŁłMmNnŃńOoÓóPpRrSsŚśTtUuWwYyZzŹźŻż]+$/;
    var poleTestowane = document.getElementById(idPola);
    if (!poleTestowane.value.match(litery)) {
        alert('Można prowadzić tylko litery!');
        poleTestowane.focus();
    }
}

