document.addEventListener('DOMContentLoaded', function () {
    const notificationBadge = document.getElementById('notification-badge');
    const notificationsDropdown = document.getElementById('notifications-dropdown');

    // Simula la carga inicial de notificaciones
    function loadNotifications() {
        // Este ejemplo usa datos simulados. Integra tu backend aquí.
        const notifications = [
            { text: "Tienes un nuevo amigo", link: "friends.php" },
            { text: "Tu publicación recibió un Like", link: "post.php?id=123" },
            { text: "Tienes un nuevo mensaje"}
        ];

        // Limpia las notificaciones actuales
        const dropdownItems = notificationsDropdown.querySelectorAll('li:not(.dropdown-header)');
        dropdownItems.forEach(item => item.remove());

        // Agrega nuevas notificaciones
        if (notifications.length > 0) {
            notifications.forEach(notification => {
                const listItem = document.createElement('li');
                const link = document.createElement('a');
                link.href = notification.link;
                link.textContent = notification.text;
                listItem.appendChild(link);
                notificationsDropdown.appendChild(listItem);
            });
            notificationBadge.textContent = notifications.length;
            notificationBadge.style.display = 'block';
        } else {
            const emptyItem = document.createElement('li');
            emptyItem.innerHTML = '<a href="#" class="text-muted text-center">No tienes notificaciones nuevas</a>';
            notificationsDropdown.appendChild(emptyItem);
            notificationBadge.style.display = 'none';
        }
    }
    // Cargar notificaciones al inicio
    loadNotifications();
});
