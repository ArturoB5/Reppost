document.addEventListener('DOMContentLoaded', function () {
    const notificationBadge = document.getElementById('notification-badge');
    const notificationList = document.getElementById('notification-list');
    const deleteNotificationsButton = document.getElementById('delete-notifications-button');
    let displayedNotifications = [];
    // Función para formatear la fecha
    function formatDate(date) {
        const d = new Date(date);
        const hours = String(d.getHours()).padStart(2, '0');
        const minutes = String(d.getMinutes()).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        return `${hours}:${minutes} - ${day}/${month}/${year}`;
    }
    // Función para cargar notificaciones
    function loadNotifications() {
        fetch('get_notifications.php')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const notifications = data.notifications;
                    notificationList.innerHTML = '<li class="dropdown-header">Notificaciones</li><li class="divider"></li>';
                    if (notifications.length > 0) {
                        notificationBadge.style.display = 'inline-block';
                        notificationBadge.textContent = notifications.length;
                        notifications.forEach(notification => {
                            if (!displayedNotifications.includes(notification.id)) {
                                displayedNotifications.push(notification.id);
                            }
                            const listItem = document.createElement('li');
                            listItem.innerHTML = `
                                <a href="message.php" style="white-space: normal; padding: 10px;">
                                    <strong>${notification.message}</strong><br>
                                    <small class="text-muted">${formatDate(notification.date_created)}</small>
                                </a>
                            `;
                            notificationList.appendChild(listItem);
                        });
                    } else {
                        notificationBadge.style.display = 'none';
                        notificationList.innerHTML += '<li class="text-center text-muted">Sin notificaciones</li>';
                    }
                }
            })
            .catch(err => console.error('Error al cargar las notificaciones:', err));
    }
    // Función para eliminar notificaciones
    deleteNotificationsButton.addEventListener('click', function () {
        fetch('delete_notifications.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Vaciar la lista de notificaciones y ocultar el badge
                    notificationList.innerHTML = '<li class="dropdown-header">Notificaciones</li><li class="divider"></li><li class="text-center text-muted">Sin notificaciones</li>';
                    notificationBadge.style.display = 'none';
                    displayedNotifications = [];
                    alert(data.message);
                } else {
                    alert('Error al eliminar las notificaciones: ' + data.message);
                }
            })
            .catch(err => console.error('Error al eliminar las notificaciones:', err));
    });
    // Cargar las notificaciones al iniciar
    loadNotifications();
    // Recargar cada segundo
    setInterval(loadNotifications, 1000);
});