function doImageEdit(filename, x, y, text, fontsize)
{
	$.ajax({
		url: "server/editImage.php",
		type: "GET",
		data: {filename: filename, x: x, y: y, text: text, fontsize: fontsize},
		contentType: "application/json",
		dataType: "json",
		success: function(filename)
		{
			console.log(filename);
			/*var i = 0;
			
			while(i < images.length)
			{
				var row = $("<tr/>");
				for (var col = 0; col < (columns || 5) && i < images.length; col++)
				{
					
					if (isResultSet)
						row.append($("<td style='vertical-align:middle'><img onclick=\"getSimilarImages(" + images[i].id + ", '" + images[i].url + "')\" title='Get similar images' class='box' src='" + (images[i].url || "http://www.freephotobank.org/d/55503-3/Cannes-16.gif") + "'></img></td>"));
					else
						row.append($("<td style='vertical-align:middle'><img draggable='true' ondragstart=\"drag(" + images[i].id + ", '" + images[i].url + "')\" class='box' src='" + (images[i].url || "http://www.freephotobank.org/d/55503-3/Cannes-16.gif") + "'></img></td>"));
					i++;
				}
				photoTable.append(row);
			}*/
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