<?php
// Include the database connection file
include '../adminquarterlyassessment/roxcon.php'; // Adjust the path to your connection file

// Fetch subjects for the dropdown
$subjects = [];
$result = $conn->query("SELECT id, 'subject' FROM subjects");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}

// Initialize search results
$competencies = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = intval($_POST['subject_id']);

    $stmt = $conn->prepare("SELECT * FROM tb_competencies WHERE subject = ?");
    $stmt->bind_param("i", $subject_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $competencies[] = $row;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Competencies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Search Competencies</h1>

        <form method="POST" class="mb-4">
            <div class="row mb-3">
                <label for="subject_id" class="form-label">Select Subject</label>
                <select name="subject_id" id="subject_id" class="form-select" required>
                    <option value="">-- Select Subject --</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['id'] ?>">
                            <?= htmlspecialchars($subject['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <?php if (!empty($competencies)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Grade Level</th>
                        <th>Subject</th>
                        <?php for ($i = 1; $i <= 30; $i++): ?>
                            <th>C<?= $i ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($competencies as $competency): ?>
                        <tr>
                            <td><?= htmlspecialchars($competency['gradelevel']) ?></td>
                            <td><?= htmlspecialchars($competency['subject']) ?></td>
                            <?php for ($i = 1; $i <= 30; $i++): ?>
                                <td><?= htmlspecialchars($competency['c' . $i]) ?></td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p class="text-danger">No competencies found for the selected subject.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
