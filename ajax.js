$('#joshForm input').on('change', function(){
  var chosen = $('input[name=joshMood]:checked', '#joshForm').val();
  var data = 'joshMood=' + chosen;

  $.ajax({
    url: "db_access.php",
    data: data,
    type: "GET",
    dataType: "json",
    success: function(json){
      var imgEl = $('img');
      if(imgEl.length === 0){
        imgEl = $(document.createElement("img"));
        imgEl.insertAfter('#joshForm');
        imgEl.attr("class", "col-md-6 picture");
      }
      imgEl.attr("src", json.image);
    },
    error: function(xhr, status, error){
      alert("AJAX onchange failed" + error);
    },
    complete: function(xhr, status){
      //done
    },
    cache: false
  });
});
