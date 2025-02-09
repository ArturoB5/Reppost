function validatePostForm(form) {
  const content = form.querySelector('textarea[name="content"]').value.trim();
  const imageInput = form.querySelector('input[name="images"]'); // Corrige el nombre según tu HTML
  const videoInput = form.querySelector('input[name="videos"]'); // Corrige el nombre según tu HTML

  const hasImage = imageInput && imageInput.files && imageInput.files.length > 0;
  const hasVideo = videoInput && videoInput.files && videoInput.files.length > 0;

  if (!content && !hasImage && !hasVideo) {
    alert("Por favor ingresa algo, sube una imagen o un video.");
    return false;
  }
  return true;
}
