<script>
    $(function () {
        load_firstab(<?= $id; ?>);
        load_periods(<?= @$id; ?>);
        $(document).on('click', '.getView', function () {
            var op = $(this).attr('data-otp');
            var id = $(this).attr('data-id')
            $.ajax({
                url: "<?= base_url('Majors/loadTabs?op='); ?>" + op + "&id=" + id,
                method: "get",
                success: function (response) {
                    $(document).find('#custom-tabs-four-tabContent').empty().append(response);
                    if (op == "P") {
                        load_periods(<?= @$id; ?>);
                    } else if (op == "S") {
                        cargar_contenido("Active", <?= @$id; ?>);
                    } else if (op == "G") {
                        load_groups(<?= @$id; ?>, "Active");
                    }
                }
            });
        });
        $(document).on('click', '#regPeriods', function () {
            event.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                url: "<?= base_url('Majors/openPrdForm?id='); ?>" + id,
                method: "get",
                success: function (response) {
                    $(document).find('#mjContent').empty().append(response);
                }
            });
        });

        $(document).on('submit', '#period_form', function (event) {
            event.preventDefault();
            var id = $(this).attr('data-id');
            var data = $(this).serialize();
            //console.log(data);
            $(this).find("input").each(function (element) {
                $(this).removeClass("is-invalid");
                $(this).next(".invalid-feedback").remove();
            });
            $.ajax({
                url: "<?= base_url('Majors/proccessPeriodsForm'); ?>",
                method: "post",
                data: data,
                dataType: "json",
                success: function (response) {
                    //console.info(response);
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
                        load_periods(id);
                    }
                }
            });
        });

        $(document).on('click', '#active', function () {
            event.preventDefault();
            var status = "Active";
            cargar_contenido(status);
        });
        $(document).on('click', '#inactive', function () {
            event.preventDefault();
            var status = "Inactive";
            cargar_contenido(status);
        });

        $(document).on('click', '#regStudents', function () {
            var major = $(this).attr('data-id');
            event.preventDefault();
            $.ajax({
                url: "<?= base_url('Admin_Students/processForm?major='); ?>" + major,
                method: "get",
                success: function (response) {
                    $(document).find('#stuContent').empty().append(response);
                }
            });
        });

        $(document).on('click', '.btn_operation', function () {
            event.preventDefault();
            var option = $(this).attr('data-opt');
            var code = $(this).attr('data-code');
            $.ajax({
                url: "<?= base_url('Admin_Students/processForm?option='); ?>" + option + "&code=" + code,
                method: "post",
                success: function (response) {
                    $(document).find('#students_modal').show();
                    $(document).find("#stuContent").empty().append(response);
                }
            });
        });


        $(document).on('submit', '#students_form', function (event) {
            event.preventDefault();
            var major = $("#students_form").attr('data-id');
            var data = $(this).serialize();
            console.log(data);
            $(this).find("input").each(function (element) {
                $(this).removeClass("is-invalid");
                $(this).next(".invalid-feedback").remove();
            });
            $(this).find('select').each(function (elemento) {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
            $.ajax({
                url: "<?= base_url('Admin_Students/proces_students_form?major='); ?>" + major,
                method: "post",
                data: data,
                dataType: "json",
                success: function (response) {
                    //console.info(response);
                    if (response.status == "error") {
                        if (response.errors) {
                            $.each(response.errors, function (variable, value) {
                                $(document).find('#' + variable).addClass('is-invalid');
                                $(document).find('#' + variable).after('<div class="invalid-feedback">' + value + '</div>');
                            });
                        } else if (response.message) {
                            $(document).Toasts('create', {
                                title: 'Informaci&oacute;n',
                                class: 'bg-danger',
                                autohide: true,
                                delay: 5000,
                                body: response.message
                            });
                        }
                    } else if (response.status == "success") {
                        $(document).find('#students_modal').modal('hide');
                        $(document).Toasts('create', {
                            title: 'Informaci&oacute;n',
                            class: 'bg-success',
                            autohide: true,
                            delay: 5000,
                            body: response.message
                        });
                        cargar_contenido("Active");
                    }
                }
            });
        });

        $(document).on("submit", "#profesors_react_cancel_form", function (event) {
            event.preventDefault();
            var data = $(this).serialize();
            $(this).find("input").each(function (element) {
                $(this).removeClass("is-invalid");
                $(this).next(".invalid-feedback").remove();
            });
            $(this).find('textarea').each(function (elemento) {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
            $.ajax({
                url: "<?= base_url('Admin_Students/optionsProccess'); ?>",
                method: "post",
                data: data,
                dataType: "json",
                success: function (response) {
                    //console.info(response);
                    if (response.status == "error") {
                        if (response.errors) {
                            $.each(response.errors, function (variable, value) {
                                $(document).find('#' + variable).addClass('is-invalid');
                                $(document).find('#' + variable).after('<div class="invalid-feedback">' + value + '</div>');
                            });
                        }
                    } else if (response.status == "success") {
                        $(document).find('#students_modal').modal('hide');
                        $(document).Toasts('create', {
                            title: 'Informaci&oacute;n',
                            class: 'bg-success',
                            autohide: true,
                            delay: 5000,
                            body: response.message
                        });
                        cargar_contenido("Active");
                    }
                }
            });
        });

        $(document).on('click', '#regGroups', function () {
            event.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                url: "<?= base_url('Majors/openGroupForm?id='); ?>" + id,
                method: "get",
                success: function (response) {
                    $(document).find('#mjContent').empty().append(response);
                }
            });
        });

        $(document).on("submit", "#group_form", function () {
            event.preventDefault();
            var id = $(this).attr('data-id');
            var data = $(this).serialize();
            $.ajax({
                url: "<?= base_url('Majors/openGroupForm?id='); ?>" + id,
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
                        load_groups(id, "Active");
                    }
                }
            });
        });
    });

    function load_firstab(id) {
        //var id = $(document).find("data-id").val()
        $.ajax({
            url: "<?= base_url('Majors/loadTabs?op='); ?>" + "P" + "&id=" + id,
            method: "get",
            success: function (response) {
                $(document).find('#custom-tabs-four-tabContent').empty().append(response);
            }
        });
    }

    function load_periods(id) {
        $.ajax({
            url: "<?= base_url('Majors/showPeriods?id='); ?>" + id,
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

    function cargar_contenido(status, id = <?= @$id; ?>) {
        $.ajax({
            url: "<?= base_url('Admin_Students/showTable?status='); ?>" + status + "&id=" + id,
            method: "get",
            success: function (respuesta) {
                //console.log(respuesta);
                $(document).find('#wraper').empty().append(respuesta);
                setTimeout(function () {
                    $('#studentsTable').DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#wraper .col-md-6:eq(0)');
                }, 100);
            }
        });
    }


    function load_groups(id, status) {
        $.ajax({
            url: "<?= base_url('Majors/showGroups?id='); ?>" + id + "&status=" + status,
            method: "get",
            success: function (respuesta) {
                $(document).find('#groups_content').empty().append(respuesta);
                setTimeout(function () {
                    $('#groupsTable').DataTable({
                        "order": [2, 'desc'],
                        "ordering": false,
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", "pdf", "print"]
                    }).buttons().container().appendTo('#groups_content .col-md-6:eq(0)');
                }, 100);
            }
        });
    }
</script>