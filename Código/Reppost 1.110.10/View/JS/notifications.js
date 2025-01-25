document.addEventListener("DOMContentLoaded", function () {
  const notificationBadge = document.getElementById("notification-badge");
  const notificationList = document.getElementById("notification-list");
  let displayedNotifications = [];
  function formatDate(date) {
    const d = new Date(date);
    const hours = String(d.getHours()).padStart(2, "0");
    const minutes = String(d.getMinutes()).padStart(2, "0");
    const day = String(d.getDate()).padStart(2, "0");
    const month = String(d.getMonth() + 1).padStart(2, "0");
    const year = d.getFullYear();
    return `${hours}:${minutes} - ${day}/${month}/${year}`;
  }
  // Función para cargar notificaciones
  function loadNotifications() {
    fetch("get_notifications.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          const notifications = data.notifications;
          notificationList.innerHTML =
            '<li class="dropdown-header">Notificaciones</li><li class="divider"></li>';
          if (notifications.length > 0) {
            notificationBadge.style.display = "inline-block";
            notificationBadge.textContent = notifications.length;
            notifications.forEach((notification) => {
              const listItem = document.createElement("li");
              listItem.innerHTML = `
                                <div style="white-space: normal; padding: 10px; cursor: pointer;" class="notification-item" data-id="${
                                  notification.notification_id
                                }" data-link="${notification.link}">
                                    <strong>${notification.message}</strong><br>
                                    <small class="text-muted">${formatDate(
                                      notification.date_created
                                    )}</small>
                                </div>
                            `;
              notificationList.appendChild(listItem);
            });
            attachClickListeners();
          } else {
            notificationBadge.style.display = "none";
            notificationList.innerHTML +=
              '<li class="text-center text-muted">Sin notificaciones</li>';
          }
        }
      })
      .catch((err) =>
        console.error("Error al cargar las notificaciones:", err)
      );
  }
  // Función para manejar clics en notificaciones
  function attachClickListeners() {
    const notificationItems = document.querySelectorAll(".notification-item");
    notificationItems.forEach((item) => {
      item.addEventListener("click", function () {
        const notificationId = this.getAttribute("data-id");
        const link = this.getAttribute("data-link");
        // Enviar solicitud para marcar la notificación como vista
        fetch("mark_notification_seen.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ notification_id: notificationId }),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              // Actualizar el contador de notificaciones
              const currentCount = parseInt(notificationBadge.textContent, 10);
              const newCount = Math.max(0, currentCount - 1);
              if (newCount === 0) {
                notificationBadge.style.display = "none";
              } else {
                notificationBadge.textContent = newCount;
              }
              // Redirigir al enlace asociado
              if (link) {
                window.location.href = link;
              }
            } else {
              console.error(
                "Error al marcar la notificación como vista:",
                data.message
              );
            }
          })
          .catch((err) =>
            console.error("Error al procesar la notificación:", err)
          );
      });
    });
  }
  // Función para resaltar publicaciones si se redirige con el ID
  function highlightPostFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get("post_id");
    if (postId) {
      const targetPost = document.getElementById("post-" + postId);
      if (targetPost) {
        targetPost.style.transition = "background-color 0.5s ease";
        targetPost.style.backgroundColor = "#5cb85c8c";
        targetPost.scrollIntoView({ behavior: "smooth", block: "center" });
        targetPost.style.boxShadow = "0px 0px 10px 5px rgba(92, 184, 92, 0.5)";
        setTimeout(() => {
          targetPost.style.boxShadow = "";
          targetPost.style.backgroundColor = "";
        }, 2000);
      }
    }
  }
  // Inicializar
  loadNotifications();
  highlightPostFromURL();
  setInterval(loadNotifications, 1000); // Recargar notificaciones cada segundo
});
