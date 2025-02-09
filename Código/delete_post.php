<?php
include('Config/dbcon.php');
session_start();

$get_id = $_GET['id'];
$session_id = $_SESSION['id'];
$query = $conn->prepare("SELECT member_id, post_id FROM post WHERE post_id = :post_id");
$query->bindParam(':post_id', $get_id);
$query->execute();
$row = $query->fetch();

if ($row) {
    $comment_owner_id = $row['member_id'];
    if ($comment_owner_id == $session_id) {
        $comment_tokens = 0.00000010;
        $user_query = $conn->prepare("SELECT tokens FROM members WHERE member_id = :member_id");
        $user_query->bindParam(':member_id', $session_id);
        $user_query->execute();
        $user_row = $user_query->fetch();
        if ($user_row) {
            $current_tokens = $user_row['tokens'];
            $new_token_balance = $current_tokens - $comment_tokens;
            $update_query = $conn->prepare("UPDATE members SET tokens = :tokens WHERE member_id = :member_id");
            $update_query->bindParam(':tokens', $new_token_balance);
            $update_query->bindParam(':member_id', $session_id);
            $update_query->execute();
        }
        // Eliminación de imágenes
        $image_query = $conn->prepare("SELECT image_path FROM post_images WHERE post_id = :post_id");
        $image_query->bindParam(':post_id', $get_id);
        $image_query->execute();
        $images = $image_query->fetchAll();
        foreach ($images as $image) {
            $image_path = $image['image_path'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        $delete_images_query = $conn->prepare("DELETE FROM post_images WHERE post_id = :post_id");
        $delete_images_query->bindParam(':post_id', $get_id);
        $delete_images_query->execute();
        // Eliminación de videos
        $video_query = $conn->prepare("SELECT video_path FROM post_videos WHERE post_id = :post_id");
        $video_query->bindParam(':post_id', $get_id);
        $video_query->execute();
        $videos = $video_query->fetchAll();
        foreach ($videos as $video) {
            $video_path = $video['video_path'];
            if (file_exists($video_path)) {
                unlink($video_path);
            }
        }
        $delete_videos_query = $conn->prepare("DELETE FROM post_videos WHERE post_id = :post_id");
        $delete_videos_query->bindParam(':post_id', $get_id);
        $delete_videos_query->execute();
        // Eliminación de notificaciones y post
        $delete_notifications_query = $conn->prepare("DELETE FROM notifications WHERE link LIKE :link");
        $notification_link = "%home.php?post_id=$get_id%";
        $delete_notifications_query->bindParam(':link', $notification_link, PDO::PARAM_STR);
        $delete_notifications_query->execute();
        $delete_query = $conn->prepare("DELETE FROM post WHERE post_id = :post_id");
        $delete_query->bindParam(':post_id', $get_id);
        $delete_query->execute();
        header('Location: home.php');
    } else {
        header('Location: home.php?error=not_authorized');
    }
} else {
    header('Location: home.php?error=comment_not_found');
}
exit();
