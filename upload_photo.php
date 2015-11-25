<!DOCTYPE html>
<html>
<body>
<form action="actions/action_upload_photo.php" method="post" enctype="multipart/form-data">
Select image to upload:
<input type="file" name="userfile">
<input type="hidden" name="source" value="user">
<input type="submit" value="Upload Image" name="submit">
</form>
</body>
</html>