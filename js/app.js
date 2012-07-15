$(function(){

   $('#status').hide();
   $('#snapshot_button').hide();
   $('#done_button').hide();
   $('#editor').hide();
   $("#youtubeinput").keypress(function(event) {
      if ( event.which == 13 ) {
         if ($("#youtubeinput").val() == "") {

         } else {
            start_loader();

         }
      }
   });

   function start_loader(){
      event.preventDefault();
      window.video_id = youtube_parser($("#youtubeinput").val());
      var template_json = {"video_id": video_id};
      var template = _.template($("#player-template").html());
      $("#youtubeplayer").empty().append(template(template_json));
      $('#snapshot_button').show();
      $('#done_button').show();
      $('#timestamps').empty();

      $.getJSON('Youtube/getVideo.php?url='+window.video_id, function(data) {
         window.saved_video = data['filename'];
         window.image_urls = [];
         window.time_stamps = [];
         window.timeStampQueue = [];
         window.captions = [];
         $('#status').show();
        
         $('#status').html(data['filename']);
         

      });
   }

   function youtube_parser(url){
       var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
       var match = url.match(regExp);
       if (match&&match[7].length==11){
           return match[7];
       }else{
           alert("Url incorrecta");
       }
   }



});

function imageExtractAddQueue(timestamp){
   window.timeStampQueue.push(timestamp);
   imageExtractJquery();
}

function imageExtractJquery(){
   if (window.timeStampQueue.length > 0) {
      $.getJSON('youtube/getFrame.php?videofile='+window.saved_video+"&timestamp="+ window.timeStampQueue.pop(), function(data) {
         window.image_urls.push(data['filename']);
         $('#status').html(data);
         imageExtractJquery();
      });
   } 
}
function onYouTubePlayerReady(playerId) {
   player = document.getElementById("myytflashplayer");
   player.playVideo();

   $('#snapshot_button').mousedown(function() {
      window.time_stamps.push(player.getCurrentTime());
      imageExtractAddQueue(player.getCurrentTime());
      player.pauseVideo();
      $('#timestamps').append(player.getCurrentTime() + ", ");   
   });

   $('#snapshot_button').mouseup(function() {
      player.playVideo();
   });

   $('#done_button').click(function() {
      $('#editor').hide();
      $('#status').hide();
      $('#snapshot_button').hide();
      $('#done_button').hide();
      $('#editor').show();
      $("#youtubeplayer").hide();


      $("#thumbs").empty().show();
      for (i in window.image_urls) {
         var template_json = {"filename": window.image_urls[i],"id": i, "name":window.image_urls[i]};
         var template = _.template($("#image-template").html());
         $("#thumbs").append(template(template_json));

      }

      $('#editor').find('img').each(function(){
         $(this).bind('mouseup',function(e){ 
            //var caption = $(this).val();
            var index = parseInt($(this).parent().find('input').attr("id").substring(6));
            var caption = $(this).parent().find('input').val();
            
            var relativeX = e.pageX - $(this).position()['left'];
            var relativeY = e.pageY - $(this).position()['top'];

            doImageEdit(window.image_urls[index], relativeX, relativeY, caption, 20, this);



         });
      });
   });
   
   $('#process_button').click(function(i) {
      window.captions = [];
      var $i = 0;
      $('#editor').find('input').each(function(){
         var caption = $(this).val();
         window.captions.push(caption);

         doImageEdit(window.image_urls[$i], 20, 20, caption, 20);
         $i+=1;
      });



      //var myStuff = new Array("cow-dolphin.jpg", "cow-dolphin-altered.jpg");
      //doStitch(myStuff);


   });



}  


function doImageEdit(filename, x, y, text, fontsize, jquery_this) {
   $.ajax({
      url: "youtube/editImage.php",
      type: "GET",
      data: {filename: filename, x: x, y: y, text: text, fontsize: fontsize},
      contentType: "application/json",
      dataType: "json",
      success: function(filename)
      {
         console.log(filename);
         $(jquery_this).prop("src","youtube/"+filename);
      },
      error: function(xhr, textStatus, errorThrown)
      {
         alert("An error occured");
      }
   });
}

function doStitch(filenames)
{  
   var str = JSON.stringify(filenames);
   $.ajax({
      url: "server/stitchImages.php",
      type: "GET",
      data: "image_files="+str,
      contentType: "application/json",
      dataType: "json",
      success: function(filename)
      {
         console.log(filename);
      },
      error: function(xhr, textStatus, errorThrown)
      {
         alert("An error occured");
         console.log(errorThrown);
         console.log(textStatus);
         console.log(xhr);
      }
   });
}




