<?php if($lecturerlist): ?>
<div class="b-lecturers">
    <table summary="Submitted table designs">
        <caption>Профессорско-преподавательский состав</caption>
        <thead>
            <tr>
                <th scope="col">Ф.И.О.</th>
                <th scope="col">Должность</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach($lecturerlist as $lecturer): $class = ($i % 2 == 0) ? "odd" : ""; ?>
            <tr class="<?=$class?>">
                <th scope="row"><a href="<?=$hostname?>abiturient/lecturers/<?=$lecturer->linkname?>/"><?=$lecturer->header?></a></th>
                <td><?=$lecturer->additional?></td>
            </tr>
            <?php $i++; endforeach;?>
        </tbody>
    </table>
</div>
<?php endif;?>