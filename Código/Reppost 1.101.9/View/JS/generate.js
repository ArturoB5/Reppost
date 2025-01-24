document.getElementById('generateImage').addEventListener('click', () => {
  const promptText = document.getElementById('promptInput').value;
  if (!promptText) {
      alert('Por favor ingresa el texto para generar la imagen.');
      return;
  }
  const options = {
      method: 'POST',
      headers: {
          accept: 'application/json',
          'content-type': 'application/json',
          'X-API-Key': 'tYUtO27Fpy_dNVkXV05rTXF_h-lAWw'
      },
      body: JSON.stringify({
          model: 'lyra',
          aspectRatio: 'square',
          highResolution: false,
          images: 1,
          steps: 20,
          initialImageMode: 'color',
          prompt: promptText
      })
  };
  // Muestra la barra de carga
  document.getElementById('loadingBarContainer').style.display = 'block';
  // Solicitud POST para generar la imagen
  fetch('https://api.starryai.com/creations/', options)
      .then(res => res.json())
      .then(res => {
          console.log(res);
          const creationId = res.id;
          checkCreationStatus(creationId);
      })
      .catch(err => console.error(err));
});
// Función para revisar el estado de creación hasta que esté completo
function checkCreationStatus(creationId) {
  const checkInterval = setInterval(() => {
      const options = {
          method: 'GET',
          headers: {
              accept: 'application/json',
              'X-API-Key': 'tYUtO27Fpy_dNVkXV05rTXF_h-lAWw'
          }
      };
      fetch(`https://api.starryai.com/creations/${creationId}`, options)
          .then(res => res.json())
          .then(res => {
              console.log(res);
              // Si se completa muestra la imagen
              const progress = document.getElementById('loadingBar');
              if (res.status === 'submitted') {
                progress.value = 50; // Mostrar un 50% de carga
            } else if (res.status === 'completed' && res.images[0]?.url) {
                clearInterval(checkInterval);
                const imageUrl = res.images[0].url;
                displayImage(imageUrl);
            }
          })
          .catch(err => console.error(err));
  }, 3000);
}
// Mostrar la imagen en el contenedor
function displayImage(imageUrl) {
  const responseContainer = document.getElementById('responseContainer');
  responseContainer.innerHTML = '';
  const imgElement = document.createElement('img');
  imgElement.src = imageUrl;
  imgElement.alt = 'Generated Image';
  imgElement.style.maxWidth = '512px';
  imgElement.style.marginTop = '20px';
  responseContainer.appendChild(imgElement);
  // Ocultar la barra de carga
  document.getElementById('loadingBarContainer').style.display = 'none';

  // Mostrar los botones de acción
  const actionButtons = document.getElementById('actionButtons');
  actionButtons.style.display = 'block';

  // Configurar botón de pantalla completa
  const fullscreenButton = document.getElementById('viewFullscreen');
  fullscreenButton.style.display = 'inline-block';
  fullscreenButton.addEventListener('click', () => {
      const fullscreenImage = document.createElement('img');
      fullscreenImage.src = imageUrl;
      fullscreenImage.style.maxWidth = '100%';
      fullscreenImage.style.height = 'auto';
      fullscreenImage.style.position = 'fixed';
      fullscreenImage.style.top = '0';
      fullscreenImage.style.left = '0';
      fullscreenImage.style.width = '100%';
      fullscreenImage.style.height = '100vh';
      fullscreenImage.style.zIndex = '9999';
      fullscreenImage.addEventListener('click', () => {
          document.body.removeChild(fullscreenImage);
      });
      document.body.appendChild(fullscreenImage);
  });
}