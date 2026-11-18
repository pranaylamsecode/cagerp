<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <!-- Horizontal Form -->
        <form action="<?php echo site_url('admin/parentenquiry') ?>" id="myForm1" method="post" class="ptt10">

            <div class="printcontent">
                <div class="row">
                    <h4 class="pagetitleh2"><?php echo $this->lang->line('basic_details'); ?></h4>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="pwd"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                            <select id="class_id" name="class_id" class="form-control">
                                <option value="" selected=""><?php echo $this->lang->line('select') ?></option>
                                <?php
                                foreach ($class_list as $key => $value) {
                                ?>

                                    <option value="<?php echo $value['id'] ?>" <?php if (set_value('class_id', $enquiry_data['class_id']) == $value['id']) { ?> selected="" <?php } ?>><?php echo $value['class'] ?></option>

                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3 displaynone">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                            <select id="section_id" name="section_id" class="form-control">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                            </select>
                            <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="pwd"><?php echo $this->lang->line('first_name'); ?></label><small class="req"> *</small>
                            <input type="text" class="form-control" id="name_value" value="<?php echo set_value('firstname', $enquiry_data['firstname']); ?>" name="firstname">
                            <span class="text-danger"><?php echo form_error('firstname'); ?></span>
                        </div>
                    </div>



                    <?php if ($this->customlib->getenquiryfieldstatus('middlename')) { ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('middle_name'); ?></label>
                                <input id="middlename" name="middlename" placeholder="" type="text" class="form-control" value="<?php echo set_value('middlename', $enquiry_data['middlename']); ?>" autocomplete="off" />
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($this->customlib->getenquiryfieldstatus('lastname')) { ?>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="pwd"><?php echo $this->lang->line('last_name'); ?></label>
                                <input type="text" class="form-control" id="name_value" value="<?php echo set_value('lastname', $enquiry_data['lastname']); ?>" name="lastname">
                                <!-- <span class="text-danger" id="lastname"></span> -->
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($this->customlib->getenquiryfieldstatus('category')) { ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('category'); ?></label>
                                <select id="category_id" name="category_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($categorylist as $category) { ?>
                                        <option value="<?php echo $category['id'] ?>" <?php
                                                                                        if (set_value('category_id', $enquiry_data['category_id']) == $category['id']) {
                                                                                            echo "selected=selected";
                                                                                        }
                                                                                        ?>><?php echo $category['category'] ?>
                                        </option>
                                    <?php $count++;
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('gender'); ?></label><small class="req"> *</small>
                            <select class="form-control" name="gender">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($genderList as $key => $value) {
                                ?>
                                    <option value="<?php echo $key; ?>" <?php if (set_value('gender', $enquiry_data['gender']) == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('gender'); ?></span>
                        </div>
                    </div>



                    <div class="col-md-3">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('date_of_birth'); ?></label><small class="req"> *</small>
                            <input type="text" class="form-control date" value="<?php echo set_value('dob', date('d-m-Y', $this->customlib->datetostrtotime($enquiry_data['dob']))); ?>" id="dob" name="dob" readonly="readonly" />
                            <span class="text-danger"><?php echo form_error('dob'); ?></span>
                        </div>
                    </div>

                    <?php if ($this->customlib->getenquiryfieldstatus('mobile_no')) { ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('mobile_no'); ?></label>
                                <input type="text" class="form-control" value="<?php echo set_value('mobileno', $enquiry_data['guardian_phone']); ?>" id="mobileno" name="mobileno" autocomplete="off" />
                                <span class="text-danger"><?php echo form_error('mobileno'); ?></span>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ($this->customlib->getenquiryfieldstatus('student_email')) { ?>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('email'); ?></label><small class="req"> *</small>
                                <input type="text" value="<?php echo set_value('email', $enquiry_data['email']); ?>" name="email" class="form-control">
                                <span class="text-danger"><?php echo form_error('email'); ?></span>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('enquiry') . " " . $this->lang->line('date'); ?></label><small class="req"> *</small>
                            <input type="text" value="<?php echo set_value('enquiry_date', date('d-m-Y', $this->customlib->datetostrtotime($enquiry_data['enquiry_date']))); ?>" name="enquiry_date" class="form-control date" readonly="readonly">
                            <span class="text-danger"><?php echo form_error('enquiry_date'); ?></span>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('follow_up_date'); ?></label><small class="req"> *</small>
                            <input type="text" value="<?php echo set_value('follow_up_date', $enquiry_data['follow_up_date'] != null ? date('d-m-Y', $this->customlib->datetostrtotime($enquiry_data['follow_up_date'])) : " "); ?>" name="follow_up_date" class="form-control date">
                            <span class="text-danger"><?php echo form_error('follow_up_date'); ?></span>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="email"><?php echo $this->lang->line('description'); ?></label>
                            <textarea name="description" class="form-control"><?php echo set_value('description', $enquiry_data['description']); ?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                            <textarea name="note" class="form-control"><?php echo set_value('note', $enquiry_data['note']); ?></textarea>
                        </div>
                    </div>


                    <!-- //===================================added==================================================== -->
                    <?php if ($this->customlib->getenquiryfieldstatus('religion')) { ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('religion'); ?></label>
                                <input id="religion" name="religion" placeholder="" type="text" class="form-control" value="<?php echo set_value('religion', $enquiry_data['religion']); ?>" autocomplete="off" />
                                <span class="text-danger"><?php echo form_error('religion'); ?></span>
                            </div>
                        </div>
                    <?php }
                    if ($this->customlib->getenquiryfieldstatus('cast')) { ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('cast'); ?></label>
                                <input id="cast" name="cast" placeholder="" type="text" class="form-control" autocomplete="off" value="<?php echo set_value('cast', $enquiry_data['cast']); ?>" />
                                <span class="text-danger"><?php echo form_error('cast'); ?></span>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($this->customlib->getenquiryfieldstatus('is_student_house')) {  ?>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('house') ?></label>
                                <select class="form-control" rows="3" placeholder="" name="house">
                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                    <?php foreach ($houses as $hkey => $hvalue) {
                                    ?>
                                        <option value="<?php echo $hvalue["id"] ?>" <?php if (set_value('house', $enquiry_data['school_house_id']) == $hvalue["id"]) echo "selected"; ?>><?php echo $hvalue["house_name"] ?></option>

                                    <?php } ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('house'); ?></span>
                            </div>
                        </div>
                    <?php
                    }
                    ?>


                    <?php if ($this->customlib->getenquiryfieldstatus('is_blood_group')) { ?>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('blood_group'); ?></label>
                                <?php
                                ?>
                                <select class="form-control" rows="3" placeholder="" name="blood_group">
                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                    <?php foreach ($bloodgroup as $bgkey => $bgvalue) {
                                    ?>
                                        <option value="<?php echo $bgvalue ?>" <?php if (set_value('blood_group', $enquiry_data['blood_group']) == $bgvalue) echo "selected"; ?>><?php echo $bgvalue ?></option>

                                    <?php } ?>
                                </select>

                                <span class="text-danger"><?php echo form_error('blood_group'); ?></span>
                            </div>
                        </div>
                    <?php } ?>


                    <?php if ($this->customlib->getenquiryfieldstatus('student_height')) { ?>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('height'); ?></label>
                                <?php ?>
                                <input type="text" name="height" class="form-control" value="<?php echo set_value('height', $enquiry_data['height']); ?>" autocomplete="off">
                                <span class="text-danger"><?php echo form_error('height'); ?></span>
                            </div>
                        </div>
                    <?php }
                    if ($this->customlib->getenquiryfieldstatus('student_weight')) { ?>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('weight'); ?></label>
                                <input type="text" name="weight" class="form-control" value="<?php echo set_value('weight', $enquiry_data['weight']); ?>" autocomplete="off">
                                <span class="text-danger"><?php echo form_error('weight'); ?></span>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($this->customlib->getenquiryfieldstatus('measurement_date')) { ?>
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('measurement_date'); ?></label>

                                <input type="text" id="measure_date" value="<?php if (!empty($enquiry_data['measurement_date'])) echo set_value('measure_date', date('d-m-Y', $this->customlib->datetostrtotime($enquiry_data['measurement_date'])))
                                                                            ?>" name="measure_date" class="form-control date2" autocomplete="off" readonly="">
                                <span class="text-danger"><?php echo form_error('measure_date'); ?></span>
                            </div>
                        </div>
                    <?php } ?>



                    <div class="col-sm-4">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('assigned'); ?></label><small class="req"> *</small>
                            <select name="assigned" class="form-control">
                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                <?php foreach ($stff_list as $key => $stff_list_value) { ?>
                                    <option value="<?php echo $stff_list_value['id']; ?>" <?php if ($stff_list_value['id'] == $enquiry_data['assigned']) { ?>selected="" <?php } ?>><?php echo $stff_list_value['name'] . ' ' . $stff_list_value['surname']; ?></option>
                                <?php }   ?>
                            </select>
                            <input type="hidden" id="previous_assigned_staff" name="previous_assigned_staff" value="<?php echo set_value('previous_assigned_staff', $enquiry_data['assigned']); ?>">
                        </div>
                    </div>


                    <?php if ($this->customlib->getenquiryfieldstatus('source')) { ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pwd"><?php echo $this->lang->line('source'); ?></label><small class="req"> *</small>
                                <select name="source_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select') ?></option>
                                    <?php foreach ($source as $key => $value) { ?>
                                        <option value="<?php echo $value['id']; ?>" <?php
                                                                                    if ($enquiry_data['source_id'] == $value['id']) {
                                                                                        echo "selected";
                                                                                    }
                                                                                    ?>><?php echo $value['source']; ?></option>
                                    <?php }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- //================================================================ -->

                </div><!--./row-->


                <!-- <?php if ($this->customlib->getenquiryfieldstatus('student_photo')) { ?>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputFile"><?php echo $this->lang->line('student') . "  " . $this->lang->line('photo'); ?></label>
                                <input class="filestyle form-control" type='file' name='file' id="file" size='20' <?php if ($enquiry_data['image'] != "") { ?> data-default-file="<?php echo base_url() . $enquiry_data['image'] ?>" <?php } ?> />
                            </div>
                            <span class="text-danger"><?php echo form_error('file'); ?></span>
                        </div>
                    </div>
                <?php } ?> -->




                <div class="row">
                    <?php
                    echo display_enquiry_form_custom_fields('students', $enquiry_data['id']);
                    ?>
                </div>
            </div><!--./printcontent-->


            <?php if ($this->customlib->getenquiryfieldstatus('father_name') || $this->customlib->getenquiryfieldstatus('father_phone') || $this->customlib->getenquiryfieldstatus('father_occupation') || $this->customlib->getenquiryfieldstatus('father_pic') || $this->customlib->getenquiryfieldstatus('mother_name') || $this->customlib->getenquiryfieldstatus('mother_phone') || $this->customlib->getenquiryfieldstatus('mother_occupation') || $this->customlib->getenquiryfieldstatus('mother_pic')) { ?>
                <div class="printcontent">
                    <div class="row">
                        <h4 class="pagetitleh2"><?php echo $this->lang->line('parent_detail'); ?></h4>
                        <?php if ($this->customlib->getenquiryfieldstatus('father_name')) { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('father_name'); ?></label>
                                    <input id="father_name" name="father_name" placeholder="" type="text" class="form-control" autocomplete="off" value="<?php echo set_value('father_name', $enquiry_data['father_name']); ?>" />
                                    <span class="text-danger"><?php echo form_error('father_name'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->customlib->getenquiryfieldstatus('father_phone')) { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('father_phone'); ?></label>
                                    <input id="father_phone" name="father_phone" placeholder="" type="text" class="form-control" autocomplete="off" value="<?php echo set_value('father_phone', $enquiry_data['father_phone']); ?>" />
                                    <span class="text-danger"><?php echo form_error('father_phone'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->customlib->getenquiryfieldstatus('father_occupation')) { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('father_occupation'); ?></label>
                                    <input id="father_occupation" name="father_occupation" placeholder="" type="text" class="form-control" value="<?php echo set_value('father_occupation', $enquiry_data['father_occupation']); ?> " autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('father_occupation'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- <?php if ($this->customlib->getenquiryfieldstatus('father_pic')) { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('father'); ?> <?php echo $this->lang->line('photo'); ?></label>
                                    <div><input class="filestyle form-control" type='file' name='father_pic' id="file" size='20' />
                                    </div>
                                    <span class="text-danger"><?php echo form_error('father_pic'); ?></span>
                                </div>
                            </div>
                        <?php } ?> -->
                    </div><!---row-->
                    <div class="row">
                        <?php if ($this->customlib->getenquiryfieldstatus('mother_name')) { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('mother_name'); ?></label>
                                    <input id="mother_name" name="mother_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('mother_name', $enquiry_data['mother_name']); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('mother_name'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->customlib->getenquiryfieldstatus('mother_phone')) { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('mother_phone'); ?></label>
                                    <input id="mother_phone" name="mother_phone" placeholder="" type="text" class="form-control" value="<?php echo set_value('mother_phone', $enquiry_data['mother_phone']); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('mother_phone'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->customlib->getenquiryfieldstatus('mother_occupation')) { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('mother_occupation'); ?></label>
                                    <input id="mother_occupation" name="mother_occupation" placeholder="" type="text" class="form-control" value="<?php echo set_value('mother_occupation', $enquiry_data['mother_occupation']); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('mother_occupation'); ?></span>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- <?php if ($this->customlib->getenquiryfieldstatus('mother_pic')) { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('mother'); ?> <?php echo $this->lang->line('photo'); ?></label>
                                    <div><input class="filestyle form-control" type='file' name='mother_pic' id="file" size='20' />
                                    </div>
                                    <span class="text-danger"><?php echo form_error('mother_pic'); ?></span>
                                </div>
                            </div>
                        <?php } ?> -->
                    </div><!--./row-->
                </div><!--./printcontent-->
            <?php } ?>


            <?php if ($this->customlib->getenquiryfieldstatus('if_guardian_is')) { ?>
                <div class="printcontent">
                    <div class="row">
                        <h4 class="pagetitleh2"><?php echo $this->lang->line('guardian_details'); ?></h4>
                        <div class="form-group col-md-12">
                            <label><?php echo $this->lang->line('if_guardian_is'); ?><small class="req"> *</small>&nbsp;&nbsp;&nbsp;</label>
                            <label class="radio-inline">
                                <input type="radio" name="guardian_is" <?php
                                                                        echo set_value('guardian_is', $enquiry_data['guardian_is']) == "father" ? "checked" : "";
                                                                        ?> value="father"> <?php echo $this->lang->line('father'); ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="guardian_is" <?php
                                                                        echo set_value('guardian_is', $enquiry_data['guardian_is']) == "mother" ? "checked" : "";
                                                                        ?> value="mother"> <?php echo $this->lang->line('mother'); ?>
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="guardian_is" <?php
                                                                        echo set_value('guardian_is', $enquiry_data['guardian_is']) == "other" ? "checked" : "";
                                                                        ?> value="other"> <?php echo $this->lang->line('other'); ?>
                            </label>
                            <span class="text-danger"><?php echo form_error('guardian_is'); ?></span>
                        </div>

                        <?php if ($this->customlib->getenquiryfieldstatus('guardian_name')) { ?>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('guardian_name'); ?></label><small class="req"> *</small>
                                    <input id="guardian_name" name="guardian_name" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_name', $enquiry_data['guardian_name']); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('guardian_name'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->customlib->getenquiryfieldstatus('guardian_relation')) { ?>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('guardian_relation'); ?></label><small class="req"> *</small>
                                    <input id="guardian_relation" name="guardian_relation" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_relation', $enquiry_data['guardian_relation']); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('guardian_relation'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->customlib->getenquiryfieldstatus('guardian_email')) { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('guardian_email'); ?></label>
                                    <input id="guardian_email" name="guardian_email" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_email', $enquiry_data['guardian_email']); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('guardian_email'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                        <!-- <?php if ($this->customlib->getenquiryfieldstatus('guardian_photo')) { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('guardian'); ?> <?php echo $this->lang->line('photo'); ?></label>
                                    <div><input class="filestyle form-control" type='file' name='guardian_pic' id="file" size='20' />
                                    </div>
                                    <span class="text-danger"><?php echo form_error('guardian_pic'); ?></span>
                                </div>
                            </div>
                        <?php } ?> -->
                    </div><!--./row-->
                    <div class="row">
                        <?php if ($this->customlib->getenquiryfieldstatus('guardian_phone')) { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('guardian_phone'); ?></label><small class="req"> *</small>
                                    <input id="guardian_phone" name="guardian_phone" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_phone', $enquiry_data['guardian_phone']); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('guardian_phone'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->customlib->getenquiryfieldstatus('guardian_occupation')) { ?>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('guardian_occupation'); ?></label>
                                    <input id="guardian_occupation" name="guardian_occupation" placeholder="" type="text" class="form-control" value="<?php echo set_value('guardian_occupation', $enquiry_data['guardian_occupation']); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('guardian_occupation'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->customlib->getenquiryfieldstatus('guardian_address')) { ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('guardian_address'); ?></label>
                                    <textarea id="guardian_address" name="guardian_address" placeholder="" class="form-control" rows="1" autocomplete="off"><?php echo set_value('guardian_address', $enquiry_data['guardian_address']); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('guardian_address'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>


            <?php if ($this->customlib->getenquiryfieldstatus('current_address')  || $this->customlib->getenquiryfieldstatus('permanent_address')) { ?>
                <div class="printcontent">
                    <div class="row">
                        <h4 class="pagetitleh2"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('address'); ?> <?php echo $this->lang->line('details'); ?></h4>

                        <?php if ($this->customlib->getenquiryfieldstatus('current_address')) { ?>
                            <div class="col-md-6">
                                <?php if ($this->customlib->getenquiryfieldstatus('guardian_address')) { ?>
                                    <div class="checkbox">
                                        <label> <input type="checkbox" id="autofill_current_address" onclick="return auto_fill_guardian_address();" autocomplete="off">
                                            <?php echo $this->lang->line('if_guardian_address_is_current_address'); ?>
                                        </label>
                                    </div>
                                <?php } else {
                                    echo "<div class='checkbox'><label>&nbsp;</label></div>";
                                } ?>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('current_address'); ?></label>
                                    <textarea id="current_address" name="current_address" placeholder="" rows="1" class="form-control" autocomplete="off"><?php echo set_value('current_address', $enquiry_data['current_address']); ?><?php echo set_value('current_address'); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('current_address'); ?></span>
                                </div>
                            </div>
                        <?php }
                        if ($this->customlib->getenquiryfieldstatus('permanent_address')) { ?>
                            <div class="col-md-6">
                                <?php if ($this->customlib->getenquiryfieldstatus('current_address')) { ?>
                                    <div class="checkbox">
                                        <label> <input type="checkbox" id="autofill_address" onclick="return auto_fill_address();">
                                            <?php echo $this->lang->line('if_permanent_address_is_current_address'); ?> </label>
                                    </div>
                                <?php } else {
                                    echo  "<div class='checkbox'><label>&nbsp;</label></div>";
                                } ?>
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('permanent_address'); ?></label>
                                    <textarea id="permanent_address" name="permanent_address" rows="1" placeholder="" class="form-control" autocomplete="off"><?php echo set_value('permanent_address', $enquiry_data['permanent_address']); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('permanent_address'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <?php if ($this->customlib->getenquiryfieldstatus('bank_account_no') || $this->customlib->getenquiryfieldstatus('bank_name') || $this->customlib->getenquiryfieldstatus('ifsc_code') || $this->customlib->getenquiryfieldstatus('national_identification_no') || $this->customlib->getenquiryfieldstatus('local_identification_no') || $this->customlib->getenquiryfieldstatus('rte') || $this->customlib->getenquiryfieldstatus('previous_school_details') || $this->customlib->getenquiryfieldstatus('student_note')) { ?>
                <div class="printcontent">
                    <div class="row">
                        <h4 class="pagetitleh2"><?php echo $this->lang->line('miscellaneous_details'); ?></h4>
                        <?php if ($this->customlib->getenquiryfieldstatus('bank_account_no')) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('bank_account_no'); ?></label>
                                    <input id="bank_account_no" name="bank_account_no" placeholder="" type="text" class="form-control" value="<?php if (!empty($enquiry_data['bank_account_no'])) echo set_value('bank_account_no', $enquiry_data['bank_account_no']); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('bank_account_no'); ?></span>
                                </div>
                            </div>
                        <?php }
                        if ($this->customlib->getenquiryfieldstatus('bank_name')) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('bank_name'); ?></label>
                                    <input id="bank_name" name="bank_name" placeholder="" type="text" class="form-control" value="<?php if (!empty($enquiry_data['bank_name'])) echo set_value('bank_name', $enquiry_data['bank_name']); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('bank_name'); ?></span>
                                </div>
                            </div><?php }

                                if ($this->customlib->getenquiryfieldstatus('ifsc_code')) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('ifsc_code'); ?></label>
                                    <input id="ifsc_code" name="ifsc_code" placeholder="" type="text" class="form-control" value="<?php if (!empty($enquiry_data['ifsc_code'])) echo set_value('ifsc_code', $enquiry_data['ifsc_code']); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('ifsc_code'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>


                    <!-- <div class="row">
                        <?php if ($this->customlib->getenquiryfieldstatus('national_identification_no')) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> <?php echo $this->lang->line('national_identification_no'); ?> </label>
                                    <input id="adhar_no" name="adhar_no" placeholder="" type="text" class="form-control" value="<?php echo set_value('adhar_no'); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('adhar_no'); ?></span>
                                </div>
                            </div>
                        <?php }
                        if ($this->customlib->getenquiryfieldstatus('local_identification_no')) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> <?php echo $this->lang->line('local_identification_no'); ?> </label>
                                    <input id="samagra_id" name="samagra_id" placeholder="" type="text" class="form-control" value="<?php echo set_value('samagra_id'); ?>" autocomplete="off" />
                                    <span class="text-danger"><?php echo form_error('samagra_id'); ?></span>
                                </div>
                            </div>
                        <?php
                        }
                        if ($this->customlib->getenquiryfieldstatus('rte')) { ?>
                            <div class="col-md-4">
                                <label><?php echo $this->lang->line('rte'); ?></label>
                                <div class="radio" style="margin-top: 2px;">
                                <label><input class="radio-inline" type="radio" name="rte" value="Yes"  <?php
                                                                                                        echo set_value('rte', $enquiry_data['rte']) == "Yes" ? "checked" : "";
                                                                                                        ?>  ><?php echo $this->lang->line('yes'); ?></label>
                                                <label><input class="radio-inline" type="radio" name="rte" value="No" <?php
                                                                                                                        echo set_value('rte', $enquiry_data['rte']) == "No" ? "checked" : "";
                                                                                                                        ?>  ><?php echo $this->lang->line('no'); ?></label>
                                </div>
                                <span class="text-danger"><?php echo form_error('rte'); ?></span>
                            </div>
                        <?php } ?>
                    </div> -->




                    <div class="row">
                        <?php if ($this->customlib->getenquiryfieldstatus('previous_school_details')) { ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('previous_school_details'); ?></label>
                                    <textarea class="form-control" rows="1" placeholder="" name="previous_school" autocomplete="off"><?php echo set_value('previous_school', $enquiry_data['previous_school']); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('previous_school'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($this->customlib->getenquiryfieldstatus('student_note')) { ?>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('note'); ?></label>
                                    <textarea class="form-control" rows="1" placeholder="" name="note" autocomplete="off"><?php echo set_value('note', $enquiry_data['note']); ?></textarea>
                                    <span class="text-danger"><?php echo form_error('note'); ?></span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>






                </div>
            <?php } ?>




            <div class="row">
                <div class="box-footer col-md-12">

                    <div class="pull-right ptt10">
                        <a onclick="enrollEnquiry(<?php echo $enquiry_data['id'] ?>)" class="btn btn-info"><?php echo $this->lang->line('save_and_enroll'); ?></a>

                        <a onclick="postRecord(<?php echo $enquiry_data['id'] ?>)" class="btn btn-info"><?php echo $this->lang->line('save'); ?></a>

                    </div>

                </div>
            </div>


        </form>
    </div><!--./col-md-12-->
</div><!--./row-->


<script type="text/javascript">
    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

    $(document).ready(function() {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', 0) ?>';

        getSectionByClass(class_id, section_id);

        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            getSectionByClass(class_id, 0);
        });

        $('.date2').datepicker({
            autoclose: true,
            format: date_format,
            todayHighlight: true
        });
        $('.date').datepicker({
            autoclose: true,
            format: date_format,
            todayHighlight: true
        });

        function getSectionByClass(class_id, section_id) {

            if (class_id !== "") {
                $('#section_id').html("");

                var div_data = '';
                var url = "";

                $.ajax({
                    type: "POST",
                    url: base_url + "welcome/getSections",
                    data: {
                        'class_id': class_id
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $('#section_id').addClass('dropdownloading');
                    },
                    success: function(data) {
                        $.each(data, function(i, obj) {
                            var sel = "";
                            if (section_id === obj.section_id) {
                                sel = "selected";
                            }
                            div_data += "<option value=" + obj.id + " " + sel + ">" + obj.section + "</option>";
                        });
                        $('#section_id').append(div_data);
                    },
                    complete: function() {
                        $('#section_id').removeClass('dropdownloading');
                    }
                });
            }
        }
    });

    function auto_fill_guardian_address() {
        if ($("#autofill_current_address").is(':checked')) {
            $('#current_address').val($('#guardian_address').val());
        }
    }

    function auto_fill_address() {
        if ($("#autofill_address").is(':checked')) {
            $('#permanent_address').val($('#current_address').val());
        }
    }
    $('input:radio[name="guardian_is"]').change(
        function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if (value === "father") {
                    var father_relation = "<?php echo $this->lang->line('father'); ?>";
                    $('#guardian_name').val($('#father_name').val());
                    $('#guardian_phone').val($('#father_phone').val());
                    $('#guardian_occupation').val($('#father_occupation').val());
                    $('#guardian_relation').val(father_relation);
                } else if (value === "mother") {
                    var mother_relation = "<?php echo $this->lang->line('mother'); ?>";
                    $('#guardian_name').val($('#mother_name').val());
                    $('#guardian_phone').val($('#mother_phone').val());
                    $('#guardian_occupation').val($('#mother_occupation').val());
                    $('#guardian_relation').val(mother_relation);
                } else {
                    $('#guardian_name').val("");
                    $('#guardian_phone').val("");
                    $('#guardian_occupation').val("");
                    $('#guardian_relation').val("");
                }
            }
        });
</script>