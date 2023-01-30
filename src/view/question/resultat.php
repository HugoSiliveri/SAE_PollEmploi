<div class="d-flex flex-column align-items-center bg-white p-5 rounded">
    <h1><?php echo $question->getIntituleQuestion() ?></h1>
    <p class="d-flex flex-column align-items-start w-100 mb-5 white-space text-justify"><?php echo nl2br($question->getDescriptionQuestion()) ?></p>

    <table class="table table-striped rounded rounded-3 overflow-hidden mt-4">
        <thead class="table-dark">
        <tr>
            <th scope="col">Propositions</th>
            <?php
            $etatQuestion = $question->getEtatQuestion();
            if ($etatQuestion == 8) echo "<th scope=\"col\">Résultat</th>";
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $etatQuestion = $question->getEtatQuestion();
        foreach ($propositions as $proposition) {
            echo "<tr class='border border-2'>
                          <td onclick=location.href='" . $url . "frontController.php?action=read&controller=proposition&id=" . rawurlencode($proposition->getIdProposition()) . "&idQuestion=" . rawurlencode($question->getIdQuestion()) . "'>" . htmlspecialchars($proposition->getIntituleProposition()) . "</td>";
            if ($etatQuestion == 8) {
                //echo "<td onclick=location.href='" . $url . "frontController.php?action=read&controller=proposition&id=" . rawurlencode($proposition->getIdProposition()) . "&idQuestion=" . rawurlencode($question->getIdQuestion()) .  "'>" . htmlspecialchars($proposition->getIntituleProposition()) . "</td>";
                echo "<td> Gagnée</td>";
            }
        }
        ?>
        </tbody>
    </table>