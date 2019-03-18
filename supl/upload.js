$(document).ready(function () {
    var currForm = '';
    $('.uploadBtnClick').click(function (e) {
        e.preventDefault();
        currForm = $(this).parents("form").first().prop("id");
        $("#fileupload").click();
    });

    $('#fileupload').change(function(){
        /*if ($("#generate_catalog_img").is(":checked")) {
            currUploadUrl += "?cat=true";
        }*/
        //console.log('upload_url: ' + currUploadUrl);
        $(this).simpleUpload('/supl/upload-file.php', {
            start: function(file){
                //upload started
                $("#" + currForm + " .uploadProgressWrapper").show();
                $("#" + currForm + " .fileUploadSuccess").hide();
                $("#" + currForm + " .fileUploadError").hide();
            },

            progress: function(progress){
                //received progress
                $("#" + currForm + " .uploadProgressWrapper .progress-bar").css("width",Math.round(progress) + "%");
                //console.log('progress: ' + Math.round(progress) + "%");
            },

            success: function(data){
                var currSrc = JSON.stringify(data);
                currSrc = currSrc.substr(1,currSrc.length - 2);
                $("#" + currForm + " .uploadProgressWrapper").hide();
                if (currSrc.substr(0,4) != "http") {
                    $("#" + currForm + " .fileUploadError").html(currSrc).show();
                } else {
                    let currVal = $("#" + currForm + " .form_file_path").val();
                    if (currVal.length)
                        currVal += ',';
                    currVal += currSrc;
                    $("#" + currForm + " .form_file_path").val(currVal);
                    $("#" + currForm + " .fileUploadSuccess").show();
                }
            },

            error: function(error){
                $("#" + currForm + " .uploadProgressWrapper").hide();
                return;
            }
        });
    });
});