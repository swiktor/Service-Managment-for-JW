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

function obecnaDataGodzina() {
    var tzoffset = (new Date()).getTimezoneOffset() * 60000;
    var localISOTime = (new Date(Date.now() - tzoffset)).toISOString().slice(0, -1);
    var localISOTimeWithoutSeconds = localISOTime.slice(0, 16);
    document.getElementById("kiedy_sluzba_od").value = localISOTimeWithoutSeconds;
}

function sprawdzenieFormularzaDodajOsobe() {
    var status = true;
    var nazwisko = document.forms["dodajOsobe"]["nazwisko"];
    var imie = document.forms["dodajOsobe"]["imie"];
    if (nazwisko.value == null || nazwisko.value == "", imie.value == null || imie.value == "") {
        alert("Uzpełnij wszystkie pola");
        status = false;
    } else {
        var litery = /^[AaĄąBbCcĆćDdEeĘęFfGgHhIiJjKkLlŁłMmNnŃńOoÓóPpRrSsŚśTtUuWwYyZzŹźŻż]+$/;

        if (!nazwisko.value.match(litery)) {
            alert("Nazwisko może skaładać sie tylko z liter");
            status = false;
        }

        if (!imie.value.match(litery)) {
            alert("Imię może skaładać sie tylko z liter");
            status = false;
        }
    }
    return status;
}