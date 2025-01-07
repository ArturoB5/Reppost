$(document).ready(function () {
    $('.addFriendButton').click(function () {
        const button = $(this); // Referencia al botón que se hizo clic
        const form = $('#addFriendForm');
        const friendId = button.data('id'); // Obtener el friend_id del atributo data-id

        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: { my_friend_id: friendId },
            success: function (response) {
                if (response.status === 'success') {
                    $('#friendMessage').text(response.message).css('color', 'green');
                    button.closest('.pull-right').html('<span class="text-success">Amigo agregado</span>'); // Ocultar el botón y mostrar mensaje
                } else {
                    $('#friendMessage').text(response.message).css('color', 'red');
                }
            },
            error: function () {
                $('#friendMessage').text('Error en la solicitud').css('color', 'red');
            }
        });
    });
});
