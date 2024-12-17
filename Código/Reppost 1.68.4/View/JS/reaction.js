document.addEventListener("DOMContentLoaded", () => {
    // Seleccionamos todos los botones de reacción
    const reactionContainers = document.querySelectorAll(".reaction-container");
    reactionContainers.forEach(container => {
      const button = container.querySelector(".btn-heart");
      const reactionCountElement = container.querySelector(".reaction-count");
      button.addEventListener("click", () => {
        // Obtener el ID de la publicación desde el contenedor
        const postId = container.getAttribute("data-post-id");
        let currentCount = parseInt(reactionCountElement.textContent, 10);
        // Alternar la reacción (agregar o quitar)
        if (button.classList.contains("reacted")) {
          // Si ya reaccionaron, restamos 1
          currentCount -= 1;
          button.classList.remove("reacted");
        } else {
          // Si no reaccionaron, sumamos 1
          currentCount += 1;
          button.classList.add("reacted");
        }
        // Actualizar el contador de reacciones en el frontend
        reactionCountElement.textContent = currentCount;
        // Enviar al backend la actualización de la reacción
        updateReactionOnServer(postId, button.classList.contains("reacted"));
      });
    });
  });
  // Función para enviar la reacción al servidor
  function updateReactionOnServer(postId, reacted) {
    fetch("/update-reaction", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        post_id: postId,
        reacted: reacted, // true para sumar, false para restar
      }),
    })
      .then(response => response.json())
      .then(data => {
        console.log("Reacción actualizada:", data);
      })
      .catch(error => {
        console.error("Error al actualizar la reacción:", error);
      });
  }
  