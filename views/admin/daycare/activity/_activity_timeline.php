
<?php
$profile = $student_profile;
$activities = $activities ?? [];
?>

<div id="activity-report-content">
<!-- Student Summary Section -->
<div class="student-profile-box box box-widget widget-user mb-3">
    <div class="widget-user-header bg-aqua-active">
        <h3 class="widget-user-username"><?= $profile['firstname'] . ' ' . $profile['lastname'] ?> (<?= $profile['admission_no'] ?>)</h3>
        <h5 class="widget-user-desc">Guardian: <?= $profile['guardian_name'] ?> | <?= $profile['guardian_phone'] ?></h5>
        <h5 class="widget-user-desc">Status: <strong><?= $profile['status'] ?? 'N/A' ?></strong></h5>
    </div>
    <div class="widget-user-image">
        <img class="img-circle" src="<?= base_url($profile['image']) ?>" alt="Student Image" style="object-fit: cover; width: 80px; height: 80px;">
    </div>
</div>

<!-- Activities Section -->
<div class="box box-info">
    <div class="box-header with-border">
        <h4 class="box-title">Daycare Activities for <?= date('d M Y', strtotime( $date ?? date('Y-m-d'))) ?></h4>
    </div>
    <div class="box-body">
        <?php if (!empty($activities)) : ?>
            <?php foreach ($activities as $activity) : ?>
                <div class="activity-entry panel panel-default p-2 mb-2">
                    <div class="panel-heading">
                        <strong><?= !empty($activity['time']) ? $activity['time'] : date('H:i', strtotime($activity['date'])) ?></strong> -
                        <?= $activity['service_name'] ?: 'General Activity' ?>
                    </div>
                    <div class="panel-body">
                        <?php if (!empty($activity['meal'])) : ?>
                            <p><strong>Meal:</strong> <?= $activity['meal'] ?></p>
                        <?php endif; ?>
                        <?php if (!empty($activity['quantity'])) : ?>
                            <p><strong>Quantity:</strong> <?= $activity['quantity'] ?></p>
                        <?php endif; ?>
                        <?php if (!empty($activity['service_description'])) : ?>
                            <p><strong>Description:</strong> <?= $activity['service_description'] ?></p>
                        <?php elseif (!empty($activity['description'])) : ?>
                            <p><strong>Description:</strong> <?= $activity['description'] ?></p>
                        <?php else : ?>
                            <p class="text-muted">No detailed description available.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="alert alert-warning">No daycare activities found for this date.</div>
        <?php endif; ?>
    </div>
</div>

</div>
