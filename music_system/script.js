/* ---------- ADD NEW TRACK ---------- */
document.getElementById("addForm").addEventListener("submit", function (e) {
    e.preventDefault();
    const fd = new FormData(this);

    fetch("add_entry.php", { method: "POST", body: fd })
        .then(r => r.text())
        .then(msg => {
            alert(msg);
            loadLibrary(document.getElementById("searchInput").value);
            this.reset();
        });
});

document.getElementById("searchInput").addEventListener("input", function () {
    loadLibrary(this.value);
});

document.getElementById("clearSearch").addEventListener("click", function () {
    document.getElementById("searchInput").value = "";
    loadLibrary("");
});

function loadLibrary(search = "") {
    fetch(`get_library.php?q=${encodeURIComponent(search)}`)
        .then(r => r.text())
        .then(html => {
            document.getElementById("library").innerHTML = html;
        });
}

/* ---------- PLAY ---------- */
function playTrack(id) {
    fetch(`play_track.php?id=${id}`)
        .then(() => {
            const audio = document.getElementById(`audio_${id}`);
            if (audio) audio.play();
            loadLibrary(document.getElementById("searchInput").value);
        });
}
function deleteTrack(id) {
    if (!confirm("Delete this track (and file)?")) return;
    fetch(`delete_track.php?id=${id}`)
        .then(r => r.text())
        .then(msg => {
            alert(msg);
            loadLibrary(document.getElementById("searchInput").value);
        });
}

window.onload = () => loadLibrary();