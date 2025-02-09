document.addEventListener('DOMContentLoaded', function() {
   // Botón ver más
    document.querySelectorAll('.show-more-comments').forEach(function(button) {
      button.addEventListener('click', function() {
        const hiddenContainer = this.nextElementSibling;
        if (hiddenContainer && hiddenContainer.classList.contains('hidden-comments')) {
          hiddenContainer.style.display = 'block';
          this.style.display = 'none';
        }
      });
    });
    // Botón ver menos
    document.querySelectorAll('.show-less-comments').forEach(function(button) {
      button.addEventListener('click', function() {
        const hiddenContainer = this.parentElement;
        hiddenContainer.style.display = 'none';
        const moreButton = hiddenContainer.previousElementSibling;
        if (moreButton && moreButton.classList.contains('show-more-comments')) {
          moreButton.style.display = 'block';
        }
      });
    });
  });