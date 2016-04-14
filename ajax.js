
  var modalContainer = $('#uploadPreviewModal');
  $('#uploadBtn').click(function(){
    modalContainer.show();
  });
  $('.modalCancel').click(function(){
    modalContainer.hide();
  });
  $(window).click(function(event){
    if ($(event.target).is(modalContainer)) {
      modalContainer.hide();
    }
  });


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
