$(document).ready(function () {

// CKEDITOR
    ClassicEditor
        .create(document.querySelector('#body'))//select any text area with id=body
        .catch(error => {
            console.error(error);
        });
});

//------------Other stuff------------------