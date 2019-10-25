<h1>Frases em <span id="language-name"></span></h1>


<p>Número de frases: <?= $sentence_count ?></p>

<?php foreach($sentences as $sentence) {
  echo "<div>".$sentence['HTML']."</div>";
} ?>
