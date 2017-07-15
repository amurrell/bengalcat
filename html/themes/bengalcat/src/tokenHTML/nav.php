<?php if (empty($data) || !is_array($data)) : return; endif; ?>

<ul>

<?php foreach ($data as $item) :

    $attributeArr = $item['attributes'];
    $attributes = array_reduce(array_keys($attributeArr),
        function($carry, $key) use ($attributeArr) {
            return "$carry  $key=\"$attributeArr[$key]\"";
        }
    );

    ?>

    <li>
        <a <?= $attributes; ?>>

            <?php if (isset($item['fontawesomeIcon'])) : ?>

            <span class="fa fa-<?= $item['fontawesomeIcon']; ?>"></span>

            <?php endif; ?>

            <?= $item['display']; ?>

        </a>
    </li>

<?php endforeach; ?>

</ul>