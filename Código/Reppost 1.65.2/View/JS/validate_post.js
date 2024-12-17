function validatePostForm(form) {
	const content = form.querySelector('textarea[name="content"]').value.trim();
	const images = form.querySelector('input[name="images[]"]').files;

	if (!content && images.length === 0) {
		alert("Debes escribir algo o enviar una imagen.");
		return false;
	}
	return true;
}
	