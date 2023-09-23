<script>
  $(function () {

    cargar_contenido("Active");

    $(document).on('click', '#active', function () {
      var status = "Active";
      cargar_contenido(status);
    });

    $(document).on('click', '#inactive', function () {
      var status = "Inactive";
      cargar_contenido(status);
    });

    $(document).on('click', '#regCoordinator', function () {
      $.ajax({
        url: "<?= base_url('Admin_Cordination/processForm'); ?>",
        method: "get",
        success: function (response) {
          $(document).find('#crdContent').empty().append(response);
        }
      });
    });

    $(document).on('click', '.btn_rst_pass', function () {
      var code = $(this).attr('data-code');
      $.ajax({
        url: "<?= base_url('Admin_Students/resetPass?code='); ?>" + code,
        method: "post",
        success: function (response) {
          $(document).find('#coordinator_modal').show();
          $(document).find("#crdContent").empty().append(response);
        }
      });
    });

    $(document).on('submit', '#reset_password', function (event) {
      event.preventDefault();
      var data = $(this).serialize();
      $.ajax({
        url: "<?= base_url('Admin_Students/reset_password'); ?>",
        method: "post",
        data: data,
        dataType: "json",
        success: function (response) {
          if (response.status == "error") {
            if (response.errors) {

            }
          } else if (response.status == "success") {
            $(document).find('#coordinator_modal').modal('hide');
            $(document).Toasts('create', {
              title: 'Informaci&oacute;n',
              class: 'bg-success',
              autohide: true,
              delay: 600000,
              body: response.message
            });
            cargar_contenido("Active");
          }
        }
      });
    });


    $(document).on('click', '.btn_operation', function () {
      var option = $(this).attr('data-opt');
      var code = $(this).attr('data-code');
      $.ajax({
        url: "<?= base_url('Admin_Cordination/processForm?option='); ?>" + option + "&code=" + code,
        method: "post",
        success: function (response) {
          $(document).find('#coordinator_modal').show();
          $(document).find("#crdContent").empty().append(response);
        }
      });
    });

    $(document).on('submit', '#Coordinator_form', function (event) {
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
        url: "<?= base_url('Admin_Cordination/proces_coordinator_form'); ?>",
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
              $(document).find('#coordinator_modal').modal('hide');
              $(document).Toasts('create', {
                title: 'Informaci&oacute;n',
                class: 'bg-danger',
                autohide: true,
                delay: 5000,
                body: response.message
              });
              cargar_contenido("Active");
            }
          } else if (response.status == "success") {
            $(document).find('#coordinator_modal').modal('hide');
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
  });

  function cargar_contenido(status) {
    $.ajax({
      url: "<?= base_url('Admin_Cordination/showTable?status='); ?>" + status,
      method: "get",
      success: function (respuesta) {
        $(document).find('#wraper').empty().append(respuesta);
        setTimeout(function () {
          $('#coordinatorTable').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
          }).buttons().container().appendTo('#wraper .col-md-6:eq(0)');
        }, 100);
      }
    });
  }

</script>