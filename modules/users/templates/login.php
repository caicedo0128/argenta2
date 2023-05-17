<script type="text/javascript">

    $(document).ready(function (){  


    });

    function ingresar(isAdmin) {

        validateForm("form_datos_login");
        var msjError = "";

        if ($("#form_datos_login").valid() && msjError == "") {
            
            showLoading("Enviando información. Espere por favor...");
            
            var strUrl = "index.php";
            if (isAdmin)
                strUrl = "admindex.php";
                
            var dataForm = $("#form_datos_login").serialize();
            $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data:
                    {
                        Ajax : "true",
                        mod : "users",
                        action : "loginDo",
                        username : $("#username").val(),
                        pass : $("#pass").val()
                    },
                success: function (response) {
                    closeNotify();
                    if (response.Success == true) {
                        showSuccess("Ingreso exitoso. Espere un momento por favor...");
                        window.setTimeout(function () {
                            if (isAdmin)
                                window.location.href = "admindex.php?login";
                            else
                                window.location.href = "index.php?login";
                        }, 2000)
                    }
                    else {
                        showError("El usuario o contraseña no se encuentran registrados. Verifique");
                    }
                }
            });
        }
        else {
            showError("Por favor revise los campos marcados." + msjError);
        }
    }

    function restablecerContrasena() {

        var usuario = $("#form_datos_login #username").val();
        if (usuario == "")
            showError("Ingrese el usuario para restablecer su contraseña");
        else {

            showLoading("Enviando información. Espere por favor...");
            var strUrl = "admindex.php";
            $.ajax({
                type: 'POST',
                url: strUrl,
                dataType: "json",
                data: {
                	Ajax:true,
                	mod:'users',
                	action: 'rememberPassword',
                    username: usuario
                },
                success: function (response) {
                    closeNotify();
                    if (response.Success) {
                        showSuccess(response.Message);
                    }
                    else
                        showError(response.Message);
                }
            });
        }

    }

</script>

<div class="wrap">
<div class="container">      
    <div class="row">
        <div class="col-md-12">
            <div class="login-panel panel panel-default">
                <div class="panel-body">
                    <a href="admindex.php" lt="Argenta estructuradores"><img src="./images/logo2.png" alt="Argenta estructuradores" style="padding:5px;" class="img-responsive"></a>
                    <div class="">              
                        <div class="logo1">
                               
                        </div>
                    </div>                
                    <form name="form_datos_login" id="form_datos_login" method="post" action="" role="form">
                        <fieldset>
                            <div class="form-group">
                                <input id="username" name="username" type="text" class="form-control required no_mayus" placeholder = "Usuario" autofocus = "autofocus"/>
                            </div>
                            <div class="form-group">
                                <input id="pass" name="pass" type="password" class="form-control required no_mayus" placeholder = "Contraseña" autofocus = "autofocus"/>                                
                            </div>
                            <input type="button" value="Ingresar" class="col-md-12 btn btn-primary" onclick="ingresar(true);">
                        </fieldset>
                    </form>
                </div>
            </div>
            <?php
                if ($mensaje != "")
                    echo "<div id='msjError2' class='alert alert-warning' role='alert'>".$mensaje."</div>";
                    
                if ($_REQUEST["expired"] == "1")
                    echo "<div class='alert alert-warning' role='alert'>Su sesión ha expirado. Por su seguridad esta fue cerrada automàticamente.</div>";                    
            ?>
        </div>
    </div>
</div>
<div class="forgotPassword text-center">
    <div onclick="javascript:restablecerContrasena();" class="restablecer_pass">Restablecer contraseña?</div>
</div>
</div>

