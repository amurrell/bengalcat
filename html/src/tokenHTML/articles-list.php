<?php

if (empty($data->articles)) {
    return;
}
?>

<table class="table">
    <thead>
        <tr>
            <th>id</th>
            <th>name</th>
        </tr>
    </thead>
    <tbody>

        <?php foreach ($data->articles as $article) : ?>
        <tr>
            <td>
                <?= $article->id; ?>
            </td>
            <td>
                <a href="[bc:base link]<?= $article->id; ?>/">
                    <?= $article->title; ?>
                </a>
            </td>
        </tr>
        <?php endforeach; ?>

    </tbody>
</table>

