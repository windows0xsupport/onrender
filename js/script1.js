
  $(document).ready(function() {
   var audioElement = document.createElement('audio');
   audioElement.setAttribute('src', 'https://audio.jukehost.co.uk/019e6d4b-c621-7323-bf16-b37bc1c8077d');

   audioElement.addEventListener('ended', function() {
       this.play();
   }, false);


    $('#mycanvas').click(function() {
       audioElement.play();

   });

     $('#link_black').click(function() {
       audioElement.play();

   });


      $('.pro_box').click(function() {
       audioElement.play();

   });

       $('#botgnws').click(function() {
       audioElement.play();

   });

});