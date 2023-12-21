<div class="col-12 col-sm-12">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link getView active" id="custom-tabs-four-home-tab" data-toggle="pill"
                        href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home"
                        aria-selected="true" data-otp="P" data-id="<?= $id; ?>">Periods</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link getView" id="custom-tabs-four-profile-tab" data-toggle="pill"
                        href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile"
                        aria-selected="false" data-otp="G" data-id="<?= $id; ?>">Groups</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link getView" id="custom-tabs-four-messages-tab" data-toggle="pill"
                        href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages"
                        aria-selected="false" data-otp="S" data-id="<?= $id; ?>">Stundents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link getView" id="custom-tabs-four-settings-tab" data-toggle="pill"
                        href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings"
                        aria-selected="false" data-otp="C" data-id="<?= $id; ?>">Classes</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-four-tabContent">

            </div>
            <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel"
                aria-labelledby="custom-tabs-four-profile-tab">

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="major_admin_modal" style="display: none;" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog" id="mjContent">

    </div>
</div>

<script>
    $(function () {
        load_firstab(<?= $id; ?>);
        load_periods(<?= $id; ?>);
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
                    } else if (op == "C") {
                        load_classes(<?= @$id; ?>);
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

        $(document).on('click', '.btn_Gtool', function () {
            event.preventDefault();
            var option = $(this).attr('data-opt');
            var id = $(this).attr('data-id');
            $.ajax({
                url: "<?= base_url('Majors/openGroupsForm?option='); ?>" + option + "&id=" + id,
                method: "post",
                success: function (response) {
                    $(document).find('#major_admin_modal').modal("show");
                    $(document).find("#mjContent").empty().append(response);
                }
            });
        });

        $(document).on('submit', '#Gtool_form', function (event) {
            event.preventDefault();
            var major = $(this).attr('data-id');
            var data = $(this).serialize();
            $(this).find("input").each(function (element) {
                $(this).removeClass("is-invalid");
                $(this).next(".invalid-feedback").remove();
            });
            $(this).find('select').each(function (elemento) {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
            $.ajax({
                url: "<?= base_url('Majors/processGroupsForm?major='); ?>" + major,
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
                        load_groups(<?= @$id ?>, "Active");
                    }
                }
            });
        });

        $(document).on('click', '#regClasses', function () {
            event.preventDefault();
            var id = $(this).attr('data-id');
            $.ajax({
                url: "<?= base_url('Majors/openClassForm?id='); ?>" + id,
                method: "get",
                success: function (response) {
                    $(document).find('#mjContent').empty().append(response);
                }
            });
        });

        $(document).on('submit', '#classes_form', function (event) {
            event.preventDefault();
            var major = $(this).attr('data-id');
            var data = $(this).serialize();
            $(this).find("input").each(function (element) {
                $(this).removeClass("is-invalid");
                $(this).next(".invalid-feedback").remove();
            });
            $(this).find('select').each(function (elemento) {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
            $.ajax({
                url: "<?= base_url('Majors/proccessClassForm?major='); ?>" + major,
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
                        $(document).find('#major_admin_modal').modal('hide');
                        $(document).Toasts('create', {
                            title: 'Informaci&oacute;n',
                            class: 'bg-success',
                            autohide: true,
                            delay: 5000,
                            body: response.message
                        });
                        load_groups(<?= @$id ?>, Active);
                    }
                }
            });
        });

        $(document).on('click', '.btn_Ctool', function () {
            event.preventDefault();
            var id = $(this).attr('data-id');
            var option = $(this).attr('data-opt');
            $.ajax({
                url: "<?= base_url('Majors/openClassForm?id='); ?>" + id + "&option=" + option,
                method: "get",
                success: function (response) {
                    $(document).find('#mjContent').empty().append(response);
                }
            });
        });

        $(document).on('click', '.load_class', function () {
            var status = $(this).attr('data-status');
            var id = $(this).attr('data-id')
            var major = $(this).attr('data-major')
            $.ajax({
                url: "<?= base_url('Majors/sowClasses?status='); ?>" + status + "&id=" + id + "&major=" + major,
                method: "get",
                success: function (respuesta) {
                    $(document).find('#classes_content').empty().append(respuesta);
                    setTimeout(function () {
                        $('#classesTable').DataTable({
                            "ordering": false,
                            "responsive": true,
                            "lengthChange": false,
                            "autoWidth": false,
                            "buttons": ["copy", "csv", "excel", "pdf", "print"]
                        }).buttons().container().appendTo('#classesTable .col-md-6:eq(0)');
                    }, 100);
                }
            });
        });

        $(document).on('submit', '#schedule_form', function (event) {
            event.preventDefault();
            var data = $(this).serialize();
            $(this).find("input").each(function (element) {
                $(this).removeClass("is-invalid");
                $(this).next(".invalid-feedback").remove();
            });
            $(this).find('select').each(function (elemento) {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
            $.ajax({
                url: "<?= base_url('Majors/proccessScheduleForm'); ?>",
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
                        $(document).find('#major_admin_modal').modal('hide');
                        $(document).Toasts('create', {
                            title: 'Informaci&oacute;n',
                            class: 'bg-success',
                            autohide: true,
                            delay: 5000,
                            body: response.message
                        });
                        load_classes(<?= @$id ?>);
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

    function load_classes(id) {
        $.ajax({
            url: "<?= base_url('Majors/sowClasses?id='); ?>" + id,
            method: "get",
            success: function (respuesta) {
                $(document).find('#classes_content').empty().append(respuesta);
                setTimeout(function () {
                    $('#classesTable').DataTable({
                        "ordering": true,
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", "pdf", "print"]
                    }).buttons().container().appendTo('#classesTable .col-md-6:eq(0)');
                }, 100);
            }
        });
    }
</script>