<?php
$conn = new mysqli("localhost", "root", "", "music_library");
$id   = intval($_GET['id']);
$conn->query("INSERT INTO playback_log(track_id,played_at) VALUES ($id,NOW())");
echo "Logged play.";
?>