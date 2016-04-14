<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Sarcastic Josh</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <!-- upload more images -->
    <div class="uploadContainer">
      <span>Upload</span>
      <input type="button" name="upload" class="uploadBtn" value="upload">
    </div>

    <!-- available selections -->
    <div class="formContainer">
      <form id='joshForm'>
        <fieldset id="selectionField">
        </fieldset>
      </form>
    </div>

    <!-- image view area -->
    <div class="imgContainer">
      <img src="pix/default.jpg" alt="default image" class="picture"/>
    </div>

    <script src="/js/jquery-2.2.2.js"></script>
    <script src="ajax.js"></script>
  </body>
</html>
