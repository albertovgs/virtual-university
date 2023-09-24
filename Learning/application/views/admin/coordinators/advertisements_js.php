<script>
    $(function () {
        load_content("Major")
        $(document).on('click', '#btn_new_post', function () {
            $.ajax({
                url: "<?= base_url('Posts/open_form'); ?>",
                method: "post",
                success: function (response) {
                    $(document).find('#modal_posts').show();
                    $(document).find("#content_modal").empty().append(response);
                }
            });
        });

        $(document).on('click', '.btn_major', function () {
            var id = $(this).attr('data-id');
            $.ajax({
                url: "<?= base_url('Majors/loadMajorAdmin?id=') ?>" + id,
                method: "post",
                success: function (response) {
                    $(document).find("#major_content").empty().append(response);
                }
            });
        });

        $(document).on('click', '.btn_edit_post', function () {
            var ps = $(this).attr('data-ps');
            var op = $(this).attr('data-opt');
            $.ajax({
                url: "<?= base_url('Posts/open_form?op=') ?>" + op + '&ps=' + ps,
                method: "post",
                success: function (response) {
                    $(document).find('#modal_posts').show();
                    $(document).find("#content_modal").empty().append(response);
                }
            });
        });

        $(document).on('click', '#showAdver', function () {
            var status = "Adver";
            load_content(status);
        });
        $(document).on('click', '#showMaj', function () {
            var status = "Major";
            load_content(status);
        });
        $(document).on('click', '#showReq', function () {
            var status = "Request";
            load_content(status);
        });

        $(document).on('submit', '#form_request', function (event) {
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
                url: "<?= base_url('Posts/request_pro'); ?>",
                method: "POST",
                dataType: "json",
                data: data,
                success: function (response) {
                    if (response.status == "error") {
                        if (response.errors) {
                            $.each(response["errors"], function (variable, value) {
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
                        $(document).find('#req').modal('hide');
                        $(document).Toasts('create', {
                            title: 'Informaci&oacute;n',
                            class: 'bg-success',
                            autohide: true,
                            delay: 5000,
                            body: response.message
                        });
                        setTimeout(function () {
                            load_content("Request");
                        }, 1000);
                    }
                }
            });
        });

        $(document).on('submit', '#form_posts', function (event) {
            event.preventDefault();
            var data = new FormData($(this)[0]);
            $(this).find("input").each(function (element) {
                $(this).removeClass("is-invalid");
                $(this).next(".invalid-feedback").remove();
            });
            $(this).find('select').each(function (elemento) {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            });
            $.ajax({
                url: "<?= base_url('Posts/proccessForm'); ?>",
                method: "POST",
                contentType: false,
                cache: false,
                processData: false,
                data: data,
                success: function (response) {
                    response = JSON.parse(response);
                    if (response.status == "error") {
                        if (response.errors) {
                            $.each(response["errors"], function (variable, value) {
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
                        $(document).find('#modal_posts').modal('hide');
                        $(document).Toasts('create', {
                            title: 'Informaci&oacute;n',
                            class: 'bg-success',
                            autohide: true,
                            delay: 5000,
                            body: response.message
                        });
                        setTimeout(function () {
                            load_content("Adver");
                        }, 1000);
                    }
                }
            });
        });

        $(document).on('click', '.btn_commt', async function () {
            var us = $(this).attr('data-us');
            var ps = $(this).attr('data-ps');
            $.ajax({
                url: "<?= base_url('Posts/openCommentForm?us=') ?>" + us + '&ps=' + ps,
                method: "get",
                success: function (response) {
                    $(document).find('#modal_comment').empty().append(response);
                    $.ajax({
                        url: "<?= base_url('Posts/showComments?ps=') ?>" + ps,
                        method: "get",
                        success: function (respuesta) {
                            $(document).find('#registered_comment').empty().append(respuesta);
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btn_delete_post', function (event) {
            var ps = $(this).attr('data-ps');
            var op = $(this).attr('data-op');
            var itm = $(this).attr('data-item');
            if (op == "delete") {
                var btn = "<button type='button' class='btn btn-tool btn_delete_post' data-ps='" + ps + "' data-op='active' data-item='post' title='Undo'><i class='fas fa-undo'></i></button>";
            } else {
                var btn = ""
            }
            $.ajax({
                url: "<?= base_url('Posts/confirmation?ps=') ?>" + ps + '&op=' + op + "&itm=" + itm,
                method: "post",
                dataType: "json",
                success: function (response) {
                    if (response.status == "success") {
                        $(document).find('#modal_comment').hide('hide');
                        $(document).find('#modal_comment').modal('hide');
                        $(document).Toasts('create', {
                            title: 'Informaci&oacute;n',
                            class: 'bg-success',
                            autohide: true,
                            delay: 50000,
                            body: response.message + btn
                        });
                    }
                    load_content("Adver");
                }
            });
        });

        $(document).on('click', '.btn_cm_operacion', function (event) {
            var ps = $(this).attr('data-ps');
            var op = $(this).attr('data-opt');
            $.ajax({
                url: "<?= base_url('Posts/editComent?ps=') ?>" + ps + '&op=' + op,
                method: "post",
                dataType: "json",
                success: function (response) {
                    if (response) {
                        $(document).find('#registered_comment').empty().append(response);
                    }
                }
            });
        });

        $(document).on('submit', '#form_comments_edit', function (event) {
            event.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: "<?= base_url('posts/proccEditComent') ?>",
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
                        $(document).find('#modal_comment').hide('hide');
                        $(document).find('#modal_comment').modal('hide');
                        $(document).Toasts('create', {
                            title: 'Informaci&oacute;n',
                            class: 'bg-success',
                            autohide: true,
                            delay: 5000,
                            body: "Comment edited sent."
                        });
                    }
                }
            });
        });


        $(document).on('submit', '#form_comments', function (event) {
            event.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url: "<?= base_url('posts/proccessFormComments') ?>",
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
                        $(document).find('#modal_comment').hide('hide');
                        $(document).find('#modal_comment').modal('hide');
                        $(document).Toasts('create', {
                            title: 'Informaci&oacute;n',
                            class: 'bg-success',
                            autohide: true,
                            delay: 5000,
                            body: "Comment sent."
                        });
                    }
                }
            });
        });


    });

    function load_content(status) {
        $.ajax({
            url: "<?= base_url('posts/showContent?status='); ?>" + status,
            method: "get",
            success: function (respuesta) {
                $(document).find('#content').empty().append(respuesta);
            }
        });
    }

    function load_comments() {
        var code_ps = $(this).attr('data-ps');
        $.ajax({
            url: "<?= base_url('comments/mostrarContenido?code_ps=') ?>" + code_ps,
            method: "get",
            success: function (respuesta) {
                $(document).find('#registered_comment').empty().append(respuesta);
            }
        });
    }
</script>