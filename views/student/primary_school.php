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
            <h3 class="entered mt0 text-center">Online Admission</h3>
        </div>
    </div>

    <div class="row justify-content-center align-items-center">
        <div class="col-md-6">
            <form id="register_form" class="spaceb60 spacet40 onlineform" action="<?php echo site_url('student/online_admission1'); ?>" method="post" accept-charset="utf-8">


                <?php if ($online_admission_instruction != "") { ?>

                    <div class="printcontent">

                        <div class="row">

                            <h4 class="pagetitleh2"><?php echo $this->lang->line('instructions'); ?></h4>

                            <div class="col-md-12">

                                <div class="form-group">

                                    <?php echo $online_admission_instruction;  ?>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php } ?>
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



                <div class="form-group">
                    <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                    <select name="section_id" class="form-control" id="section_id">
                        <option value="">Select Class first</option>
                        Options will be populated based on selected class using JavaScript
                    </select>
                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                </div>

                <div class="form-group">
                    <label><?php echo $this->lang->line('firstname'); ?></label><small class="req"> *</small>
                    <input type="text" name="firstname" class="form-control" value="<?php echo set_value('firstname'); ?>" autocomplete="off" />
                    <span class="text-danger"><?php echo form_error('firstname'); ?></span>
                </div>
                <div class="form-group">
                    <label><?php echo $this->lang->line('lastname'); ?></label><small class="req"> *</small>
                    <input type="text" name="lastname" class="form-control" value="<?php echo set_value('lastname'); ?>" autocomplete="off" />
                    <span class="text-danger"><?php echo form_error('lastname'); ?></span>
                </div>


                <div class="form-group">

                    <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?></label><small class="req"> *</small>

                    <select class="form-control" name="gender">

                        <option value=""><?php echo $this->lang->line('select'); ?></option>

                        <?php

                        foreach ($genderList as $key => $value) {

                        ?>

                            <option value="<?php echo $key; ?>" <?php

                                                                if (set_value('gender') == $key) {

                                                                    echo "selected";
                                                                }

                                                                ?>><?php echo $value; ?></option>

                        <?php

                        }

                        ?>

                    </select>

                    <span class="text-danger"><?php echo form_error('gender'); ?></span>

                </div>


                <div class="form-group">
                    <label>Date of Birth
                        <!-- <?php echo $this->lang->line('dob'); ?></label> -->
                        <small class="req"> *</small>
                        <input type="date" name="dob" class="form-control" value="<?php echo set_value('dob'); ?>" autocomplete="off" />
                        <span class="text-danger"><?php echo form_error('dob'); ?></span>
                </div>









                <div class="form-group">
                    <label><?php echo $this->lang->line('mobileno'); ?></label><small class="req"> *</small>
                    <input type="number" name="mobileno" class="form-control" value="<?php echo set_value('mobileno'); ?>" autocomplete="off" />
                    <span class="text-danger"><?php echo form_error('mobileno'); ?></span>
                </div>






                <div class="form-group">
                    <label><?php echo $this->lang->line('email'); ?></label><small class="req"> *</small>
                    <input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" autocomplete="off" />
                    <span class="text-danger"><?php echo form_error('email'); ?></span>
                </div>


                <div class="row">

                    <?php if ($sch_setting->category) { ?>

                        <div class="col-md-2">

                            <div class="form-group">

                                <label for="exampleInputEmail1"><?php echo $this->lang->line('category'); ?></label>

                                <select id="category_id" name="category_id" class="form-control">

                                    <option value=""><?php echo $this->lang->line('select'); ?></option>

                                    <?php foreach ($categorylist as $category) { ?>

                                        <option value="<?php echo $category['id'] ?>" <?php

                                                                                        if (set_value('category_id') == $category['id']) {

                                                                                            echo "selected=selected";
                                                                                        }

                                                                                        ?>><?php echo $category['category'] ?></option>

                                    <?php $count++;
                                    }

                                    ?>

                                </select>

                                <span class="text-danger"><?php echo form_error('category_id'); ?></span>

                            </div>

                        </div>

                    <?php }
                    if ($sch_setting->religion) { ?>

                        <div class="col-md-2">

                            <div class="form-group">

                                <label for="exampleInputEmail1"><?php echo $this->lang->line('religion'); ?></label>

                                <input id="religion" name="religion" placeholder="" type="text" class="form-control" value="<?php echo set_value('religion'); ?>" />

                                <span class="text-danger"><?php echo form_error('religion'); ?></span>

                            </div>

                        </div>

                    <?php }
                    if ($sch_setting->cast) { ?>

                        <div class="col-md-2">

                            <div class="form-group">

                                <label for="exampleInputEmail1"><?php echo $this->lang->line('cast'); ?></label>

                                <input id="cast" name="cast" placeholder="" type="text" class="form-control" value="<?php echo set_value('cast'); ?>" />

                                <span class="text-danger"><?php echo form_error('cast'); ?></span>

                            </div>

                        </div>

                    <?php }

                    ?>

                </div>


                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('blood_group'); ?></label>

                    <?php

                    ?>

                    <select class="form-control" rows="3" placeholder="" id="blood_group" name="blood_group">

                        <option value=""><?php echo $this->lang->line('select') ?></option>

                        <?php foreach ($bloodgroup as $bgkey => $bgvalue) {

                        ?>

                            <option value="<?php echo $bgvalue ?>"><?php echo $bgvalue ?></option>



                        <?php } ?>

                    </select>



                    <span class="text-danger"><?php echo form_error('blood_group'); ?></span>

                </div>


                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('father_name'); ?></label>

                    <input id="father_name" name="father_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('father_name'); ?>" />

                    <span class="text-danger"><?php echo form_error('father_name'); ?></span>

                </div>



                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('father_phone'); ?></label>

                    <input id="father_phone" name="father_phone" placeholder="" type="text" class="form-control" value="<?php echo set_value('father_phone'); ?>" />

                    <span class="text-danger"><?php echo form_error('father_phone'); ?></span>

                </div>


                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('father_occupation'); ?></label>

                    <input id="father_occupation" name="father_occupation" placeholder="" type="text" class="form-control" value="<?php echo set_value('father_occupation'); ?>" />

                    <span class="text-danger"><?php echo form_error('father_occupation'); ?></span>

                </div>



                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_name'); ?></label>

                    <input id="mother_name" name="mother_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('mother_name'); ?>" />

                    <span class="text-danger"><?php echo form_error('mother_name'); ?></span>

                </div>


                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_phone'); ?></label>

                    <input id="mother_phone" name="mother_phone" placeholder="" type="text" class="form-control" value="<?php echo set_value('mother_phone'); ?>" />

                    <span class="text-danger"><?php echo form_error('mother_phone'); ?></span>

                </div>


                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('mother_occupation'); ?></label>

                    <input id="mother_occupation" name="mother_occupation" placeholder="" type="text" class="form-control" value="<?php echo set_value('mother_occupation'); ?>" />

                    <span class="text-danger"><?php echo form_error('mother_occupation'); ?></span>

                </div>


                <div class="row">

                    <div class="form-group col-md-12">

                        <label><?php echo $this->lang->line('if_guardian_is'); ?><small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>

                        <label class="radio-inline">

                            <input type="radio" name="guardian_is" <?php

                                                                    echo set_value('guardian_is') == "father" ? "checked" : "";

                                                                    ?> value="father"> <?php echo $this->lang->line('father'); ?>

                        </label>

                        <label class="radio-inline">

                            <input type="radio" name="guardian_is" <?php

                                                                    echo set_value('guardian_is') == "mother" ? "checked" : "";

                                                                    ?> value="mother"> <?php echo $this->lang->line('mother'); ?>

                        </label>

                        <label class="radio-inline">

                            <input type="radio" name="guardian_is" <?php

                                                                    echo set_value('guardian_is') == "other" ? "checked" : "";

                                                                    ?> value="other"> <?php echo $this->lang->line('other'); ?>

                        </label>

                        <span class="text-danger"><?php echo form_error('guardian_is'); ?></span>

                    </div>

                </div>


                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_name'); ?></label><small class="req"> *</small>

                    <input id="guardian_name" name="guardian_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_name'); ?>" />

                    <span class="text-danger"><?php echo form_error('guardian_name'); ?></span>

                </div>

                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_relation'); ?></label>

                    <input id="guardian_relation" name="guardian_relation" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_relation'); ?>" />

                    <span class="text-danger"><?php echo form_error('guardian_relation'); ?></span>

                </div>


                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_phone'); ?></label><small class="req"> *</small>

                    <input id="guardian_phone" name="guardian_phone" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_phone'); ?>" />

                    <span class="text-danger"><?php echo form_error('guardian_phone'); ?></span>

                </div>


                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_occupation'); ?></label>

                    <input id="guardian_occupation" name="guardian_occupation" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_occupation'); ?>" />

                    <span class="text-danger"><?php echo form_error('guardian_occupation'); ?></span>

                </div>

                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_email'); ?></label>

                    <input id="guardian_email" name="guardian_email" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_email'); ?>" />

                    <span class="text-danger"><?php echo form_error('guardian_email'); ?></span>

                </div>

                <div class="form-group">

                    <label for="exampleInputFile"><?php echo $this->lang->line('guardian'); ?> <?php echo $this->lang->line('photo'); ?></label>

                    <div><input class="filestyle form-control" type='file' name='guardian_pic' id="file" size='20' />

                    </div>

                    <span class="text-danger"><?php echo form_error('file'); ?></span>
                </div>

                <div class="form-group">

                    <label for="exampleInputEmail1"><?php echo $this->lang->line('guardian_address'); ?></label>

                    <textarea id="guardian_address" name="guardian_address" placeholder="" class="form-control" rows="2"><?php echo set_value('guardian_address'); ?></textarea>

                    <span class="text-danger"><?php echo form_error('guardian_address'); ?></span>

                </div>


                <div id='upload_documents_hide_show'>

                    <?php if ($sch_setting->upload_documents) { ?>

                        <div class="row">

                            <div class="col-md-12">

                                <div class="tshadow bozero">

                                    <h4 class="pagetitleh2"><?php echo $this->lang->line('upload_documents'); ?></h4>



                                    <div class="row around10">

                                        <div class="col-md-6">

                                            <table class="table">

                                                <tbody>
                                                    <tr>

                                                        <th style="width: 10px">#</th>

                                                        <th><?php echo $this->lang->line('title'); ?></th>

                                                        <th><?php echo $this->lang->line('documents'); ?></th>

                                                    </tr>

                                                    <tr>

                                                        <td>1.</td>

                                                        <td><input type="text" name='first_title' class="form-control" placeholder=""></td>

                                                        <td>

                                                            <input class="filestyle form-control" type='file' name='first_doc' id="doc1">

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td>2.</td>

                                                        <td><input type="text" name='second_title' class="form-control" placeholder=""></td>

                                                        <td>

                                                            <input class="filestyle form-control" type='file' name='second_doc' id="doc1">

                                                        </td>

                                                    </tr>



                                                </tbody>
                                            </table>

                                        </div>

                                        <div class="col-md-6">

                                            <table class="table">

                                                <tbody>
                                                    <tr>

                                                        <th style="width: 10px">#</th>

                                                        <th><?php echo $this->lang->line('title'); ?></th>

                                                        <th><?php echo $this->lang->line('documents'); ?></th>

                                                    </tr>

                                                    <tr>

                                                        <td>3.</td>

                                                        <td><input type="text" name='fourth_title' class="form-control" placeholder=""></td>

                                                        <td>

                                                            <input class="filestyle form-control" type='file' name='fourth_doc' id="doc1">

                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td>4.</td>

                                                        <td><input type="text" name='fifth_title' class="form-control" placeholder=""></td>

                                                        <td>

                                                            <input class="filestyle form-control" type='file' name='fifth_doc' id="doc1">

                                                        </td>

                                                    </tr>

                                                </tbody>
                                            </table>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php } ?>

                </div>






                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
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



    $("form-control .date").datetimepicker({
        format: 'yyyy-mm-dd hh:ii'
    });
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