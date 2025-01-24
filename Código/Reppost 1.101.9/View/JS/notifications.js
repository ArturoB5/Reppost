document.addEventListener('DOMContentLoaded', function () {
    const notificationBadge = document.getElementById('notification-badge');
    const notificationList = document.getElementById('notification-list');
    const deleteNotificationsButton = document.getElementById('delete-notifications-button');
    let displayedNotifications = [];

    // Formato para fecha
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
                            const listItem = document.createElement('li');
                            listItem.innerHTML = `
                                <div style="white-space: normal; padding: 10px; cursor: pointer;" class="notification-item" data-link="${notification.link}">
                                    <strong>${notification.message}</strong><br>
                                    <small class="text-muted">${formatDate(notification.date_created)}</small>
                                </div>
                            `;
                            notificationList.appendChild(listItem);
                        });
                        const notificationItems = document.querySelectorAll('.notification-item');
                        notificationItems.forEach(item => {
                            item.addEventListener('click', function () {
                                const link = this.getAttribute('data-link');
                                if (link) {
                                    window.location.href = link;
                                }
                            });
                        });
                    } else {
                        notificationBadge.style.display = 'none';
                        notificationList.innerHTML += '<li class="text-center text-muted">Sin notificaciones</li>';
                    }
                }
            })
            .catch(err => console.error('Error al cargar las notificaciones:', err));
    }
    // Función para resaltar publicaciones si se redirige con el ID
    function highlightPostFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        const postId = urlParams.get('post_id');
        if (postId) {
            const targetPost = document.getElementById('post-' + postId);
            if (targetPost) {
                targetPost.style.transition = 'background-color 0.5s ease';
                targetPost.style.backgroundColor = '#5cb85c8c';
                targetPost.scrollIntoView({ behavior: 'smooth', block: 'center' });
                targetPost.style.boxShadow = '0px 0px 10px 5px rgba(92, 184, 92, 0.5)';
                setTimeout(() => {
                    targetPost.style.boxShadow = '';
                    targetPost.style.backgroundColor = '';
                }, 2000);
            }
        }
    }
    // Función para eliminar notificaciones
    deleteNotificationsButton.addEventListener('click', function () {
        fetch('delete_notifications.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
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
    // Inicializar
    loadNotifications();
    highlightPostFromURL();
    setInterval(loadNotifications, 1000); // Recargar notificaciones cada segundo
});
