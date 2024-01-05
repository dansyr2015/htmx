<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "htmx";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$action=$_GET['action'];

$title=@$_POST['title'];
$content=@$_POST['content'];
$id=@$_POST['id'];

//var_dump($_POST);
//var_dump($_GET);

switch($action){
case 'add':
    try {
        header("HX-Trigger:post_add");
        $sql = "INSERT INTO posts (title,content)
        VALUES ('".$title."', '".$content."')";
        // use exec() because no results are returned
        $conn->exec($sql);?>
<div class="col-3">
<div class="card" style="height:200px;margin:20px;">
        <div class="card-img-overlay">
          <h4 class="card-title"><?=$title?></h4>
          <p class="card-text"><?=$content?></p>
          
          <a href="#" class="btn btn-primary">Edit</a>
          <a href="#" class="btn btn-danger">Delete</a>
    
        </div>
      </div>
</div>

      <?php 
      
      echo ('<div class="alert alert-success alert-dismissible">
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      <strong>Success!</strong> Record added successfully  </div>');

    } catch(PDOException $e) {
        echo ('<div class="alert alert-success alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <strong>Error!</strong> '.$sql . "<br>" . $e->getMessage().'
      </div>');
      }
break;
      case 'get_all':
        try {
        $stmt = $conn->prepare("SELECT * FROM posts");
        $stmt->execute();
      
        // set the resulting array to associative
        //$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result =$stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $k) {
            $id=$k['id'];
            $title=$k['title'];
            $content=$k['content'];

            ?>
            <div class="col-3" id="post_<?=$id?>" >
<div class="card" style="height:200px;margin:20px;">
        <div class="card-img-overlay">
          <h4 class="card-title"><?=$title?></h4>
          <p class="card-text"><?=$content?></p>
          
          <a hx-get="edit.php?id=<?=$id?>&title=<?=$title?>&content=<?=$content?>" class="btn btn-primary"  hx-target="#post_<?=$id?>">Edit</a>
          <a HX-Refresh="true" hx-post="api.php?action=del&id=<?=$id?>" hx-trigger="click" hx-target=".msg"  href="#" class="btn btn-danger">Delete</a>
    
        </div>
      </div>
</div>
        <?php }
    } catch(PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
      }
        break;

        case 'del':
            try {
                header("HX-Trigger:post_del");
                $id=$_GET['id'];
            // sql to delete a record
            $sql = "DELETE FROM posts WHERE id={$id}";

            
            // use exec() because no results are returned
            $conn->exec($sql);
            
            echo ('<div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong>Success!</strong> Record deleted successfully - id= '. $id.'
          </div>');
        } catch(PDOException $e) {
            echo ('<div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong>Error!</strong> '.$sql . "<br>" . $e->getMessage().'
          </div>');

         
          }
            break; 

            case 'edit':
                try {
                    header("HX-Trigger:post_update");

                    $title=@$_POST['title'];
                    $content=@$_POST['content'];
                    $id=@$_POST['id'];

                    $sql = "UPDATE posts SET title='".$title."',content='".$content."' WHERE id=".$id;

                    // Prepare statement
                    $stmt = $conn->prepare($sql);
                  
                    // execute the query
                    $stmt->execute();
                  
                    // echo a message to say the UPDATE succeeded
                    //echo $stmt->rowCount() . " records UPDATED successfully";
                
                echo ('<div class="alert alert-success alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong>Success!</strong>'.$stmt->rowCount().' Record UPDATED successfully - id= '. $id.'
              </div>');
            } catch(PDOException $e) {
                echo ('<div class="alert alert-success alert-dismissible">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong>Error!</strong> '.$sql . "<br>" . $e->getMessage().'
              </div>');
            }
                break;

default:
    echo "bad request";
}

?>