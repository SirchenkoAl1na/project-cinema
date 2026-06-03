<h1 class="page-title"><?php echo $title; ?></h1>

<div style="width:75%;color:white;">

</div>
<div class="row j-c-start flex-wrap">
        <?php

        foreach ($achievements as $achievement) {
            $class = '';

            if ($achievement['achieved']) {
                $class = 'achieved'; // кольорове
            } elseif (!is_null($achievement['date'])) {
                $class = 'in_progress'; // ч/б
            } else {
                $class = 'not_started'; // блідий
            }
            $goal=null;
            $current=null;
            if ($achievement['achievement']->number_for_goal != 1) {
                $goal = $achievement['achievement']->number_for_goal;
                $current = $achievement['current_level'];
                $percent = 0;
                if ($goal != 0) {
                    $percent = round($current / $goal * 100);
                }
            }
            ?>
        <div class="achievement-user-item" title="<?= $achievement['achievement']->number_for_goal != 1? $current.'/'.$goal:'' ?> (<?= $achievement['achievement']->discount ?>%)"> 
            <i class="fa-solid fa-circle-info info-icon" title="<?php echo $achievement['achievement']->level_description; ?>"></i>
            <img src="/Resources/img/achievements/<?php echo $class != 'not_started' ? $achievement['achievement']->image_title : 'not_started_achievement.png'; ?>" alt="<?php  echo $class != 'not_started' ? $achievement['achievement']->image_title : 'not_started_achievement.png'; ?>" class="<?php echo $class; ?>">
            
            <p><?php echo $achievement['achievement']->title; ?></p>
            <i><?=$achievement['achievement']->discount?> %</i>
            
            <?php
                if ($achievement['achievement']->number_for_goal != 1) {
                    ?>
            <div class="achievement-progress-bar">
                <div class="achievement-progress-bar-current" style="width:<?php echo $percent; ?>%;"></div>
            </div>
            <?php
                }
            ?>
        </div>
        <?php
        }
?>
            
    </div>