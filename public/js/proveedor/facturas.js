function cancelCreateInvoiceData() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se borrarán los datos capturados en esta ventana",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Regresar',
        confirmButtonText: 'Aceptar'
      }).then((result) => {
        if(result.isConfirmed) {
            bootstrap.Modal.getInstance(document.getElementById("registerModal")).hide();

            Swal.fire(
                '¡Operación cancelada!',
                'No se ha realizado ninguna acción.',
                'success'
            )

            $("#password").val('');
            $("#confirm_password").val('');

            $("#pdf_input").val('');
            $("#xml_input").val('');
            $("#other_input").val('');
        }
    });
}