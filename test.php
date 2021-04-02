<?php
  session_start();
  if ($_SESSION[Username] && !empty($_SESSION[Username])) {
      if (!$_GET[page]) {
          header('Location: galerie.php?page=1');
      }
  } else {
      header('Location: index.php?err=Vous devez vous connectez pour acceder a cette page.');
  }
    include_once 'config/database.php';
    $start = ($_GET[page] - 1) * 10;

    try {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare('SELECT * FROM snap LIMIT 10 OFFSET :start');
        $sth->bindParam(':start', $start, PDO::PARAM_INT);
        $sth->execute();
    } catch (PDOException $e) {
        echo 'Error: '.$e->getMessage();
        exit;
    }

    $result = $sth->fetchAll();
    if (!$result) {
        if ($_GET[page] > 1) {
            $prev = $_GET[page] - 1;
            header("Location: galerie.php?page=$prev");
            exit();
        } else {
            echo "<span class='empty'>La galerie est vide.</span>";
        }
    }
    include_once 'header.php';
    ?>
      <title>Camagru - Galerie</title>
      <article class='main'>
      <div class=container>
      <?php
      foreach ($result as $key => $value) {
          echo "<div class='fleximgbox'>";
          try {
              $sth = $dbh->prepare('SELECT COUNT(*) FROM likes WHERE img_id = :img_id');
              $sth->bindParam(':img_id', $value[id], PDO::PARAM_INT);
              $sth->execute();
          } catch (PDOException $e) {
              echo 'Error: '.$e->getMessage();
              exit;
          }
          $likes = $sth->fetchColumn();
          if ($value[login] == $_SESSION[Username]) {
              echo "<a href='user/remove_img.php?img=$value[id]&page=$_GET[page]'><img src='images/DeleteRed.png' width='42' style='position:absolute;'></a>";
          }
          echo "<img src='$value[img]' style='width:426px;'>
          <br/>
          Poster par : <i>$value[login]
          <br/></i>Likes: $likes
          <a href='user/ft_like.php?img_id=$value[id]&page=$_GET[page]' style='float:right; margin-top: -20px;'><img src='images/Like.png' width='42' height='42'></a>
          <form class='comment' action='user/ft_comment.php?img_id=$value[id]&page=$_GET[page]' method='post'><br/>
          <input class='form' style='width:79%;' type='text' placeholder='Entrez votre commentaire' name='comment' required>
          <button type='submit' class='button' style='width: auto;'>Valider</button>
          </form>";
          try {
              $sth = $dbh->prepare("SELECT * FROM comments WHERE img_id = $value[id]");
              $sth->execute();
          } catch (PDOException $e) {
              echo 'Error: '.$e->getMessage();
              exit;
          }
          $result = $sth->fetchAll();
          if ($result) {
              echo "<div class='comments'>";
              foreach ($result as $key => $value) {
                  echo "-> <i>$value[login]</i> <br/> $value[comment] <hr>";
              }
              echo '</div>';
          }
          echo '</div>';
      }
      echo '</div><center>
      <ul class="pagination">';
      try {
          $sth = $dbh->prepare('SELECT COUNT(*) FROM snap');
          $sth->execute();
      } catch (PDOException $e) {
          echo 'Error: '.$e->getMessage();
          exit;
      }
      $nbpage = ($sth->fetchColumn() - 1) / 10 + 1;
      $prev = $_GET[page] - 1;
      if ($prev > 0) {
          echo "<li><a href='?page=$prev'>«</a></li>";
      }
      for ($i = 1; $i <= $nbpage; ++$i) {
          echo "<li><a href='?page=$i'>$i</a></li>";
      }
      $next = $_GET[page] + 1;
      if ($next < $nbpage) {
          echo "<li><a href='?page=$next'>»</a></li>";
      }
      echo '</ul></center>';
    ?>
  </article>
</div>