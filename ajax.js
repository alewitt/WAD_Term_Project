
function reset_file_input(event) {
    event.wrap('<form>').parent('form').trigger('reset');
    event.unwrap();
}

var modalContainer = $('#uploadPreviewModal');
$('#uploadBtn').click(function(){
  var fileInput = $('#fileinput');
  fileInput.click();
  fileInput.on('change', function(){
    modalContainer.show();
  });
});
$('.modalCancel').click(function(event){
  modalContainer.hide();
  reset_file_input( $('#fileinput') );
  event.preventDefault();
  $('#modalImgContainer').html('');
});
$(window).click(function(event){
  if ($(event.target).is(modalContainer)) {
    modalContainer.hide();
    reset_file_input( $('#fileinput') );
    event.preventDefault();
    $('#modalImgContainer').html('');
  }
});
function reload_page() {
  $('#modalImgContainer').html('');
  $('#selectionField').html('');
  reset_file_input( $('#fileinput') );
  addMoodOptions();
  modalContainer.hide();
}


function add_btn_listeners(){
  $('.removeImgBtn').on('click', function(){
    var count = $(this).data("count");
    $('#modalUploadForm_'+count).toggleClass('removed');
    $(this).attr('value', 'Restore');
  });
}

(function(){
  /**
   *  main functionality Created by remi on 18/01/15.
   */
    function previewImage(file, count) {
        var galleryId = "modalImgContainer";

        var gallery = document.getElementById(galleryId);
        var imageType = /image.*/;

        if (!file.type.match(imageType)) {
          alert("File type must be an image");
          throw "File Type must be an image";
        }

        // main container for each image uploaded
        var uploadItem = document.createElement("div");
        uploadItem.classList.add('modalUploadItem');


        // child container just for image thumbnail
        var uploadImg = document.createElement("div");
        uploadImg.classList.add('modalUploadImg');
        uploadItem.appendChild(uploadImg);

        var img = document.createElement("img");
        img.file = file;
        uploadImg.appendChild(img);


        // chile container for image information
        var uploadForm = document.createElement("div");
        uploadForm.classList.add('modalUploadForm');
        uploadItem.setAttribute("id", "modalUploadForm_"+count)
        uploadItem.appendChild(uploadForm);

        // Display Name input
        var label = document.createElement("label");
        label.innerText = "Display Name";
        var br = document.createElement("br");
        label.appendChild(br);
        var input = document.createElement("input");
        input.setAttribute("placeholder", "Happy Josh");
        label.appendChild(input);
        var br = document.createElement("br");
        label.appendChild(br);
        var br = document.createElement("br");
        label.appendChild(br);
        uploadForm.appendChild(label);

        // Remove Buttom
        var input = document.createElement("input");
        input.setAttribute("class", "removeImgBtn");
        input.setAttribute("type", "button");
        input.setAttribute("name", "uploadForm");
        input.setAttribute("value", "Remove");
        input.setAttribute("data-count", count);
        uploadItem.appendChild(input);

        gallery.appendChild(uploadItem);

        // Using FileReader to display the image content
        var reader = new FileReader();
        reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
        reader.readAsDataURL(file);
    }

    var previewfiles = document.querySelector('#fileinput');
    previewfiles.addEventListener('change', function () {
        var files = this.files;
        // console.log(files);
        for(var i=0; i<files.length; i++){
            previewImage(this.files[i], i);
            // console.log(files[i]);
        }
        add_btn_listeners();

    }, false);
})();

/**
 * main functionality Created by remi on 17/01/15.
 */
(function () {

    var uploadfiles = document.querySelector('#modalUploadBtn');
    var fileInput = document.querySelector('#fileinput');

    uploadfiles.addEventListener('click', function () {
        var files = fileInput.files;
        // console.log(files);
        var filesToUpload = [];

        for(var i=0; i<files.length; i++){
          var item = document.querySelector('#modalUploadForm_'+i);
          if (!item.classList.contains('removed')) {
            filesToUpload.push({'file':files[i], 'index':i});
          }
        }
        // console.log(filesToUpload);

        for(var i=0; i<filesToUpload.length; i++){
            uploadFile(filesToUpload[i], filesToUpload.length);
        }
    }, false);


    /**
     * Upload a file
     * @param file
     */
    function uploadFile(file, total){
        var url = "db_access_upload.php";
        var xhr = new XMLHttpRequest();
        var fd = new FormData();
        xhr.open("POST", url, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Every thing ok, file uploaded
                console.log(xhr.responseText); // handle response.
                var json = JSON.parse(xhr.responseText);
                if(json.result === 'failed'){
                  alert("Upload Failed for: " + json.file);
                }

                var completed = counter(false);
                if(completed === total){
                  completed = counter(true);
                  // console.log("completed");
                  reload_page();
                }
            }
        };
        fd.append('uploaded_file', file['file']);
        var item = document.querySelector('#modalUploadForm_'+file['index']);
        var displayName = item.children[1].children[0].children[1].value;
        fd.append('display_name', displayName);
        xhr.send(fd);
    }
}());

var counter = (function (reset) {
    var counter = 0;
    return function (reset) {
      if(reset){
        return counter = 0;
      } else {
        return counter += 1;
      }
    }
})();


function add_input_change_listener(inputEl, json){
  inputEl.on('change', function(){
    var chosen = $('input[name=joshMood]:checked', '#joshForm').val();
    var imgEl = $('img');
    if(imgEl.length === 0){
      imgEl = $(document.createElement("img"));
      $('.imgContainer').append(imgEl);
      imgEl.attr("class", "picture");
    }
    var imageUrl = json.urls[chosen]
    imgEl.attr("src", imageUrl);
  });
}

function addMoodOptions(){
  var data = 'allMoods=true';
  $.ajax({
    url: "db_access.php",
    data: data,
    type: "GET",
    dataType: "json",
    success: function(json){
      var fieldEl = $('#selectionField');
      var legendEl = $(document.createElement("legend"));
      fieldEl.html=('');
      legendEl.text("Choose Mood");
      fieldEl.append(legendEl);

      var count = 0;
      var pix_count = json.pix_count;
      while(count < pix_count){
        var inputEl = $(document.createElement("input"));
        var labelEl = $(document.createElement("label"));
        fieldEl.append(labelEl);
        labelEl.attr("class", "radio");

        inputEl.attr("type", "radio");
        inputEl.attr("name", "joshMood");
        inputEl.attr("value", count);

        labelEl.append(inputEl);
        labelEl.append(json.names[count]);
        count += 1;

        add_input_change_listener(inputEl, json);

      }
    },
    error: function(xhr,status,error){
      alert("AJAX onload failed" + error);
    },
    complete: function(xhr, status){
      //done
    },
    cache: false
  });
}

$(document).ready(function(){
  addMoodOptions();
});
