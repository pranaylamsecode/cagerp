<!-- 

<div class="row justify-content-center align-items-center flex-wrap d-flex pt20">

    <div class="col-md-8">

        <h3 class="entered mt0">Online register</h3>

    </div>



</div> -->

<?php
if ($this->session->flashdata('msg')) {
    $message = $this->session->flashdata('msg');
    echo $message;
}
?>

<div class="container">
    <div class="row justify-content-center align-items-center flex-wrap d-flex pt20">
        <div class="col-md-8">
            <h3 class="entered mt0 text-center">Online Register</h3>
        </div>
    </div>

    <div class="row justify-content-center align-items-center">
        <div class="col-md-6">
            <form id="register_form" class="spaceb60 spacet40 onlineform" action="<?php echo site_url('student/submit'); ?>" method="post" accept-charset="utf-8">
                <div class="form-group">
                    <label><?php echo $this->lang->line('first_name'); ?></label><small class="req"> *</small>
                    <input type="text" name="first_name" class="form-control" value="<?php echo set_value('first_name'); ?>" autocomplete="off" />
                    <span class="text-danger"><?php echo form_error('first_name'); ?></span>
                </div>
                <div class="form-group">
                    <label><?php echo $this->lang->line('last_name'); ?></label><small class="req"> *</small>
                    <input type="text" name="last_name" class="form-control" value="<?php echo set_value('last_name'); ?>" autocomplete="off" />
                    <span class="text-danger"><?php echo form_error('last_name'); ?></span>
                </div>

                <div class="form-group">
                    <label><?php echo $this->lang->line('mobile_no'); ?></label><small class="req"> *</small>
                    <input type="number" name="mobile_no" class="form-control" value="<?php echo set_value('mobile_no'); ?>" autocomplete="off" />
                    <span class="text-danger"><?php echo form_error('mobile_no'); ?></span>
                </div>

                <div class="form-group">
                    <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                    <select name="class_id" class="form-control" id="class_id">
                        <option value="">Select</option>
                        <?php foreach ($classlist as $class) { ?>
                            <option value="<?php echo $class['id']; ?>"><?php echo $class['class']; ?></option>
                        <?php } ?>
                    </select>
                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                </div>


                <!-- <div class="col-md-3">

                    <div class="form-group">

                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>

                        <select id="class_id" name="class_id" class="form-control">

                            <option value=""><?php echo $this->lang->line('select'); ?></option>

                            <?php

                            foreach ($classlist as $class) {

                            ?>

                                <option value="<?php echo $class['id'] ?>" <?php

                                                                            if (set_value('class_id') == $class['id']) {

                                                                                echo "selected=selected";
                                                                            }

                                                                            ?>><?php echo $class['class'] ?></option>

                            <?php

                            }

                            ?>

                        </select>

                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>

                    </div>

                </div> -->



                <!-- <div class="col-md-3">

                    <div class="form-group">

                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>

                        <select id="section_id" name="section_id" class="form-control">

                            <option value=""><?php echo $this->lang->line('select'); ?></option>

                        </select>

                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>

                    </div>

                </div> -->
                <div class="form-group">
                    <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                    <select name="section_id" class="form-control" id="section_id">
                        <option value="">Select Class first</option>
                        Options will be populated based on selected class using JavaScript
                    </select>
                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                </div>
                <div class="form-group">
                    <label><?php echo $this->lang->line('email'); ?></label><small class="req"> *</small>
                    <input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" autocomplete="off" />
                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                </div>

                <div class="form-group">
                    <label><?php echo $this->lang->line('dob'); ?></label><small class="req"> *</small>
                    <input type="date" name="dob" class="form-control" value="<?php echo set_value('dob'); ?>" autocomplete="off" />
                    <span class="text-danger"><?php echo form_error('dob'); ?></span>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('class_id').addEventListener('change', function() {
        var classId = this.value;

        // var section_id = '<?php echo set_value('section_id', 0) ?>';
        if (classId) {
            fetchSemesters(classId);
        } else {
            document.getElementById('section_id').innerHTML = '<option value="">Select</option>';
        }
    });

    function fetchSemesters(classId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '<?php echo site_url('sections/getByClass?class_id='); ?>' + classId, true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var semesters = JSON.parse(xhr.responseText);
                var semesterSelect = document.getElementById('section_id');
                semesterSelect.innerHTML = '<option value="">Select</option>';
                console.log(semesters);
                semesters.forEach(function(semester) {
                    var option = document.createElement('option');
                    option.value = semester.id;
                    option.textContent = semester.section;
                    semesterSelect.appendChild(option);
                });
            }
        };
        xhr.send('class_id=' + classId);
    }
</script>







<style>
    .container {
        margin-top: 50px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-block {
        display: block;
        width: 100%;
    }
</style>