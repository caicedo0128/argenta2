var segundosID = null;
var idIntervalSession = null;
var idActivarSessionAuto = null;

$(document).ready(function () {
    idIntervalSession = window.setInterval("validarInactividad()", 60003);
});

function genEncryptCode(){

	var tokenService = window.btoa(tokenUser + ":" + tokenClient);
	return tokenService;
}

function validarInactividad() {
    var now = new Date();
    var minutesDiff = DateDiff.inMinutes(now, utcTimeExpireSession);

    if (minutesDiff <= 0) {
        window.clearInterval(idIntervalSession);
        window.clearTimeout(segundosID);
        $("#msj_session").fadeOut(500, function () {
            showLoading("Cerrando su sesión. Espere por favor...");
            var stUrl = 'admindex.php?Ajax=true&mod=users&action=logout&expired=true';
            $.ajax({
                type: 'POST',
                data: {},
                url: stUrl,
                dataType: "json",
                success: function (response) {
                    if (response.Success) {
                        window.location.href = 'admindex.php?expired=1';
                    }
                }
            });
        });
    }

    if (minutesDiff == 1) {
        $("#segundos").text(60);
        $("#msj_session").fadeIn(1000);
        mostrarSegundosInactividad();
    }

}

function activarSession(general) {

    if (general == 0)
        window.clearTimeout(segundosID);

    var stUrl = 'admindex.php?Ajax=true&mod=users&action=actualizarSession';
    $.ajax({
        type: 'POST',
        dataType: "json",
        data: {},
        url: stUrl,
        success: function (response) {
            if (response.Success) {
                utcTimeExpireSession = new Date(response.anio, (response.mes - 1), response.dia, response.hora, response.minutos);
                $("#segundos").text(60);
                $("#msj_session").fadeOut(2000);
            }
        }
    });
}

function mostrarSegundosInactividad() {

    var segundos = $("#segundos");

    if (parseInt(segundos.text()) <= 0) {
        window.clearTimeout(segundosID);
        showLoading("Cerrando su sesión. Espere por favor...");
        $("#msj_session").fadeOut(500, function () {
            var stUrl = 'admindex.php?Ajax=true&mod=users&action=logout&expired=true';
            $.ajax({
                type: 'POST',
                dataType: "json",
                data: {},
                url: stUrl,
                success: function (response) {
                    if (response.Success) {
                        window.location.href = 'admindex.php?expired=1';
                    }
                }
            });
        });
    }
    else
        segundos.text(parseInt(segundos.text()) - 1);

    segundosID = window.setTimeout("mostrarSegundosInactividad()", 1000);
    return true;
}


