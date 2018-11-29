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

function confirmDel(x){
   var delA = confirm("Weet je zeker dat je dit wilt verwijderen?");
    if (delA == true) {
        window.location.href=x
    } 
}

function showList(x){
    document.getElementById(x).classList.toggle('aVisible');
    y = x + "_arrow";
    document.getElementById(y).classList.toggle('arrowActive');
}