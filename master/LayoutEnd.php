    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/respond.js"></script>
    <script src="./js/site.js"></script>
    <script src="./js/utilities.js"></script>
    <script src="./js/jquery.dataTables.min.js"></script>
    <script src="./js/dataTables.bootstrap.js"></script>
    <script src="./js/dataTables.responsive.min.js"></script>
    <script src="./js/jquery.jNotify.js"></script>
    <script src="./js/jquery.mask.min.js"></script>
    <script src="./js/jquery.price_format.min.js"></script>
    <script src="./js/moment.js"></script>
    <script src="./js/bootstrap-datetimepicker.min.js"></script>
    <script src="./js/bootbox.min.js"></script>
    <script src="./js/bootstrap3-typeahead.min.js"></script>
    <script src="./js/TweenMax.min.js"></script>
    <script src="./js/metis_menu.js"></script>
    <script src="./js/jquery.select2.js"></script>
    <script src="./js/jquery.validate.js"></script>
    <script src="./js/html2pdf.bundle.min.js"></script>
    <script src="./js/pivot.js"></script>

    <script type="text/javascript">

        $(document).ready(function () {

                    
            $.ajaxSetup(
            {
                cache:false,
                success: function(data, statusText, jqhxr) {

                },
                fail: function (){
                },
                error: function(){
                    //window.location.href = pathToAction() + "/Error/Error?type=500";
                },
                complete: function() {
                    $("#msj_session").fadeOut(1000);
                    var d = new Date();
                    var timeSession = '30';
                    var DateSession = new Date(d.getFullYear(),d.getMonth(), d.getDate(), d.getHours(),d.getMinutes());
                    utcTimeExpireSession = dateAdd(DateSession, 'minute', timeSession);
                }
            });
            

        });
    </script>
</body>
</html>