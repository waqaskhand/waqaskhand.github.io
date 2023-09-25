function UploadVideo() {
	//event.preventDefault();
	//event.stopPropagation();
	
	var fileInput = document.getElementById("file");
	var FileData = fileInput.files[0];
	var Data = new FormData();
	Data.append('Ajax', true);
	Data.append('file', FileData);
	
	$("#SubmitBtn").attr('disabled', 'disabled');
	$("#ProgressBar").css('display', 'block');
	
	var request = new XMLHttpRequest();
	
	request.upload.addEventListener('progress', function(event){
		if(event.lengthComputable)
		{
			var percent = event.loaded / event.total;
			var percentValue = Math.round(percent * 100) + '%';
			$("#PercentAge").html(percentValue);
			$("#ProgressBg").css('width', percentValue);
		}
	});
	
	request.upload.addEventListener('load', function(event){
		$("#PercentAge").html('Upload Complete');
		window.opener.location.reload();
		//window.close();
	});
	
	request.upload.addEventListener('error', function(event){
		$("#SubmitBtn").removeAttr('disabled');
		$("#ErrorMsg").html('Uploading failed. Please try again.');
	});
	
	request.addEventListener('readystatechange', function(event){
		if(this.readyState==4)
		{
			if(this.status==200)
			{
				$("#SubmitBtn").removeAttr('disabled');
				$("#ErrorMsg").html(this.response);
			}
			else
			{
				$("#SubmitBtn").removeAttr('disabled');
				$("#ErrorMsg").html('Uploading failed. Please try again.');
			}
		}
	});
	
	request.open('POST', 'save_file.php');
	request.setRequestHeader('Cache-Control', 'no-cache');
	request.send(Data);
	
	return false;
}