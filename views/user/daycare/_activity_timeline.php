 <?php
 function getActivityClass($serviceName)
 {
     $name = strtolower(trim($serviceName));
 
     if (in_array($name, ['breakfast11', 'lunch11', 'snacks'])) {
         return 'activity-food';
     } elseif ($name === 'nap/sleeping time') {
         return 'activity-nap';
     } elseif (in_array($name, ['music', 'singing', 'rhymes'])) {
         return 'activity-music';
     } elseif (in_array($name, ['diaper change'])) {
         return 'activity-care';
     } elseif (in_array($name, ['art & craft', 'playing with toys'])) {
         return 'activity-play';
     } else {
         return 'activity-default';
     }
 }
 ?>                                   

<?php if (!empty($activities)) : ?>
    <div class="custom-timeline">
        <?php foreach ($activities as $activity) : ?>
            <div class="timeline-item">
                <div class="timeline-time">
                    <?php echo !empty($activity['time']) ? date('h:i A', strtotime($activity['time'])) : ''; ?>
                </div>
                <div class="timeline-content">
                    <div class="timeline-dot"></div>

                    <div class="timeline-description <?php echo getActivityClass($activity['service_name'] ?? ''); ?>">
                        <?php if (!empty($activity['service_name'])): ?>
                            <strong><?php echo htmlspecialchars($activity['service_name']); ?></strong>
                            <?php if (!empty($activity['meal'])): ?>
                                – <?php echo htmlspecialchars($activity['meal']); ?>
                            <?php elseif (!empty($activity['activity_description'])): ?>
                                – <?php echo htmlspecialchars($activity['activity_description']); ?>
                            <?php elseif (!empty($activity['description'])): ?>
                                – <?php echo htmlspecialchars($activity['description']); ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php echo htmlspecialchars($activity['activity_description'] ?? $activity['description'] ?? ''); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="no-activity">No daycare activities recorded for this day.</div>
<?php endif; ?>

