function logout(isAdmin) {

		showLoading("Terminando su sesi�n. Espere por favor...");

		var strUrl = "index.php";
		if (isAdmin)
			strUrl = "admindex.php";

		$.ajax({
				type: 'POST',
				url: strUrl,
				dataType: "json",
				data:
					{
						Ajax : "true",
						mod : "users",
						action : "logout"
					},
				success: function (response) {
					showSuccess("Sesi�n terminada. Espere por favor...");
					if (response.Success) {
						if (isAdmin)
							window.location.href = "admindex.php?logout";
						else
							window.location.href = "index.php?logout";
					}
				}
		});
}

function cargarSelect(url, id, idSelect, value, param1) {

    var val = typeof (value) != 'undefined' ? value : -1;
    var param1Val = typeof (param1) != 'undefined' ? param1 : -1;

    $("#" + idSelect).empty();
    $("#" + idSelect).append("<option value = '' >Seleccione uno...</option>");

    if (id != "" && id != 0) {
        $.ajax({
            type: 'POST',
            url: 'admindex.php?Ajax=true&' + url,
            data:
            {
                id: id,
                param1: param1Val
            },
            dataType: 'json',
            success: function (result) {
                $.each(result, function (i, item) {
                    if (val == item.Value)
                        $("#" + idSelect).append("<option value = '" + item.Value + "' selected='selected'>" + item.Text + "</option>");
                    else
                        $("#" + idSelect).append("<option value = '" + item.Value + "' >" + item.Text + "</option>");
                });
            }
        });
    }
}

function obtenerValorRelacionado(url, id, objeto) {
    $.ajax({
        type: 'POST',
        dataType: "json",
        url: pathToAction() + '/' + url,
        data:
        {
            id: id
        },
        success: function (response) {
            $("#" + objeto).html(response.Value);
        }
    });
}

function autocompleteCustom(url, idObj, multiple) {

    var jsonData = [];
    $.getJSON(pathToAction() + url, function (data) {
        jsonData = data;

        var $input = $("#id_entidad");

        $input.typeahead({
            source: jsonData,
            autoSelect: true
        });

        $input.change(function () {
            var current = $input.typeahead("getActive");
            if (current) {

                var uid = current.uid;
                var id = current.id;
                var value = current.name;

                // Some item from your model is active!
                if (current.name == $input.val()) {

                    setTimeout(function () {

                        if (multiple) {
                            var itemsSeleccionados = $("#" + idObj + "_values").val();

                            //VALIDAMOS SI YA ESTA SELECCIONADO EL VALOR PARA NO INGRESARLO
                            if (itemsSeleccionados.search("," + id + ",") == -1) {

                                $("#" + idObj + "_values").val(itemsSeleccionados + id + ",");
                                $("#" + idObj).val("");
                                var dataSelected = "<div id='elemento_" + uid + "'>" + value + " &nbsp;&nbsp;<a href=\"javascript:quitarElemento('" + idObj + "','" + id + "', '" + uid + "');\" class='eliminar_reg'><i class='fa fa-times-circle fa-lg'></i></a></div>";
                                $("#" + idObj + "_text").append(dataSelected);
                            }
                            else {
                                $("#" + idObj).val("");
                            }
                        }
                        else {
                            $("#" + idObj + "_values").val(id);
                            $("#" + idObj).val(value);
                        }

                    }, 2);


                } else {
                    // This means it is only a partial match, you can either add a new item
                    // or take the active if you don't want new items
                }
            } else {
                // Nothing is active so it is a new value (or maybe empty value)
            }
        });
    });
}


function quitarElemento(idObj, id, uid) {

    $("#elemento_" + uid).remove();
    var itemsSeleccionados = $("#" + idObj + "_values").val();
    $("#" + idObj + "_values").val(itemsSeleccionados.replace(id + ",", ""));

}

function exportarExcel(objetoTableHtml, tituloReporte) {

	var oSettings = oTableExport.fnSettings();
    var base = oSettings._iDisplayLength;
    oSettings._iDisplayLength = -1;
    oTableExport.fnDraw();
    window.setTimeout(function () {

        var dataExcel = $("#" + objetoTableHtml).html();


        $("#__dataReporte").val(dataExcel);
        $("#__tituloReporte").val(tituloReporte);

        $("#formExport").submit();

        oSettings._iDisplayLength = base;
        oTableExport.fnDraw();

    }, 500);

}

function exportarExcelWithoutColumn(objetoTableHtml, tituloReporte, column) {

    var oSettings = oTableExport.fnSettings();
    var base = oSettings._iDisplayLength;
    oSettings._iDisplayLength = -1;

    if (column == 1 || column == 2)
    	oTableExport.fnSetColumnVis(0, false);

    if (column == 2)
    	oTableExport.fnSetColumnVis(1, false);

    oTableExport.fnDraw();

    window.setTimeout(function () {

        var dataExcel = $("#" + objetoTableHtml).html();

        $("#__dataReporte").val(dataExcel);
        $("#__tituloReporte").val(tituloReporte);
        $("#formExport").submit();

        oSettings._iDisplayLength = base;
        if (column == 1 || column == 2)
        	oTableExport.fnSetColumnVis(0, true);

	    if (column == 2)
	        oTableExport.fnSetColumnVis(1, true);

        oTableExport.fnDraw();

    }, 500);

}

function exportarExcelFromDiv(objetoDivHtml, tituloReporte) {

	loader();
    window.setTimeout(function () {

        var dataExcel = $("#" + objetoDivHtml).html();

        $("#__dataReporte").val(dataExcel);
        $("#__tituloReporte").val(tituloReporte);
        //$("#formExport input[name=action]").val("exportarExcelFromDiv");
        $("#formExport").submit();
		loader();
    }, 500);
}

function loader() {
    $("#text-loader").toggle();
    $('.overlayCustom').toggle();
}

function goObjHtml(obj, height, time){

	time = time || 0;
	window.setTimeout(function(){
		$('html, body').animate({
		scrollTop: $("#" + obj).offset().top - height
		}, 100);
	}, time);
}
