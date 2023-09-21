<script>
    $(function () {
        load_periods();
        $(document).on('click', '#regPeriods', function () {
            event.preventDefault();
            $.ajax({
                url: "<?= base_url('Periods/openPrdForm'); ?>",
                method: "get",
                success: function (response) {
                    $(document).find('#mjContent').empty().append(response);
                }
            });
        });

        $(document).on('submit', '#period_form', function (event) {
            event.preventDefault();
            var data = $(this).serialize();
            $(this).find("input").each(function (element) {
                $(this).removeClass("is-invalid");
                $(this).next(".invalid-feedback").remove();
            });
            $.ajax({
                url: "<?= base_url('Periods/proccessPeriodsForm'); ?>",
                method: "post",
                data: data,
                dataType: "json",
                success: function (response) {
                    if (response.status == "error") {
                        if (response.errors) {
                            $.each(response.errors, function (variable, value) {
                                $(document).find('#' + variable).addClass('is-invalid');
                                $(document).find('#' + variable).after('<div class="invalid-feedback">' + value + '</div>');
                            });
                        } else {
                            $(document).Toasts('create', {
                                title: 'Informaci&oacute;n',
                                class: 'bg-danger',
                                autohide: true,
                                delay: 5000,
                                body: response.message
                            });
                        }
                    } else if (response.status == "success") {
                        $(document).find('#major_admin_modal').modal('hide');
                        $(document).Toasts('create', {
                            title: 'Informaci&oacute;n',
                            class: 'bg-success',
                            autohide: true,
                            delay: 5000,
                            body: response.message
                        });
                        load_periods();
                    }
                }
            });
        });
    });

    function load_periods() {
        $.ajax({
            url: "<?= base_url('Periods/showPeriods'); ?>",
            method: "get",
            success: function (respuesta) {
                $(document).find('#periods_content').empty().append(respuesta);
                setTimeout(function () {
                    $('#periodsTable').DataTable({
                        "order": [2, 'desc'],
                        "ordering": false,
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", "pdf", "print"]
                    }).buttons().container().appendTo('#periods_content .col-md-6:eq(0)');
                }, 100);
            }
        });
    }
</script>