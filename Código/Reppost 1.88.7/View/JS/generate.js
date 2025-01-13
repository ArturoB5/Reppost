 // 1. Evita que tu JS crashee si algún ID no está
        // 2. Maneja la generación de imágenes con DALL·E

        document.addEventListener('DOMContentLoaded', () => {
            // === 1) Lógica para DALLE ===
            const API_KEY = '<?php echo $apiKey; ?>';
            const dalleForm = document.getElementById('dalle-form');
            const promptInput = document.getElementById('prompt-input');
            const photoContainer = document.getElementById('photo-container');

            if (dalleForm && promptInput && photoContainer) {
                dalleForm.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    photoContainer.innerHTML = '';

                    const prompt = promptInput.value.trim();
                    if (!prompt) {
                        photoContainer.innerText = 'El prompt está vacío. Intenta describir la imagen.';
                        return;
                    }

                    try {
                        const response = await fetch('https://api.openai.com/v1/images/generations', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${API_KEY}`
                            },
                            body: JSON.stringify({
                                prompt: prompt,
                                n: 1,
                                size: '512x512'
                            })
                        });

                        if (!response.ok) {
                            const errorData = await response.json().catch(() => ({}));
                            console.error('Error al generar imágenes:', response.status, errorData);
                            throw new Error('Error al generar imágenes: ' + response.status);
                        }

                        const data = await response.json();
                        if (data.data && data.data.length > 0) {
                            data.data.forEach((imgObj, index) => {
                                const imgContainer = document.createElement('div');
                                imgContainer.style.margin = '10px';
                                imgContainer.style.display = 'inline-block';

                                const img = document.createElement('img');
                                img.src = imgObj.url;
                                img.alt = `Imagen generada #${index + 1}`;
                                img.style.maxWidth = '200px';

                                imgContainer.appendChild(img);

                                // Botón para ver en pantalla completa
                                const visualizeBtn = document.createElement('a');
                                visualizeBtn.href = imgObj.url;
                                visualizeBtn.target = '_blank';
                                visualizeBtn.textContent = 'Visualizar';
                                visualizeBtn.classList.add('btn', 'btn-primary');
                                visualizeBtn.style.display = 'block';
                                visualizeBtn.style.textAlign = 'center';

                                imgContainer.appendChild(visualizeBtn);
                                photoContainer.appendChild(imgContainer);
                            });
                        } else {
                            photoContainer.innerText = 'No se generaron imágenes.';
                        }
                    } catch (error) {
                        console.error('Error al generar imágenes:', error);
                        photoContainer.innerText = 'Hubo un error al generar imágenes.';
                    }
                });
            }

            // === 2) Ejemplo de defensiva para botones de Paint ===
            const setBackgroundColorBtn = document.getElementById('setBackgroundColor');
            if (setBackgroundColorBtn) {
                setBackgroundColorBtn.addEventListener('click', () => {
                    // ...
                });
            }
            // Repetir con los demás IDs ("eraser", "saveCanvas", etc.) según tu lógica
        });