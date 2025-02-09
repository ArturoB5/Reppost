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


// Evento para guardar la imagen y los metadatos
document.getElementById('saveCanvas').addEventListener('click', function() {
    const endTime = new Date(); // Guardamos el tiempo final
    const dataURL = canvas.toDataURL('image/png');
    const userId = document.getElementById('userId').value || 'Desconocido';
    const userName = document.getElementById('userName').value || 'Desconocido';
    const timestamp = new Date().toISOString().replace(/[-:.]/g, "");
    const filename = `dibujo_${timestamp}.png`;

    // Obtener dimensiones del canvas
    const canvasWidth = canvas.width;
    const canvasHeight = canvas.height;

    // Obtener tamaño del pincel
    const brushSize = document.getElementById('brushSize').value || 5;

    // Obtener color del trazo
    const strokeColor = document.getElementById('colorPicker').value || '#000000';

    // Obtener color de fondo (si se ha establecido)
    const backgroundColor = ctx.fillStyle || '#FFFFFF'; // El color actual de fondo

    // Crear un enlace temporal para descargar la imagen
    const imageLink = document.createElement('a');
    imageLink.href = dataURL;
    imageLink.download = filename;
    document.body.appendChild(imageLink);
    imageLink.click();
    document.body.removeChild(imageLink);

    // Generar metadatos adicionales
    const metadataContent = `
    Usuario ID: ${userId}
    Nombre: ${userName}
    Fecha: ${new Date().toLocaleString()}
    Archivo: ${filename}
    Dimensiones: ${canvasWidth}x${canvasHeight}
    Tamaño del Pincel: ${brushSize}px
    Color de Trazo: ${strokeColor}
    Color de Fondo: ${backgroundColor}
    IP: No disponible (Descarga local)
    `;
    // Crear archivo de metadatos como un blob y descargarlo
    const metadataBlob = new Blob([metadataContent], {
        type: 'text/plain'
    });
    const metadataURL = URL.createObjectURL(metadataBlob);
    const metadataLink = document.createElement('a');
    metadataLink.href = metadataURL;
    metadataLink.download = `metadata_${timestamp}.txt`;
    document.body.appendChild(metadataLink);
    metadataLink.click();
    document.body.removeChild(metadataLink);
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

