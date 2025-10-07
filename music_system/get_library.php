<?php
$conn  = new mysqli("localhost", "root", "", "music_library");
$search = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

$sql = "SELECT a.name AS artist, al.name AS album,
               t.id, t.name AS track, t.duration, t.file_path,
               (SELECT COUNT(*) FROM playback_log p WHERE p.track_id = t.id) AS play_count,
               (SELECT MAX(played_at) FROM playback_log p WHERE p.track_id = t.id) AS last_played
        FROM artists a
        JOIN albums  al ON a.id = al.artist_id
        JOIN tracks  t  ON al.id = t.album_id
        WHERE a.name LIKE '%$search%' OR
              al.name LIKE '%$search%' OR
              t.name LIKE '%$search%'
        ORDER BY a.name, al.name, t.name";

$res = $conn->query($sql);
$curArtist = $curAlbum = '';

while ($row = $res->fetch_assoc()) {
    if ($curArtist !== $row['artist']) {
        echo "<h2>Artist: " . htmlspecialchars($row['artist']) . "</h2>";
        $curArtist = $row['artist']; $curAlbum = '';
    }
    if ($curAlbum !== $row['album']) {
        echo "<h3>Album: " . htmlspecialchars($row['album']) . "</h3>";
        $curAlbum = $row['album'];
    }

   echo "<p>Track: " . htmlspecialchars($row['track']) . " (" . $row['duration'] . "s) 
    | Played: " . $row['play_count'] . " times 
    | Last Played: " . ($row['last_played'] ?? "Never") . " 
    <button onclick='playTrack(" . $row['id'] . ")'>Play</button>
    <button onclick='deleteTrack(" . $row['id'] . ")'>Delete</button><br>";

    if ($row['file_path'])
        echo "<audio id='audio_{$row['id']}' controls src='" . htmlspecialchars($row['file_path']) . "'></audio>";

    echo "</p>";
}
?>