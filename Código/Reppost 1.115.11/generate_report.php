<?php
session_start();
include('Config/dbcon.php');
require('libs/fpdf186/fpdf.php');
// Obtener  ID del usuario
$user_id = $_SESSION['id'] ?? 0;
// Consulta de datos del usuario
$stmtUser = $conn->prepare("SELECT firstname, lastname, email, username, city, country, mobile, gender,birthdate,tokens FROM members WHERE member_id = :uid");
$stmtUser->bindParam(':uid', $user_id, PDO::PARAM_INT);
$stmtUser->execute();
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);
if (!$userData) {
    echo "Error: no se encontraron datos del usuario";
    exit;
}
// Obtener estadísticas
$stmtPosts = $conn->prepare("
    SELECT COUNT(*) as total_posts
    FROM post
    WHERE member_id = :uid
");
$stmtPosts->bindParam(':uid', $user_id, PDO::PARAM_INT);
$stmtPosts->execute();
$totalPosts = $stmtPosts->fetchColumn();
$stmtReactions = $conn->prepare("
    SELECT COUNT(*) 
    FROM post_reactions pr
    JOIN post p ON pr.post_id = p.post_id
    WHERE p.member_id = :uid
");
$stmtReactions->bindParam(':uid', $user_id, PDO::PARAM_INT);
$stmtReactions->execute();
$totalReactions = $stmtReactions->fetchColumn();
$stmtComments = $conn->prepare("
    SELECT COUNT(*) 
    FROM post_comments
    WHERE user_id = :uid
");
$stmtComments->bindParam(':uid', $user_id, PDO::PARAM_INT);
$stmtComments->execute();
$totalComments = $stmtComments->fetchColumn();
// País
$countriesFull = [
    "AR" => "Argentina",
    "BO" => "Bolivia",
    "BR" => "Brasil",
    "CL" => "Chile",
    "CO" => "Colombia",
    "EC" => "Ecuador",
    "GY" => "Guyana",
    "PY" => "Paraguay",
    "PE" => "Perú",
    "SR" => "Surinam",
    "UY" => "Uruguay",
    "VE" => "Venezuela"
];
$countryName = $countriesFull[$userData['country']] ?? $userData['country'];
// Fecha de nacimiento
$birthdate = $userData['birthdate'];
$birthFormatted = date('d/m/Y', strtotime($birthdate));
// PDF
class PDF extends FPDF
{
    // Cabecera
    function Header()
    {
        // Fondo degradado o color sólido
        $this->SetFillColor(61, 160, 159);
        $this->Rect(0, 0, $this->GetPageWidth(), 40, 'F');
        // Logo Reppost
        $this->Image('View/Images/app_images/logo.png', 10, 8, 25);
        // Título
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(40);
        $this->Cell(100, 10, 'Informe de Usuario', 0, 0, 'L');
        $this->Ln(15);
        // Subtítulo
        $this->SetFont('Arial', 'I', 12);
        $this->Cell(40);
        $this->Cell(100, 10, 'Detalles y estadisticas de la cuenta', 0, 0, 'L');
        $this->Ln(20);
        $this->SetTextColor(0, 0, 0);
    }
    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
    function AddBackground()
    {
        $x = $this->GetX();
        $y = $this->GetY();
        $w = $this->GetPageWidth();
        $h = $this->GetPageHeight();
        $imgW = 50;
        $imgH = 50;
        $posX = $w - $imgW - 1;
        $posY = $h - $imgH - 1;
        $this->Image('View/Images/app_images/logol.png', $posX, $posY, $imgW, $imgH);
        $this->SetXY($x, $y);
    }
}
// Crear PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->AddBackground();
// Sección Datos de Usuario
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(61, 160, 159);
$pdf->Cell(0, 10, "Datos del Usuario", 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(2);
// a) Username
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, "Usuario:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, $userData['username'], 0, 1, 'L');
// b) Nombre completo
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, "Nombre:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, $userData['firstname'] . " " . $userData['lastname'], 0, 1);
// c) Email (verificado)
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, "Email:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, $userData['email'] . " (verificado)", 0, 1);
// d) Ubicacion
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, "Ubicacion:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, $countryName . " - " . $userData['city'], 0, 1);
// e) Genero
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, "Genero:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, $userData['gender'], 0, 1);
// f) Movil
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, "Movil:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, $userData['mobile'], 0, 1);
// g) Fecha nacimiento
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, "Nacimiento:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 8, $birthFormatted, 0, 1);
// h) Tokens totales
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 8, "Tokens:", 0, 0, 'L');
$pdf->SetFont('Arial', '', 12);
$tokensFormat = number_format($userData['tokens'], 8);
$pdf->Cell(0, 8, $tokensFormat, 0, 1);
$pdf->Ln(10);
// Sección Estadísticas
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(61, 160, 159);
$pdf->Cell(0, 10, "Estadisticas:", 0, 1);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(2);
$pdf->Cell(0, 8, "- Publicaciones realizadas: {$totalPosts}", 0, 1);
$pdf->Cell(0, 8, "- Reacciones recibidas en tus publicaciones: {$totalReactions}", 0, 1);
$pdf->Cell(0, 8, "- Comentarios hechos por ti: {$totalComments}", 0, 1);
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->SetTextColor(128, 128, 128);
$pdf->Cell(0, 8, "Fecha del informe: " . date("d/m/Y H:i:s"), 0, 1, 'L');
$pdf->Output();
exit;
