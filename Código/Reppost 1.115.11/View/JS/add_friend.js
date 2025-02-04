$(document).ready(function () {
    $('.addFriendButton').click(function () {
        const button = $(this);
        const form = button.closest('form'); // Selecciona el formulario correcto
        const friendId = button.data('id');  // Obtiene el ID del amigo

        $.ajax({
            type: 'POST',
            url: form.attr('action'), // "add_friend.php"
            data: { my_friend_id: friendId },
            dataType: 'json', // Especificamos JSON
            success: function (response) {
                if (response.status === 'success') {
                    button.closest('.pull-right').html(
                        '<span class="text-success">Amigo agregado</span>'
                    );
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert('Error en la solicitud.');
            }
        });
    });
});
