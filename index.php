<?php
include 'config.php';
include 'Database.php';
$db = new Database();
?>
<!doctype html>
<html>
<head>
<title>Uploading Image File with PHP</title>
<style>
 body{font-family: verdana}
 .phpcoding{width: 900px;margin: 0 auto; background: #ddd;}
 .headeroption, .footeroption{background: #444;color: #fff;
  text-align: center;padding: 20px;}
 .headeroption h2, .footeroption h2{margin: 0}
 .mainoption{min-height: 420px;padding: 20px}
 .myform{width: 500px;border: 1px solid  #999;margin: 0 auto; 
  padding: 10px 20px 20px;}
 input[type="submit"],input[type="file"]{cursor: pointer}
</style>
</head>
<body>
<div class="phpcoding">
 <section class="headeroption">
  <h2>Uploading Image File with PHP</h2>
 </section>

 <section class="mainoption">
 <div class="myform">
  <form action="" method="post" enctype="multipart/form-data">
  <?php
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $permited  = array('jpg', 'jpeg', 'png', 'gif');
       $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_temp = $_FILES['image']['tmp_name'];
    $folder = "uploads/";
    $div = explode('.', $file_name);
    $file_ext = strtolower(end($div));
    $unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;
    $uploaded_image = "uploads/".$unique_image;

    move_uploaded_file($file_temp, $uploaded_image);
    $query = "INSERT INTO tbl_image(image) 
    VALUES('$uploaded_image')";
    $inserted_rows = $db->insert($query);
    if ($inserted_rows) {
     echo "<span class='success'>Image Inserted Successfully.
     </span>";
    }else {
     echo "<span class='error'>Image Not Inserted !</span>";
    }
   }
  ?>
   <table>
    <tr>
     <td>Select Image</td>
     <td><input type="file" name="image"/></td>
    </tr>
    <tr>
     <td></td>
     <td><input type="submit" name="submit" value="Upload"/></td>
    </tr>
   </table>
  </form>
  
  <table>
	<tr>
		<th Width="20%">No.</th>
		<th Width="42%">Image</th>
		<th Width="32%">Action</th>
	</tr>
  <?php
  if (isset($_GET['del'])) {
   $id = $_GET['del'];

   $getquery = "select * from tbl_image where id='$id'";
   $getImg = $db->select($getquery);
   if ($getImg) {
    while ($imgdata = $getImg->fetch_assoc()) {
    $delimg = $imgdata['image'];
    unlink($delimg);
    }
   }
   
   $query = "delete from tbl_image where id='$id'";
   $delImage = $db->delete($query);
   if ($delImage) {
     echo "<span class='success'>Image Deleted Successfully.
     </span>";
    }else {
     echo "<span class='error'>Image Not Deleted !</span>";
    }
   }
  ?>
<?php
   $query = "select * from tbl_image";
   $getImage = $db->select($query);
   if ($getImage) {
    $i=0;
    while ($result = $getImage->fetch_assoc()) {
    $i++;
    ?>
   <tr>
    <td Width="20%"><?php echo $i; ?></td>
    <td Width="50%"><img src="<?php echo $result['image']; ?>" height="40px" 
      width="50px"/></td>
    <td Width="30%"><a href="?del=<?php echo $result['id']; ?>">Delete</a></td>
   </tr>
  <?php } } ?>
  </table>
 </div>
 </section>
<section class="footeroption">
 <h2>CopyRight &copy Sarwar.</h2>
</section>
</div>
</body>
</html>