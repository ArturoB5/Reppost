const canvas = document.getElementById('drawingCanvas');
const ctx = canvas.getContext('2d');

let isDrawing = false;
let color = '#000000'; 
let brushSize = 5;
let isErasing = false; 

ctx.fillStyle = '#FFFFFF'; 
ctx.fillRect(0, 0, canvas.width, canvas.height); 

canvas.addEventListener('mousedown', (e) => {
    isDrawing = true;
    ctx.beginPath();
    ctx.moveTo(e.offsetX, e.offsetY);
});

canvas.addEventListener('mousemove', (e) => {
    if (isDrawing) {
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.strokeStyle = isErasing ? '#FFFFFF' : color;
        ctx.lineWidth = brushSize;
        ctx.lineCap = 'round';
        ctx.stroke();
    }
});

canvas.addEventListener('mouseup', () => {
    isDrawing = false;
    ctx.closePath();
});

document.getElementById('colorPicker').addEventListener('input', function(event) {
    color = event.target.value;
    isErasing = false;
});

document.getElementById('brushSize').addEventListener('input', function(event) {
    brushSize = parseInt(event.target.value, 10);
});

document.getElementById('clearCanvas').addEventListener('click', function() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
});

document.getElementById('saveCanvas').addEventListener('click', function() {
    const dataURL = canvas.toDataURL('image/png');
    const link = document.createElement('a');
    link.href = dataURL;
    link.download = 'mi_dibujo.png';
    link.click();
});

document.getElementById('setBackgroundColor').addEventListener('click', () => {
    const colorPicker = document.getElementById('colorPicker');
    let backgroundColor = colorPicker.value;
    ctx.fillStyle = backgroundColor;
    ctx.fillRect(0, 0, canvas.width, canvas.height);
});

document.getElementById('eraser').addEventListener('click', function() {
    isErasing = !isErasing; 
    const eraserButton = document.getElementById('eraser');
    if (isErasing) {
        eraserButton.innerHTML = '<i class="fas fa-pencil-alt"></i> Dibujar'; 
    } else {
        eraserButton.innerHTML = '<i class="fas fa-eraser"></i> Borrador'; 
    }
});

document.getElementById('uploadCanvas').addEventListener('click', async () => {
    const canvas = document.querySelector('#yourCanvasId'); // Reemplaza con el ID de tu canvas
    const imageData = canvas.toDataURL('image/png'); // Obtén los datos de la imagen en formato Base64

    // Envía los datos al servidor
    try {
        const response = await fetch('upload_canvas.php', {
            method: 'POST',
            body: JSON.stringify({ image: imageData }),
            headers: {
                'Content-Type': 'application/json',
            },
        });

        const result = await response.json();
        if (result.success) {
            alert(result.message); // Mensaje dinámico del servidor
        } else {
            alert(result.message); // Mensaje dinámico del servidor
        }
    } catch (error) {
        console.error('Error al subir la imagen:', error);
        alert('Error al subir la imagen');
    }
});