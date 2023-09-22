<script>
  $(function () {
    cargar_contenido();

    cargar_contenido("Active");
    $(document).on('click', '#active', function () {
      var status = "Active";
      cargar_contenido(status);
    });
    $(document).on('click', '#inactive', function () {
      var status = "Inactive";
      cargar_contenido(status);
    });

    $(document).on('click', '#regMajors', function () {
      $.ajax({
        url: "<?= base_url('Admin_Majors/register_form'); ?>",
        method: "get",
        success: function (response) {
          $(document).find('#mjrContent').empty().append(response);
        }
      });
    });

    $(document).on('click', '.btn_operation', function () {
      var option = $(this).attr('data-opt');
      var code = $(this).attr('data-code');
      $.ajax({
        url: "<?= base_url('Admin_Majors/register_form?option='); ?>" + option + "&code=" + code,
        method: "post",
        success: function (response) {
          $(document).find('#majors_modal').show();
          $(document).find('#mjrContent').empty().append(response);
        }
      });
    });

    $(document).on('submit', '#majors_format', function (event) {
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
        url: "<?= base_url('Admin_Majors/register_major'); ?>",
        method: "post",
        data: data,
        dataType: "json",
        success: function (response) {
          if (response.status == "errores") {
            if (response.errors) {
              $.each(response.errors, function (variable, value) {
                $(document).find('#' + variable).addClass('is-invalid');
                $(document).find('#' + variable).after('<div class="invalid-feedback">' + value + '</div>');
              });
            }
          } else if (response.status == "success") {
            $(document).find('#majors_modal').modal('hide');
            $(document).Toasts('create', {
              title: 'Informaci&oacute;n',
              class: 'bg-success',
              autohide: true,
              delay: 5000,
              body: response.message
            });
            cargar_contenido("Active");
          } else {
            $(document).find('#majors_modal').modal('hide');
            $(document).Toasts('create', {
              title: 'Informaci&oacute;n',
              class: 'bg-danger',
              autohide: true,
              delay: 5000,
              body: response.message
            });
          }
        }
      });
    });

  });

  function cargar_contenido(status) {
    $.ajax({
      url: "<?= base_url('Admin_Majors/showMajors?status='); ?>" + status,
      method: "get",
      success: function (respuesta) {
        $(document).find('#wraper').empty().append(respuesta);
      }
    });
  }
</script>