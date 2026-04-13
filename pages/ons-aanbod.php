<?php require "includes/header.php" ?>

<main>
    <h2>Ons aanbod</h2>
</main>
<?php require "includes/footer.php" ?>

<?php require "includes/header.php" ?>
<?php require "database/connection.php" ?>

<link rel="stylesheet" href="assets/css/ons-aanbod.css">


<?php
$where = [];
$params = [];

if (!empty($_GET['merk'])) {
    $where[] = "naam = :merk";
    $params[':merk'] = $_GET['merk'];
}

if (!empty($_GET['capaciteit'])) {
    $where[] = "capacity = :capaciteit";
    $params[':capaciteit'] = $_GET['capaciteit'];
}

$sql = "SELECT * FROM waggie";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$query = $conn->prepare($sql);
foreach ($params as $key => $value) {
    $query->bindValue($key, $value);
}
$query->execute();
$autos = $query->fetchAll(PDO::FETCH_ASSOC);

$merken      = $conn->query("SELECT DISTINCT naam FROM waggie ORDER BY naam")->fetchAll(PDO::FETCH_COLUMN);
$capaciteiten = $conn->query("SELECT DISTINCT capacity FROM waggie ORDER BY capacity")->fetchAll(PDO::FETCH_COLUMN);
?>

<main>
    <h2 class="section-title">Ons aanbod</h2>

    <div class="aanbod-layout">

        <form class="filter-sidebar" method="GET" action="/ons-aanbod">

            <h3>Filteren</h3>

            <div class="filter-group">
                <label for="merk">Automerk</label>
                <select name="merk" id="merk">
                    <option value="">Alle merken</option>
                    <?php foreach ($merken as $merk): ?>
                        <option value="<?= htmlspecialchars($merk) ?>"
                            <?= (isset($_GET['merk']) && $_GET['merk'] === $merk) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($merk) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="capaciteit">Aantal personen</label>
                <select name="capaciteit" id="capaciteit">
                    <option value="">Alle capaciteiten</option>
                    <?php foreach ($capaciteiten as $cap): ?>
                        <option value="<?= htmlspecialchars($cap) ?>"
                            <?= (isset($_GET['capaciteit']) && $_GET['capaciteit'] == $cap) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cap) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="button-primary">Filteren</button>

            <?php if (!empty($_GET)): ?>
                <a href="/ons-aanbod" class="filter-reset">Reset filters</a>
            <?php endif; ?>

        </form>

        <div class="aanbod-cars">
            <?php if (empty($autos)): ?>
                <p class="geen-resultaten">Geen auto's gevonden met deze filters.</p>
            <?php else: ?>
                <div class="cars">
                    <?php foreach ($autos as $car): ?>
                        <div class="car-details">
                            <div class="car-brand">
                                <h3><?= htmlspecialchars($car['naam']) ?></h3>
                                <div class="car-type"><?= htmlspecialchars($car['type']) ?></div>
                            </div>

                            <img src="assets/images/<?= htmlspecialchars($car['afbeelding']) ?>" alt="<?= htmlspecialchars($car['naam']) ?>">

                            <div class="car-specification">
                                <span><img src="assets/images/icons/gas-station.svg" alt=""><?= htmlspecialchars($car['benzine']) ?></span>
                                <span><img src="assets/images/icons/car.svg" alt=""><?= htmlspecialchars($car['schakel']) ?></span>
                                <span><img src="assets/images/icons/profile-2user.svg" alt=""><?= htmlspecialchars($car['capacity']) ?></span>
                            </div>

                            <div class="rent-details">
                                <span><span class="font-weight-bold">€<?= htmlspecialchars($car['prijs']) ?></span> / dag</span>
                                <a href="/car-detail" class="button-primary">Bekijk nu</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php require "includes/footer.php" ?>
