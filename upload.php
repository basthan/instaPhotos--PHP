<?php
	require_once("config.php");
    if(!isset($_SESSION['uname'])){
        header('Location: login.php');
    }else{
		$nav_button ='<li class="nav-item">
            <a class="nav-link page-scroll" href="#">Hi, '.$_SESSION['uname'].'</a>
          </li>
          <li class="nav-item">
			<form method="POST" action="">
			  <input type="submit" value="Logout" name="but_logout">
			</form>
          </li>';
	}
// logout
    if(isset($_POST['but_logout'])){
        session_destroy();
        header('Location: index.php');
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Photos App</title>
    <script src="https://unpkg.com/vue"></script>
    <script src="https://unpkg.com/axios@0.2.1/dist/axios.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
  </head>
  <body>
    <!-- navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">Photos App</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto"></ul>
    <ul class="navbar-nav justify-content-end">
      <?php echo $nav_button?>
    </ul>
  </div>
</nav>
    <div id="app">
      <div v-if="!image">
        <h2>Select an image</h2>
        <input type="file" @change="onFileChange">
      </div>
      <div v-else>
        <img :src="image" />
        <button v-if="!uploadURL" @click="removeImage">Remove image</button>
        <button v-if="!uploadURL" @click="uploadImage">Upload image</button>
      </div>
      <h2 v-if="uploadURL">Success! Image uploaded!.</h2>
    </div>

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->

    <script>
      const MAX_IMAGE_SIZE = 10000000

      /* ENTER YOUR ENDPOINT HERE */

      const API_ENDPOINT = 'https://qc6ct5ofhe.execute-api.us-east-1.amazonaws.com/default/getPresignedURL' // e.g. https://ab1234ab123.execute-api.us-east-1.amazonaws.com/uploads

      new Vue({
        el: "#app",
        data: {
          image: '',
          uploadURL: ''
        },
        methods: {
          onFileChange (e) {
            let files = e.target.files || e.dataTransfer.files
            if (!files.length) return
            this.createImage(files[0])
          },
          createImage (file) {
            // var image = new Image()
            let reader = new FileReader()
            reader.onload = (e) => {
              console.log('length: ', e.target.result.includes('data:image/jpeg'))
              if (!e.target.result.includes('data:image/jpeg')) {
                return alert('Wrong file type - JPG only.')
              }
              if (e.target.result.length > MAX_IMAGE_SIZE) {
                return alert('Image is loo large.')
              }
              this.image = e.target.result
            }
            reader.readAsDataURL(file)
          },
          removeImage: function (e) {
            console.log('Remove clicked')
            this.image = ''
          },
          uploadImage: async function (e) {
            console.log('Upload clicked')
            // Get the presigned URL
            const response = await axios({
              method: 'GET',
              url: API_ENDPOINT
            })
            console.log('Response: ', response)
            console.log('Uploading: ', this.image)
            let binary = atob(this.image.split(',')[1])
            let array = []
            for (var i = 0; i < binary.length; i++) {
              array.push(binary.charCodeAt(i))
            }
            let blobData = new Blob([new Uint8Array(array)], {type: 'image/jpeg'})
            console.log('Uploading to: ', response.uploadURL)
            console.log('File name: ', response.Key)
            const result = await fetch(response.uploadURL, {
              method: 'PUT',
              body: blobData
            })
			$.ajax({
			  url: "index.php",
			  method: "POST",
			  data: { "imageURL": response.Key }
			})
			
            console.log('Result: ', result)
            console.log('Name: ', response.Key)
            // Final URL for the user doesn't need the query string params
            this.uploadURL = response.uploadURL.split('?')[0]
            setTimeout(() => {  window.location.href = 'index.php'; }, 2500)
          }
        }
      })
    </script>
    <style type="text/css">
      body {
        background: #20262E;
        padding: 20px;
        font-family: sans-serif;
      }
      #app {
        background: #fff;
        border-radius: 4px;
        padding: 20px;
        transition: all 0.2s;
        text-align: center;
      }
      #logo {
        width: 100px;
      }
      h2 {
        font-weight: bold;
        margin-bottom: 15px;
      }
      h1, h2 {
        font-weight: normal;
        margin-bottom: 15px;
      }
      a {
        color: #42b983;
      }
      img {
        width: 30%;
        margin: auto;
        display: block;
        margin-bottom: 10px;
      }
    </style>
  </body>
</html>