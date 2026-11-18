<?php

if (!$form_admission) {

?>

    <div class="alert alert-danger">

        <?php echo $this->lang->line('admission_form_disable_please_contact_to_administrator'); ?>

    </div>

<?php

    return;
}

?>



<?php



if ($this->session->flashdata('msg')) {

    $message = $this->session->flashdata('msg');

    echo $message;
}

?>



<div class="row justify-content-center align-items-center flex-wrap d-flex pt20">

    <div class="col-md-8">

        <h3 class="entered mt0"><?php echo $this->lang->line('Online') . " " . $this->lang->line('register'); ?></h3>

    </div>



</div>