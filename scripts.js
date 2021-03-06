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

function sprawdzenieFormularzaLogowania() {
    var status = true;
    var nazwa = document.forms["formularz_logowanie"]["nazwa"];
    var haslo = document.forms["formularz_logowanie"]["haslo"];
    var codigo = document.forms["formularz_logowanie"]["codigo"];

    if (nazwa.value == null || nazwa.value == "") {
        nazwa.classList.add("czerwona_obwodka");
        alert("Uzpełnij nazwe użytkownika");
        status = false;
    } else {
        nazwa.classList.remove("czerwona_obwodka");
    }

    if (haslo.value == null || haslo.value == "") {
        haslo.classList.add("czerwona_obwodka");
        alert("Uzpełnij hasło");
        status = false;
    } else {
        haslo.classList.remove("czerwona_obwodka");
    }

    if (codigo.value == null || codigo.value == "") {
        codigo.classList.add("czerwona_obwodka");
        alert("Uzpełnij kod z aplikacji");
        status = false;
    } else {
        codigo.classList.remove("czerwona_obwodka");
    }

    return status;
}

function onSignIn(googleUser) {
    // Przykładowe dane które możemy pobrać we frontendzie
    var profile = googleUser.getBasicProfile();
    console.log("ID: " + profile.getId()); // Pod żadnym pozorem nie wysyłany tego do back-endu!
    console.log('Nazwa: ' + profile.getName());
    console.log('Imię: ' + profile.getGivenName());
    console.log('Nazwisko: ' + profile.getFamilyName());
    console.log("Adres URL do zdjęcia profilowego: " + profile.getImageUrl());
    console.log("E-mail: " + profile.getEmail());

    // To w tym miejscu pobieramy id_token:
    var id_token = googleUser.getAuthResponse().id_token;
    console.log("ID Token: " + id_token);

    var data = $.parseJSON(output);

    if (data['status'] == "1") {
        window.location.href = "https://[ścieżka docelowa po zalogowaniu]";
    }
    if (data['status'] == "2") {
        location.reload(); //Nieudane logowanie? Jeszcze raz...
    }

};