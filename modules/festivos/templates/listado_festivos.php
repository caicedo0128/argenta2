<br/>
<?php
    $dataGrid->displayDataGrid();
?>
<script>
$(document).ready(function() {
    oTable = $('#tableData').dataTable({
        "bJQueryUI": true,
        "sPaginationType": "full_numbers"
    });           
});
