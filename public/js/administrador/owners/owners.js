function deleteOwner(btn) {
    let btn_id = btn.id;
    let id = btn_id.substring(7);
    
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Se eliminará esta empresa',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if(result.isConfirmed) {
            window.location.href = './delete/' + id;
        }
    })
}