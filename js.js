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

function filter_array(test_array) {
    var index = -1,
        arr_length = test_array ? test_array.length : 0,
        resIndex = -1,
        result = [];

    while (++index < arr_length) {
        var value = test_array[index];

        if (value) {
            result[++resIndex] = value;
        }
    }
    return result;
}

var submitted = [];
var c = 0;

var catInput = document.getElementById("catInput");
catInput.addEventListener("keydown", function(e) {
	if (e.keyCode === 13 && document.getElementById("catInput").value.trim() != "") {
		voegToe();
	}
});



Element.prototype.remove = function() {
    this.parentElement.removeChild(this);
}
NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
    for(var i = this.length - 1; i >= 0; i--) {
        if(this[i] && this[i].parentElement) {
            this[i].parentElement.removeChild(this[i]);
        }
    }
}

function voegToe() {
    console.log("test");
    var input = document.getElementById("catInput").value.trim();
    if (categories.indexOf(input) > -1) {
        document.getElementById("error").innerHTML = "";
        if (submitted.indexOf(input) < 0) {
            submitted.push(input); 
            var d1 = document.getElementById('categories');
            d1.insertAdjacentHTML('beforeend', "<div id='cat"+c+"' class='cat'>" + submitted[c] + "&nbsp;&nbsp; <div class='catX' onclick='deleteCat("+c+")'>&#10006;</div></div>" );
            c++;
        }
        document.getElementById("catInput").value = "";
        document.getElementById("submitButton").disabled = true;
        document.getElementById("result").value = filter_array(submitted);
    } else {
    document.getElementById("error").innerHTML = "<p>'"+input+"' is geen bestaande categorie";
    document.getElementById("catInput").value = "";
    document.getElementById("submitButton").disabled = true;
    }
}
function deleteCat(x){
 submitted[x] = "";
 document.getElementById("cat"+x).remove();
 document.getElementById("result").value = filter_array(submitted);
}
function inputUpdate() {
    if (document.getElementById("catInput").value.trim() != "") {
        document.getElementById("submitButton").disabled = false;
    } else {
        document.getElementById("submitButton").disabled = true;
    }
    
}