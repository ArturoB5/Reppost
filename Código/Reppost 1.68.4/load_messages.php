<?php
include('Config/dbcon.php');
include('Controller/Backend/session.php');

if (isset($_GET['friend_id']) && is_numeric($_GET['friend_id'])) {
    $friend_id = intval($_GET['friend_id']);
} else {
    echo "<p>Error: ID de amigo no válido.</p>";
    exit;
}
$query = $conn->prepare("
        SELECT message.*, sender.firstname AS sender_firstname, sender.lastname AS sender_lastname,
               receiver.firstname AS receiver_firstname, receiver.lastname AS receiver_lastname
        FROM message
        LEFT JOIN members AS sender ON message.sender_id = sender.member_id
        LEFT JOIN members AS receiver ON message.receiver_id = receiver.member_id
        WHERE (sender_id = :session_id AND receiver_id = :friend_id)
        OR (sender_id = :friend_id AND receiver_id = :session_id)
        ORDER BY date_sended DESC
    ");
$query->bindParam(':session_id', $session_id, PDO::PARAM_INT);
$query->bindParam(':friend_id', $friend_id, PDO::PARAM_INT);
$query->execute();
$messages = $query->fetchAll();

if ($messages) {
    foreach ($messages as $row) {
        $is_sent = $row['sender_id'] == $session_id;
?>
        <div class="mb-3">
            <!-- Alineación del mensaje -->
            <div style="
                    color: #fff; 
                    background-color: <?php echo $is_sent ? '#008568' : '#5e7671'; ?>; 
                    text-align: <?php echo $is_sent ? 'right' : 'left'; ?>; 
                    margin-<?php echo $is_sent ? 'right' : 'left'; ?>; 
                    padding: 10px; 
                    border-radius: 20px; 
                    max-width: 70%; 
                    margin-bottom: 10px;
                    <?php echo $is_sent ? 'border-bottom-right-radius: 0;' : 'border-bottom-left-radius: 0;'; ?>
                ">
                <?php echo htmlspecialchars($row['content']); ?>
                <div class="small text-muted mt-2">
                    <?php
                    // Muestra la fecha de envío en el formato adecuado
                    $date_sended = new DateTime($row['date_sended']);
                    echo $date_sended->format('H:i - Y/m/d');
                    ?>
                </div>
            </div>
        </div>
<?php
    }
} else {
    echo "<p>No hay mensajes en esta conversación.</p>";
}
?>