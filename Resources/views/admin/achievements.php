<div class="block not-visible row j-c-end">
    <button class="button button-add" onclick="location.href='/admin/achievements/create'">Додати</button>
</div>
<!-- <div class="block options"></div> -->
<div class="block not-visible column">
    <?php
    use App\Data;
    foreach ($achievements as $achievement) {
    ?>
        <div class="achievement-item">
            <!-- style: row, j-c-be -->
            <div class="info">
                <img src="/Resources/img/achievements/<?= $achievement['image_title'] ?>" alt="<?= $achievement['image_title'] ?>">
                <div class="column j-c-around">
                    <div class="row j-c-start a-c-center">
                    <small style="cursor:pointer; margin-right:5px;" title="Тригер: <?= Data::$achievementsTrigers[$achievement['triger']] ?>"><i class="fa-solid fa-bell"></i></small>
                    <h4><?= $achievement['title'] ?></h4>
                    </div>
                    <i><?= $achievement['level_description'] ?></i>
            <small>-<?= $achievement['discount'] ?>% знижки</small>
                </div>
            </div>
            <div class="buttons a-c-center h-full">
                <button class="button button-icon button-edit" onclick="location.href='/admin/achievements/edit?id=<?= $achievement['id'] ?>'" title="Редагувати"><i class="fa-solid fa-pen-to-square"></i></button>
                <button class="button button-icon button-hide" onclick="DeleteAchievement(<?= $achievement['id'] ?>,0)" title="Приховати"><i class="fa-solid fa-trash"></i></button>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<script>
    function DeleteAchievement(id,numberOfUsing)
    {
        // todo
        let answer=confirm('Ви хочете архівувати це досягнення?\n Використано користувачами '+numberOfUsing+' разів');
        if(answer){
            location.href='/admin/achievements/delete?id='+id;
        }
    }
</script>