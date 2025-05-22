<?php
session_start();

// Нэвтрэх эрх шалгах (админ хэсэг рүү шууд ордоггүй бол дараах мөрийг сэтгэж болох юм)
// if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }

$dataFile = 'data.json';

// POST хадгалах
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // JSON файл унших
    $data = json_decode(file_get_contents($dataFile), true);

    // Танилцуулга хэсэг
    $data['intro'] = [
        'image' => $_POST['intro_image'] ?? '',
        'name' => $_POST['intro_name'] ?? '',
        'desc' => $_POST['intro_desc'] ?? ''
    ];

    // Чадвар хэсэг (нэр ба түвшин массив)
    $skills = [];
    if (!empty($_POST['skill_name']) && !empty($_POST['skill_level'])) {
        foreach ($_POST['skill_name'] as $key => $skillName) {
            $level = $_POST['skill_level'][$key] ?? '';
            if ($skillName !== '') {
                $skills[] = ['skill' => $skillName, 'level' => $level];
            }
        }
    }
    $data['skills'] = $skills;

    // Амжилт (амжилтын массив)
    $achievements = array_filter($_POST['achievements'] ?? [], fn($v) => $v !== '');
    $data['achievements'] = array_values($achievements);

    // Бүтээлүүд (нэр, файл массив)
    $projects = [];
    if (!empty($_POST['project_name']) && !empty($_POST['project_file'])) {
        foreach ($_POST['project_name'] as $key => $projName) {
            $projFile = $_POST['project_file'][$key] ?? '';
            if ($projName !== '') {
                $projects[] = ['name' => $projName, 'file' => $projFile];
            }
        }
    }
    $data['projects'] = $projects;

    // Холбоо барих хэсэг
    $data['contact'] = [
        'email' => $_POST['contact_email'] ?? 'zzayanaa171@gmail.com'
    ];

    // JSON болгон буцааж бичих
    file_put_contents($dataFile, json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

    header('Location: admin.php?success=1');
    exit();
}

// GET үед файл унших
$data = json_decode(file_get_contents($dataFile), true) ?: [];

function safe($val) {
    return htmlspecialchars($val ?? '');
}
?>

<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Админ - Сайт засварлах</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .remove-btn { cursor: pointer; }
    </style>
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4">Сайт агуулга засварлах</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Амжилттай хадгалагдлаа!</div>
    <?php endif; ?>

    <form method="POST">

        <!-- Танилцуулга -->
        <h4>Танилцуулга</h4>
        <div class="mb-3">
            <label>Зураг URL:</label>
            <input type="text" name="intro_image" class="form-control" value="<?= safe($data['intro']['image'] ?? '') ?>" />
        </div>
        <div class="mb-3">
            <label>Нэр:</label>
            <input type="text" name="intro_name" class="form-control" value="<?= safe($data['intro']['name'] ?? '') ?>" />
        </div>
        <div class="mb-3">
            <label>Товч танилцуулга:</label>
            <textarea name="intro_desc" class="form-control" rows="3"><?= safe($data['intro']['desc'] ?? '') ?></textarea>
        </div>

        <hr>

        <!-- Чадварууд -->
        <h4>Чадварууд</h4>
        <div id="skills-container">
            <?php
            $skills = $data['skills'] ?? [];
            if (empty($skills)) $skills = [['skill'=>'', 'level'=>'']];
            foreach ($skills as $index => $skill): ?>
                <div class="row mb-2 skill-item">
                    <div class="col">
                        <input type="text" name="skill_name[]" placeholder="Чадварын нэр" class="form-control" value="<?= safe($skill['skill']) ?>" />
                    </div>
                    <div class="col">
                        <input type="text" name="skill_level[]" placeholder="Түвшин" class="form-control" value="<?= safe($skill['level']) ?>" />
                    </div>
                    <div class="col-1 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-skill-btn">X</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addSkill()">Чадвар нэмэх</button>

        <hr>

        <!-- Амжилтууд -->
        <h4>Амжилтууд</h4>
        <div id="achievements-container">
            <?php
            $achievements = $data['achievements'] ?? [''];
            foreach ($achievements as $ach): ?>
                <div class="input-group mb-2 achievement-item">
                    <input type="text" name="achievements[]" class="form-control" value="<?= safe($ach) ?>" placeholder="Амжилт" />
                    <button type="button" class="btn btn-danger remove-achievement-btn">X</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addAchievement()">Амжилт нэмэх</button>

        <hr>

        <!-- Бүтээлүүд -->
        <h4>Бүтээлүүд</h4>
        <div id="projects-container">
            <?php
            $projects = $data['projects'] ?? [['name'=>'','file'=>'']];
            foreach ($projects as $proj): ?>
                <div class="row mb-2 project-item">
                    <div class="col">
                        <input type="text" name="project_name[]" placeholder="Бүтээлийн нэр" class="form-control" value="<?= safe($proj['name']) ?>" />
                    </div>
                    <div class="col">
                        <input type="text" name="project_file[]" placeholder="Файл эсвэл холбоос" class="form-control" value="<?= safe($proj['file']) ?>" />
                    </div>
                    <div class="col-1 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-project-btn">X</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="btn btn-secondary mb-3" onclick="addProject()">Бүтээл нэмэх</button>

        <hr>

        <!-- Холбоо барих -->
        <h4>Холбоо барих</h4>
        <div class="mb-3">
            <label>И-мэйл хаяг:</label>
            <input type="email" name="contact_email" class="form-control" value="<?= safe($data['contact']['email'] ?? 'zzayanaa171@gmail.com') ?>" />
        </div>

        <button type="submit" class="btn btn-primary">Хадгалах</button>
    </form>
</div>

<script>
    // Чадвар нэмэх, устгах
    function addSkill() {
        const container = document.getElementById('skills-container');
        const div = document.createElement('div');
        div.classList.add('row', 'mb-2', 'skill-item');
        div.innerHTML = `
            <div class="col"><input type="text" name="skill_name[]" placeholder="Чадварын нэр" class="form-control" /></div>
            <div class="col"><input type="text" name="skill_level[]" placeholder="Түвшин" class="form-control" /></div>
            <div class="col-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm remove-skill-btn">X</button>
            </div>
        `;
        container.appendChild(div);
        div.querySelector('.remove-skill-btn').addEventListener('click', () => div.remove());
    }
    document.querySelectorAll('.remove-skill-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.target.closest('.skill-item').remove();
        });
    });

    // Амжилт нэмэх, устгах
    function addAchievement() {
        const container = document.getElementById('achievements-container');
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-2', 'achievement-item');
        div.innerHTML = `
            <input type="text" name="achievements[]" class="form-control" placeholder="Амжилт" />
            <button type="button" class="btn btn-danger remove-achievement-btn">X</button>
        `;
        container.appendChild(div);
        div.querySelector('.remove-achievement-btn').addEventListener('click', () => div.remove());
    }
    document.querySelectorAll('.remove-achievement-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.target.closest('.achievement-item').remove();
        });
    });

    // Бүтээл нэмэх, устгах
    function addProject() {
        const container = document.getElementById('projects-container');
        const div = document.createElement('div');
        div.classList.add('row', 'mb-2', 'project-item');
        div.innerHTML = `
            <div class="col"><input type="text" name="project_name[]" placeholder="Бүтээлийн нэр" class="form-control" /></div>
            <div class="col"><input type="text" name="project_file[]" placeholder="Файл эсвэл холбоос" class="form-control" /></div>
            <div class="col-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm remove-project-btn">X</button>
            </div>
        `;
        container.appendChild(div);
        div.querySelector('.remove-project-btn').addEventListener('click', () => div.remove());
    }
    document.querySelectorAll('.remove-project-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.target.closest('.project-item').remove();
        });
    });
</script>

</body>
</html>
