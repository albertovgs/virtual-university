<script>
    $(function () {

        $(document).on('click', '.btn_operation', function () {
            var option = $(this).attr('data-opt');
            var code = $(this).attr('data-code');
            $.ajax({
                url: "<?= base_url('Home/confirmation?option='); ?>" + option + "&code=" + code,
                method: "post",
                success: function (response) {
                    $(document).find('#confirm_modal').show();
                    $(document).find('#cnfContent').empty().append(response);
                }
            });
        });

        $(document).on('submit', '#confirmationProc', function (event) {
            event.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: "<?= base_url('Home/confirmationProc'); ?>",
                method: "post",
                data: data,
                dataType: "json",
                success: function (response) {
                    if (response.status == "error") {
                        if (response.errors) {

                        }
                    } else if (response.status == "success") {
                        $(document).find('#students_modal').modal('hide');
                        $(document).Toasts('create', {
                            title: 'Informaci&oacute;n',
                            class: 'bg-success',
                            autohide: true,
                            delay: 600000,
                            body: response.message
                        });
                        //cargar_contenido("Active");
                        location.reload();
                    }
                }
            });
        });

    });
</script>