<?php
$tenues = [
    ["id" => 1, "nom" => "Tenue 1", "image" => "../tenu1.jpg"],
    ["id" => 2, "nom" => "Tenue 2", "image" => "../tenu2.jpg"],
    ["id" => 3, "nom" => "Tenue 3", "image" => "../tenu3.jpg"],
];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $votes = [];
    foreach ($tenues as $tenue) {
        if (isset($_POST['vote_' . $tenue['id']])) {
            $vote = $_POST['vote_' . $tenue['id']];
            $votes[$tenue['id']] = $vote;
        }
    }

    $existing_votes = [];
    if (file_exists('votes.json')) {
        $existing_votes = json_decode(file_get_contents('votes.json'), true);
    }

    foreach ($votes as $tenue_id => $vote) {
        if (isset($existing_votes[$tenue_id])) {
            $existing_votes[$tenue_id] += $vote;
        } else {
            $existing_votes[$tenue_id] = $vote;
        }
    }

    file_put_contents('votes.json', json_encode($existing_votes));

    echo "<p>Merci pour vos votes !</p>";
}

$votes = [];
if (file_exists('votes.json')) {
    $votes = json_decode(file_get_contents('votes.json'), true);
}

$max_votes = 0;
$winning_tenue = null;
foreach ($votes as $tenue_id => $total_votes) {
    if ($total_votes > $max_votes) {
        $max_votes = $total_votes;
        $winning_tenue = $tenue_id;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Défilé de Mode - Noter les Tenues</title>
</head>

<body>
    <h1>Défilé de Mode - Noter les Tenues</h1>

    <?php if ($winning_tenue !== null): ?>
        <h2>La tenue gagnante est : Tenue <?php echo $winning_tenue; ?></h2>
        <p>Elle a reçu le plus de votes avec <?php echo $max_votes; ?> votes !</p>
    <?php endif; ?>

    <form method="POST">
        <?php foreach ($tenues as $tenue): ?>
            <div>
                <h3><?php echo $tenue['nom']; ?></h3>
                <img src="<?php echo $tenue['image']; ?>" alt="<?php echo $tenue['nom']; ?>" width="300px">
                <p>Notez cette tenue : </p>
                <div class='selection'>
                    <label for="vote_<?php echo $tenue['id']; ?>">De 1 à 5 : </label>
                    <select name="vote_<?php echo $tenue['id']; ?>" required>
                        <option value="">Sélectionnez une note</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                </div>
                </select>
            </div>
            <hr>
        <?php endforeach; ?>

        <button type="submit">Soumettre vos votes</button>
    </form>

    <hr>

    <h2>Les résultats des votes :</h2>
    <?php foreach ($tenues as $tenue): ?>
        <h3><?php echo $tenue['nom']; ?></h3>
        <p>Votes : <?php echo isset($votes[$tenue['id']]) ? $votes[$tenue['id']] : 0; ?></p>
    <?php endforeach; ?>
    <style>
        body {
            text-align: center;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #000000;
            color: #FFFFFF;
            font-family: lato, serif;
        }

        h1 {
            font-weight: bold;
            align-items: center;
            font-size: 100px;
            margin: 150px;
            color: #FFFFFF;
        }

        label {
            padding: 10px 20px;
            border: none;
            box-shadow: 5px 5px 22px rgba(0, 0, 0, 0.188);
            border-radius: 25px;
            color: #000000;
            background-color: #FFFFFF;
        }

        button {
            background-color: #9F2B68;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: bold;
            transition: all 0.3s;
        }
    </style>
</body>

</html>
