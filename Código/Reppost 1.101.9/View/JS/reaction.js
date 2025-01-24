document.addEventListener("DOMContentLoaded", function () {
    // Reacciones en publicaciones
    const postReactionButtons = document.querySelectorAll(".reaction-btn");
    postReactionButtons.forEach(button => {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        const postId = this.getAttribute("data-id");
        const reactionCount = document.getElementById(`reaction-count-${postId}`);
        const originalText = reactionCount.textContent;
        // Mostrar un indicador temporal
        reactionCount.textContent = "↻";
        fetch("react_post.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ post_id: postId }),
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              reactionCount.textContent = data.reaction_count;
            } else {
              alert(data.message);
              reactionCount.textContent = originalText; // Restaurar conteo original
            }
          })
          .catch(error => {
            console.error("Error al procesar la reacción:", error);
            reactionCount.textContent = originalText; // Restaurar conteo original
          });
      });
    });
    // Reacciones en comentarios
    const commentReactionButtons = document.querySelectorAll(".reaction-comment-btn");
    commentReactionButtons.forEach(button => {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        const commentId = this.getAttribute("data-comment-id");
        const reactionCount = document.getElementById(`comment-reaction-count-${commentId}`);
        const originalText = reactionCount.textContent;
        // Mostrar un indicador temporal
        reactionCount.textContent = "⏳";
        fetch("react_comment.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ comment_id: commentId }),
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              reactionCount.textContent = data.reaction_count;
            } else {
              alert(data.message);
              reactionCount.textContent = originalText; // Restaurar conteo original
            }
          })
          .catch(error => {
            console.error("Error al procesar la reacción:", error);
            reactionCount.textContent = originalText; // Restaurar conteo original
          });
      });
    });
  });