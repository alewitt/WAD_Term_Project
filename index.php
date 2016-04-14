<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Sarcastic Josh</title>
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>
  <body>
    <h1 class="title">The Moods Of Joshy</h1>
    <!-- upload more images -->
    <div class="uploadContainer">
      <span>Upload</span>
      <input id="uploadBtn" type="button" name="upload" class="uploadBtn" value="Upload">
      <input type="file" id="fileinput" multiple="multiple" accept="image/*" />
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

    <!-- upload preview modal -->
    <div id="uploadPreviewModal" class="modalContainer">
      <div class="modalContent">
        <!-- modal header -->
        <div class="modalHeader">
          <span class="modalTitle">Enter Info for Uploads</span>
          <span class="modalCancel">Cancel</span>
        </div>

        <!-- show preview and write name/description -->
        <div id="modalImgContainer">

        </div>

        <!-- modal footer -->
        <div class="modalFooter">
          <input id="modalUploadBtn" type="button" name="upload" class="modalUploadBtn" value="Upload">
        </div>

      </div>
    </div>

    <script src="/js/jquery-2.2.2.js"></script>
    <script src="ajax.js"></script>
  </body>
</html>
