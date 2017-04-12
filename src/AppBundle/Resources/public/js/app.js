/**
 * Created by matias on 19/4/16.
 */
$(document).ready(function () {
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        language: "es",
        autoclose: true,
    });
    $('.datetimepicker').datetimepicker({
        locale: 'es'
    });
})