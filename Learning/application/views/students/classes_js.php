<script>
    $(function () {
        load_classworks(<?= $class->id_gpc; ?>);

    });

    function load_classworks(id) {
        $.ajax({
            url: "<?= base_url('Classes/showClsWorks?id='); ?>" + id,
            method: "get",
            success: function (respuesta) {
                $(document).find('#classworks').empty().append(respuesta);
            }
        });
    }

    $(document).on('click', '#clsWork', async function () {
        var cls = $(this).attr('data-cls');
        $.ajax({
            url: "<?= base_url('Classes/openClsForm?cls=') ?>" + cls,
            method: "get",
            success: function (response) {
                $(document).find('#modal_classwork').modal("show");
                $(document).find('#content_modal').empty().append(response);
            }
        });
    });

    $(document).on('click', '#cnf_rate', async function () {
        var rate = $(this).attr('data-id');
        $.ajax({
            url: "<?= base_url('Classes/openCnfRates?rate=') ?>" + rate,
            method: "get",
            success: function (response) {
                $(document).find('#modal_classwork').modal("show");
                $(document).find('#content_modal').empty().append(response);
            }
        });
    });

    $(document).on('click', '.btn_opt', async function () {
        var cls = <?= $class->id_gpc; ?>;
        var opt = $(this).attr('data-opt');
        var wrk = $(this).attr('data-clsw');
        $.ajax({
            url: "<?= base_url('Classes/prcBtn?cls=') ?>" + cls + "&opt=" + opt + "&wrk=" + wrk,
            method: "get",
            success: function (response) {
                $(document).find('#modal_classwork').modal("show");
                $(document).find('#content_modal').empty().append(response);
            }
        });
    });

    $(document).on('submit', '#drop_classwork', function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        var cls = <?= $class->id_gpc; ?>;
        $.ajax({
            url: "<?= base_url('Classes/delClassworsForm?cls='); ?>" + cls,
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
                    $(document).find('#modal_classwork').modal('hide');
                    $(document).Toasts('create', {
                        title: 'Informaci&oacute;n',
                        class: 'bg-success',
                        autohide: true,
                        delay: 600000,
                        body: response.message
                    });
                    load_classworks(<?= $class->id_gpc; ?>);
                }
            }
        });
    });

    $(document).on('submit', '#form_classwork', function (event) {
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
        $(this).find('textarea').each(function (elemento) {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        });
        $.ajax({
            url: "<?= base_url('Classes/proccessClassworsForm'); ?>",
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
                    $(document).find('#modal_classwork').modal('hide');
                    $(document).Toasts('create', {
                        title: 'Informaci&oacute;n',
                        class: 'bg-success',
                        autohide: true,
                        delay: 600000,
                        body: response.message
                    });
                    load_classworks(<?= $class->id_gpc; ?>);
                }
            }
        });
    });

    $(document).on('submit', '#form_ratesConf', function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        $(this).find("input").each(function (element) {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        });
        $.ajax({
            url: "<?= base_url('Classes/proccCnfRates'); ?>",
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
                            delay: 3000,
                            body: response.message
                        });
                    }
                } else if (response.status == "success") {
                    $(document).find('#modal_classwork').modal('hide');
                    $(document).Toasts('create', {
                        title: 'Informaci&oacute;n',
                        class: 'bg-success',
                        autohide: true,
                        delay: 1000,
                        body: response.message
                    });

                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            }
        });
    });

    $(document).on('click', '#clsWorkStd', async function () {
        var grp = <?= $class->id_gpc; ?>;
        var cls = $(this).attr('data-cls');
        var wrk = $(this).attr('data-wrk');
        $.ajax({
            url: "<?= base_url('Classes/DeliverForm?cls=') ?>" + cls + "&grp=" + grp + "&wrk=" + wrk,
            method: "get",
            success: function (response) {
                $(document).find('#content_modal').empty().append(response);
            }
        });
    });

    $(document).on('submit', '#deliver_classwork', function (event) {
        event.preventDefault();
        var data = new FormData($(this)[0]);
        $(this).find("input").each(function (element) {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        });
        $.ajax({
            url: "<?= base_url('Classes/DeliverProccess'); ?>",
            method: "POST",
            contentType: false,
            cache: false,
            processData: false,
            data: data,
            success: function (response) {
                response = JSON.parse(response);
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
                            delay: 3000,
                            body: response.message
                        });
                    }
                } else if (response.status == "success") {
                    $(document).find('#modal_classwork').modal('hide');
                    $(document).Toasts('create', {
                        title: 'Informaci&oacute;n',
                        class: 'bg-success',
                        autohide: true,
                        delay: 1000,
                        body: response.message
                    });

                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            }
        });
    });

    $(document).on('click', '.btn_grade', async function () {
        var clsWrk = $(this).attr('data-clsWrk');
        $.ajax({
            url: "<?= base_url('Classes/gradeForm?clsWrk=') ?>" + clsWrk,
            method: "get",
            success: function (response) {
                $(document).find('#content_modal').empty().append(response);
            }
        });
    });

    $(document).on('submit', '#grade_form', function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        $(this).find("input").each(function (element) {
            $(this).removeClass("is-invalid");
            $(this).next(".invalid-feedback").remove();
        });
        $.ajax({
            url: "<?= base_url('Classes/GradeProccess'); ?>",
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
                            delay: 3000,
                            body: response.message
                        });
                    }
                } else if (response.status == "success") {
                    $(document).find('#modal_classwork').modal('hide');
                    $(document).Toasts('create', {
                        title: 'Informaci&oacute;n',
                        class: 'bg-success',
                        autohide: true,
                        delay: 1000,
                        body: response.message
                    });

                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            }
        });
    });

    $(document).on('click', '.btn_grades', function (event) {
        var opt = $(this).attr('data-opt');
        event.preventDefault();
        $.ajax({
            url: "<?= base_url('Classes/GenGradesPart?opt='); ?>" + opt + "&gpc=" + <?= $class->id_gpc; ?>,
            method: "post",
            success: function (response) {
                if (response.status == "error") {
                    $(document).Toasts('create', {
                        title: 'Informaci&oacute;n',
                        class: 'bg-danger',
                        autohide: true,
                        delay: 3000,
                        body: response.message
                    });
                } else {
                    $(document).Toasts('create', {
                        title: 'Informaci&oacute;n',
                        class: 'bg-success',
                        autohide: true,
                        delay: 1000,
                        body: "The Grades was created successfuly."
                    });
                }
            }
        });
    });

    $(document).on('click', '.btn_grade_cls', function (event) {
        event.preventDefault();
        $.ajax({
            url: "<?= base_url('Classes/GradClass?gpc='); ?>" + <?= $class->id_gpc; ?>,
            method: "post",
            success: function (response) {
                if (response.status == "error") {
                    $(document).Toasts('create', {
                        title: 'Informaci&oacute;n',
                        class: 'bg-danger',
                        autohide: true,
                        delay: 3000,
                        body: response.message
                    });
                } else {
                    $(document).Toasts('create', {
                        title: 'Informaci&oacute;n',
                        class: 'bg-success',
                        autohide: true,
                        delay: 1000,
                        body: "The Grades was created successfuly."
                    });
                }
            }
        });
    });
</script>