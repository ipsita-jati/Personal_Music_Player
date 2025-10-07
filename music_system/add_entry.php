<?php
$conn = new mysqli("localhost", "root", "", "music_library");

$artist   = $_POST['artist'];
$album    = $_POST['album'];
$track    = $_POST['track'];
$duration = intval($_POST['duration']);

$upDir = "uploads/";
if (!is_dir($upDir)) mkdir($upDir, 0777, true);

$tmpName = $_FILES['file']['tmp_name'];
$orig    = basename($_FILES['file']['name']);
$target  = $upDir . time() . '_' . $orig;

if (!move_uploaded_file($tmpName, $target)) {
    exit("File upload failed.");
}

/* artist */
$res = $conn->query("SELECT id FROM artists WHERE name='$artist'");
$artist_id = $res->num_rows ? $res->fetch_assoc()['id']
            : ($conn->query("INSERT INTO artists(name) VALUES('$artist')") ? $conn->insert_id : null);

/* album */
$res = $conn->query("SELECT id FROM albums WHERE name='$album' AND artist_id=$artist_id");
$album_id = $res->num_rows ? $res->fetch_assoc()['id']
           : ($conn->query("INSERT INTO albums(name,artist_id) VALUES('$album',$artist_id)") ? $conn->insert_id : null);

/* track */
$stmt = $conn->prepare("INSERT INTO tracks(name,album_id,duration,file_path) VALUES (?,?,?,?)");
$stmt->bind_param("siis", $track, $album_id, $duration, $target);
$stmt->execute();

echo "Track added successfully.";
?>