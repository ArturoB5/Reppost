function openEditModal(postId) {
    // Limpia el textarea
    document.getElementById('editContent').value = '';

    // Pide el contenido via AJAX
    fetch('edit_post.php?action=fetch&post_id=' + postId)
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          alert(data.message);
          return;
        }
        // Rellenar textarea con el contenido
        document.getElementById('editContent').value = data.content;
        // Guardar postId en un atributo
        document.getElementById('saveEditBtn').setAttribute('data-post-id', postId);

        // Mostrar el modal
        $('#editModal').modal('show');
      })
      .catch(err => console.error(err));
  }

  // Listener para "Guardar cambios"
  document.getElementById('saveEditBtn').addEventListener('click', function() {
    const postId = this.getAttribute('data-post-id');
    const newContent = document.getElementById('editContent').value.trim();
    if (!newContent) {
      alert('El contenido no puede estar vacío');
      return;
    }

    // Enviar via AJAX
    fetch('edit_post.php?action=update&post_id=' + postId, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          content: newContent
        })
      })
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          alert(data.message);
          return;
        }
        // Cerrar modal
        $('#editModal').modal('hide');
        // Refrescar la página o recargar posts
        location.reload();
      })
      .catch(err => console.error(err));
  });