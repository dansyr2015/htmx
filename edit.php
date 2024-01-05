<?php

$title=@$_GET['title'];
$content=@$_GET['content'];
$id=@$_GET['id'];
?>

<div class="col">
<div class="card">

<form hx-target="#post_<?=$id?>" style="padding:15px; height:250px;" hx-post="api.php?action=edit" hx-target=".xx" hx-swap="beforeend">
<input value="<?=$id?>" type="hidden" id="id"  name="id">

<div class="mb-3 mt-3">
  <label for="title">title:</label>
  <input value="<?=$title?>" type="text" class="form-control" id="title" placeholder="Enter title" name="title">
</div>

<div class="mb-3 mt-3">
    <label for="content">content:</label>
    <input value="<?=$content?>" type="text" class="form-control" id="content" placeholder="Enter content" name="content">
  </div>


<button type="submit" class="btn btn-primary">Edit</button>
</form>

</div>
</div>
