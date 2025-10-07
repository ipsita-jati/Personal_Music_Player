<?php
$conn = new mysqli("localhost", "root", "", "music_library");
$id   = intval($_GET['id']);

$path = '';
$res  = $conn->query("SELECT file_path FROM tracks WHERE id=$id");
if ($res->num_rows) $path = $res->fetch_assoc()['file_path'];

$conn->query("DELETE FROM playback_log WHERE track_id=$id");
$conn->query("DELETE FROM tracks WHERE id=$id");

if ($path && file_exists($path)) unlink($path);
echo "Track deleted.";
?>