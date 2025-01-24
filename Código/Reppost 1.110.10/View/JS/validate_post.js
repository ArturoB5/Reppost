function validatePostForm(form) {
	const content = form.querySelector('textarea[name="content"]').value.trim();
	const images = form.querySelector('input[name="images[]"]').files;

	if (!content && images.length === 0) {
		alert("Escribe algo o envia una imagen.");
		return false;
	}
	return true;
}
	