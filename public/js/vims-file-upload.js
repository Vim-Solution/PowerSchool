
$('#vims-file-upload-submit').click(function (e) {
    $('#upload-file').submit();
});

$('#vims-file-upload').change(function(){
    $('.vims-z-depth-4').css({"color": "red"});
});