<?php
$msg = '';
if (isset($_GET['msg'])) {
$msg = $_GET['msg'];
}

$clientId = '';

if (isset($_GET['deleteimg'])) {
if (!empty($_GET['deleteimg'])) {

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.imgur.com/3/image/'.$_GET['deleteimg'],
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'DELETE',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Client-ID '.$clientId
  ),
));

$response = curl_exec($curl);
curl_close($curl);
header("location:?msg=$response");
}
}



if (isset($_POST['submit'])) {
if (isset($_FILES['image'])) {
$postFields = array('image' => base64_encode(file_get_contents($_FILES['image']['tmp_name'])));

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image'); 
curl_setopt($ch, CURLOPT_POST, TRUE); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID '.$clientId)); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields); 
$response = curl_exec($ch); 
curl_close($ch); 
$res = json_decode($response);


if ($res->success == 1) {
$img = $res->data->link;
$deletehash = $res->data->deletehash;
$msg = 'Upload Success!';
}


}
}
?>
<center>
<h1>Upload Image To Imgur Using API</h1>

<p><?= $msg; ?></p><br>

<form method="post" action="" class="form" enctype="multipart/form-data">
    <div class="form-group">
        <label>Image</label>
        <input type="file" name="image" class="form-control" required>
    </div>
    <br>
    <div class="form-group">
        <input type="submit" class="form-control btn-primary" name="submit" value="Upload"/>
    </div>
</form>
<?php if (isset($img)) { ?>
<br><br>
<img src="<?= $img; ?>">
<br>
<a href="?deleteimg=<?= $deletehash; ?>">Delete Img</a>
<?php } ?>
</center>