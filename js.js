function deleteUsr() {
    var delA = confirm("Weet je zeker dat je dit wilt verwijderen?");
    if (delA == true) {
        window.location.href='deleteuser.php?id=' + id;
    }
}

function deleteArt() {
    var delA = confirm("Weet je zeker dat je dit wilt verwijderen?");
    if (delA == true) {
        window.location.href='delete.php?id=' + id;
    }
}