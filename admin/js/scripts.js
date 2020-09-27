$(document).ready(function () {

    //------------CKEDITOR---------------------
    ClassicEditor
        .create(document.querySelector('#body'))//select any text area with id=body
        .catch(error => {
            console.error(error);
        });
});

$(document).ready(function () {
    //------Check all boxes in posts.php-------

    //  - When element with id "selectAllBoxes" (the top checkbox) is clicked,
    //  check if 'this' (selectAllBoxes checkbox) is checked.
    //  - If so, go through each element with class 'checkBoxes'
    //  and set "this.checked" to "true" ('this' = current checkbox in the loop) 
    //  - If not checked (else), set all to false
    $('#selectAllBoxes').click(function (event) {
        if (this.checked) {
            $('.checkBoxes').each(function () {
                this.checked = true;
            });
        } else {
            $('.checkBoxes').each(function () {
                this.checked = false;
            });
        }
    })

    //Loader:
    //- Creating var with 2 div tags, with id's corresponding to styles.css for
    //  loading background and gif.
    //- prepend this to the body (insert said variable before body)
    //- Delay displaying everything else by 500ms, before fading out
    //  and removing loader.
    var div_box = "<div id='load-screen'><div id='loading'></div></div>";

    $("body").prepend(div_box);

    $("#load-screen").delay(500).fadeOut(600, function () {
        $(this).remove();
    });


});

function loadUsersOnline() {

    //Send get-request to functions.php
    $.get("functions.php?usersonline=result", function(data){

        //insert data from function in text-container
        $(".usersonline").text(data);

    });
}

setInterval(function(){
    loadUsersOnline();
},500);
