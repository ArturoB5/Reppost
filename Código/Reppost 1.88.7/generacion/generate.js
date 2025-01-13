const apiKey = 'tYUtO27Fpy_dNVkXV05rTXF_h-lAWw';

document.getElementById('imageForm').addEventListener('submit', async (event) => {
  event.preventDefault();

  const prompt = document.getElementById('prompt').value;

  try {
    // Step 1: POST to create a new image
    const createResponse = await fetch('https://cors-anywhere.herokuapp.com/https://api.starryai.com/creations/', {
        method: 'POST',
        headers: {
          'accept': 'application/json',
          'content-type': 'application/json',
          'X-API-Key': apiKey
        },
        body: JSON.stringify({ prompt })
      });
      

    if (!createResponse.ok) {
      throw new Error('Failed to create image');
    }

    const createData = await createResponse.json();
    const creationId = createData.id; // Assuming response contains 'id'

    // Step 2: GET to fetch the created image
    const getResponse = await fetch(`https://api.starryai.com/creations/${creationId}`, {
      method: 'GET',
      headers: {
        'accept': 'application/json',
        'X-API-Key': apiKey
      }
    });

    if (!getResponse.ok) {
      throw new Error('Failed to fetch created image');
    }

    const imageData = await getResponse.json();

    // Step 3: Display the image in the UI
    const imageUrl = imageData.image_url; // Assuming response contains 'image_url'
    const imageElement = document.getElementById('generatedImage');
    imageElement.src = imageUrl;

  } catch (error) {
    console.error(error);
    alert('An error occurred: ' + error.message);
  }
});
