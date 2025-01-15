var canvas = document.getElementById("gameCanvas");
var ctx = canvas.getContext("2d");
//================= Variables del juego =================
var ballRadius = 10;
var x = canvas.width / 2;
var y = canvas.height - 30;
var dx = 2;
var dy = -2;
var paddleHeight = 10;
var paddleWidth = 75;
var paddleX = (canvas.width - paddleWidth) / 2;
var rightPressed = false;
var leftPressed = false;
var brickRowCount = 5;
var brickColumnCount = 5;
var brickWidth = 75;
var brickHeight = 20;
var brickPadding = 10;
var brickOffsetTop = 30;
var brickOffsetLeft = 30;
var bricks = [];
for (var c = 0; c < brickColumnCount; c++) {
    bricks[c] = [];
    for (var r = 0; r < brickRowCount; r++) {
        bricks[c][r] = {
            x: 0,
            y: 0,
            status: 1
        };
    }
}
// Puntos y Vidas
var score = 0;
var lives = 3;
var isGameOver = false;
var isGameWon = false;
var btnWidth = 100;
var btnHeight = 40;
var btnX = canvas.width / 2 - btnWidth / 2;
var btnY = canvas.height / 2 + 50;
//================= Eventos =================
document.addEventListener("keydown", keyDownHandler, false);
document.addEventListener("keyup", keyUpHandler, false);
canvas.addEventListener("mousedown", canvasClickHandler, false);
function keyDownHandler(e) {
    if (e.key === "Right" || e.key === "ArrowRight") {
        rightPressed = true;
    } else if (e.key === "Left" || e.key === "ArrowLeft") {
        leftPressed = true;
    }
}
function keyUpHandler(e) {
    if (e.key === "Right" || e.key === "ArrowRight") {
        rightPressed = false;
    } else if (e.key === "Left" || e.key === "ArrowLeft") {
        leftPressed = false;
    }
}
function canvasClickHandler(event) {
    var rect = canvas.getBoundingClientRect();
    var clickX = event.clientX - rect.left;
    var clickY = event.clientY - rect.top;
    if (isGameOver || isGameWon) {
        if (
            clickX >= btnX &&
            clickX <= btnX + btnWidth &&
            clickY >= btnY &&
            clickY <= btnY + btnHeight
        ) {
            restartGame();
        }
    }
}
//================= Funciones de dibujo =================
function drawBackground() {
    ctx.fillStyle = "#000000"; // negro
    ctx.fillRect(0, 0, canvas.width, canvas.height);
}
// Bola 
function drawBall() {
    ctx.beginPath();
    ctx.arc(x, y, ballRadius, 0, Math.PI * 2);
    ctx.fillStyle = "#ffffff"; // bola blanca
    ctx.fill();
    ctx.closePath();
    ctx.font = "bold 14px Arial";
    ctx.fillStyle = "green";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillText("R", x, y);
}
// Paleta
function drawPaddle() {
    ctx.beginPath();
    ctx.rect(paddleX, canvas.height - paddleHeight, paddleWidth, paddleHeight);
    ctx.fillStyle = "#cccccc";
    ctx.fill();
    ctx.closePath();
}
// Bloques 
function drawBricks() {
    for (var c = 0; c < brickColumnCount; c++) {
        for (var r = 0; r < brickRowCount; r++) {
            if (bricks[c][r].status === 1) {
                var brickX = (c * (brickWidth + brickPadding)) + brickOffsetLeft;
                var brickY = (r * (brickHeight + brickPadding)) + brickOffsetTop;
                bricks[c][r].x = brickX;
                bricks[c][r].y = brickY;
                ctx.beginPath();
                ctx.rect(brickX, brickY, brickWidth, brickHeight);
                ctx.fillStyle = "#20B2AA"; 
                ctx.fill();
                ctx.closePath();
            }
        }
    }
}
// Actualiza puntos y vidas
function updateHUD() {
    document.getElementById("scoreText").textContent = "Puntos: " + score;
    document.getElementById("livesText").textContent = "Vidas: " + lives;
}
// Game over
function drawGameOverText() {
    ctx.font = "40px Arial";
    ctx.fillStyle = "red";
    ctx.textAlign = "center";
    ctx.fillText("GAME OVER :(", canvas.width / 2, canvas.height / 2);
}
// Ganaste
function drawWinText() {
    ctx.font = "40px Arial";
    ctx.fillStyle = "green";
    ctx.textAlign = "center";
    ctx.fillText("¡GANASTE! :)", canvas.width / 2, canvas.height / 2);
}
// Botón reintentar
function drawRetryButton() {
    ctx.beginPath();
    ctx.rect(btnX, btnY, btnWidth, btnHeight);
    ctx.fillStyle = "#808080";
    ctx.fill();
    ctx.closePath();

    ctx.font = "16px Arial";
    ctx.fillStyle = "#ffffff";
    ctx.textAlign = "center";
    ctx.fillText("Reintentar", btnX + btnWidth / 2, btnY + 25);
}
// Destruccion de bloques
function collisionDetection() {
    for (var c = 0; c < brickColumnCount; c++) {
        for (var r = 0; r < brickRowCount; r++) {
            var b = bricks[c][r];
            if (b.status === 1) {
                if (x > b.x && x < b.x + brickWidth &&
                    y > b.y && y < b.y + brickHeight) {
                    dy = -dy;
                    b.status = 0;
                    score += 10;
                    var totalBricks = brickRowCount * brickColumnCount;
                    if (score === totalBricks * 10) {
                        isGameWon = true;
                    }
                }
            }
        }
    }
}
// Reiniciar el juego por completo
function restartGame() {
    x = canvas.width / 2;
    y = canvas.height - 30;
    dx = 2;
    dy = -2;
    paddleX = (canvas.width - paddleWidth) / 2;
    score = 0;
    lives = 3;
    isGameOver = false;
    isGameWon = false;
    for (var c = 0; c < brickColumnCount; c++) {
        for (var r = 0; r < brickRowCount; r++) {
            bricks[c][r].status = 1;
        }
    }
    draw();
}
//================= Bucle principal =================
function draw() {
    drawBackground();
    if (isGameOver) {
        drawGameOverText();
        drawRetryButton();
        return;
    }
    if (isGameWon) {
        drawWinText();
        drawRetryButton();
        return;
    }
    drawBricks();
    drawBall();
    drawPaddle();
    updateHUD();
    collisionDetection();
    // Rebote lateral
    if (x + dx > canvas.width - ballRadius || x + dx < ballRadius) {
        dx = -dx;
    }
    // Rebote superior
    if (y + dy < ballRadius) {
        dy = -dy;
    }
    // Rebote inferior (pérdida de vida)
    else if (y + dy > canvas.height - ballRadius) {
        if (x > paddleX && x < paddleX + paddleWidth) {
            dy = -dy;
        } else {
            lives--;
            if (lives <= 0) {
                isGameOver = true;
            } else {
                // Reiniciamos posición de bola y paleta
                x = canvas.width / 2;
                y = canvas.height - 30;
                dx = 2;
                dy = -2;
                paddleX = (canvas.width - paddleWidth) / 2;
            }
        }
    }
    if (rightPressed && paddleX < canvas.width - paddleWidth) {
        paddleX += 7;
    } else if (leftPressed && paddleX > 0) {
        paddleX -= 7;
    }
    x += dx;
    y += dy;

    requestAnimationFrame(draw);
}
draw();