<?php $data = json_decode(file_get_contents('data.json'), true); ?>
<!DOCTYPE html>
<html lang="mn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Миний танилцуулга</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Navbar -->
<nav class="sidebar">
  <h2>Миний CV</h2>
  <ul>
    <li><a href="#intro">Танилцуулга</a></li>
    <li><a href="#skills">Чадвар</a></li>
    <li><a href="#projects">Бүтээл</a></li>
    <li><a href="#contact">Холбоо барих</a></li>
  </ul>
</nav>

<!-- Main -->
<main>
  <!-- Intro Section -->
  <section id="intro" class="section">
    <div class="card center">
      <img src="<?= $data['intro']['image'] ?>" alt="profile">
      <h1><?= $data['intro']['name'] ?></h1>
      <p><?= $data['intro']['desc'] ?></p>
    </div>
  </section>

  <!-- Skills Section -->
  <section id="skills" class="section">
    <div class="card">
      <h2>Чадвар</h2>
      <ul>
        <?php foreach ($data['skills'] as $skill): ?>
          <li><strong><?= $skill['skill'] ?></strong> – <?= $skill['level'] ?></li>
        <?php endforeach; ?>
      </ul>

      <h3>Амжилт</h3>
      <ul>
        <?php foreach ($data['achievements'] as $ach): ?>
          <li><?= $ach ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <!-- Projects Section -->
  <section id="projects" class="section">
    <div class="card">
      <h2>Бүтээлүүд</h2>
      <ul>
        <?php foreach ($data['projects'] as $proj): ?>
          <li>
            <span><?= $proj['name'] ?></span>
            <a class="btn" href="<?= $proj['file'] ?>" download>Татах</a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="section">
    <div class="card">
      <h2>Холбоо барих</h2>
      <form method="POST" action="contact.php">
  <input type="text" name="name" placeholder="Таны нэр" required>
  <textarea name="message" placeholder="Таны зурвас" required></textarea>
  <button type="submit">Илгээх</button>
</form>

    </div>
  </section>
</main>

</body>
</html>
