<style type="text/css">
    @media print {

        .no-print,
        .no-print * {
            display: none !important;
        }
    }

    .content-wrapper {
        background-color: #f8f9fa;
        padding: 20px;
    }

    .box-primary {
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .box-header {
        background-color: #4e73df;
        color: #fff;
        padding: 15px;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .box-title {
        font-size: 20px;
        font-weight: 600;
    }

    .box-body {
        padding: 20px;
        background-color: #fff;
    }

    .profile-user-img {
        width: 120px;
        height: 120px;
        border: 3px solid #4e73df;
        margin: 0 auto 20px;
    }

    .guardian-info {
        margin-top: 20px;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }

    .guardian {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .guardian-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #4e73df;
    }

    .button-container {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-success:hover,
    .btn-danger:hover {
        opacity: 0.9;
    }

    .modal-content {
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background-color: #4e73df;
        color: #fff;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 600;
    }

    .modal-body {
        padding: 20px;
    }

    .modal-footer {
        border-top: 1px solid #e3e6f0;
        padding: 15px;
    }

    .text-success {
        color: #28a745;
    }

    .text-danger {
        color: #dc3545;
    }

    .table-responsive {
        margin-top: 20px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 12px;
        border: 1px solid #e3e6f0;
    }

    .table th {
        background-color: #4e73df;
        color: #fff;
        font-weight: 600;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f8f9fa;
    }

    .table-hover tbody tr:hover {
        background-color: #e9ecef;
    }


    .custom-timeline {
        position: relative;
        padding-left: 30px;
        border-left: 2px solid #3498db;
        margin-top: 15px;
    }

    .timeline-item {
        margin-bottom: 25px;
        position: relative;
    }

    .timeline-time {
        font-size: 14px;
        font-weight: bold;
        color: #555;
        margin-bottom: 5px;
    }

    .timeline-content {
        display: flex;
        align-items: flex-start;
        position: relative;
    }

    .timeline-dot {
        width: 10px;
        height: 10px;
        background-color: #3498db;
        border-radius: 50%;
        margin-right: 10px;
        position: relative;
        top: 5px;
    }

    .timeline-description {
        font-size: 15px;
        color: #333;
        background: #f4f4f4;
        padding: 10px 15px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .no-activity {
        background-color: #f9f9f9;  
        border: 1px solid #ddd;
        padding: 20px;
        text-align: center;
        font-style: italic;
        color: #777;
        border-radius: 5px;
    }

    .activity-food {
        background-color: #fff8e1;
        border-left: 4px solid #ff9800;
    }

    .activity-nap {
        background-color: #e3f2fd;
        border-left: 4px solid #2196f3;
    }

    .activity-music {
        background-color: #fce4ec;
        border-left: 4px solid #e91e63;
    }

    .activity-care {
        background-color: #ede7f6;
        border-left: 4px solid #673ab7;
    }

    .activity-play {
        background-color: #e8f5e9;
        border-left: 4px solid #4caf50;
    }

    .activity-default {
        background-color: #f4f4f4;
        border-left: 4px solid #9e9e9e;
    }

</style>


<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
// header("refresh: 60"); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> Daycare Status
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border d-flex justify-content-between align-items-center">
                        <h3 class="box-title">Daycare Status</h3>
                        <span style="font-size: 18px; color: #fff;">
                            <?php echo   '( '.date('l, d M Y') .' )'; ?>
                        </span>
                        <button type="button" id="refresh-status" class="btn btn-xs btn-default" title="Refresh Status">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </div>
                    <div class="box-body">
                        <div id="daycare-status-content">
                            <?php $this->load->view('user/daycare/daycare_status_partial', $data); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="box box-primary">
                 
                        <div class="box-header ptbnull" style="display: flex; justify-content: center; align-items: center; gap: 10px; flex-direction: row;">
                            <h3 class="box-title titlefix" style="margin: 0;">Activity</h3>
                            
                            <input type="date" id="activity-date-picker" class="form-control input-sm" style="max-width: 180px;" />
                            
                            <button type="button" id="refresh-day" class="btn btn-xs btn-default" title="Refresh Activities">
                                <i class="fa fa-refresh"></i>
                            </button>

                            <button class="btn btn-sm btn-primary" onclick="printActivities()">
                                <i class="fa fa-print"></i> Print
                            </button>

                            <button class="btn btn-sm btn-success" onclick="downloadPdf()">
                                <i class="fa fa-download"></i> PDF
                            </button>

                        </div>

                 
                    <div class="box-body">
                        <div id="daycare-activities-container">
                            <?php $this->load->view('user/daycare/_activity_timeline', ['activities' => $activities]); ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    $(document).ready(function() {

        loadDaycareStatus(); 

        $('#refresh-status').on('click', function () {
            loadDaycareStatus(); 
         });
                
        // $('.grant-button').click(function() {
        //     var studentId = $(this).data('id');
        //     $('#permissionModal').data('student-id', studentId).modal('show');
        // });

        // $('.deny-button').click(function() {
        //     var studentId = $(this).data('id');
        //     $('#denyModal').data('student-id', studentId).modal('show');
        // });

        // $('#confirmGrantButton').click(function() {
        //     var studentId = $('#permissionModal').data('student-id');

        //     $.ajax({
        //         url: '<?php echo site_url('user/user/grant_permission'); ?>',
        //         type: 'POST',
        //         data: {
        //             student_id: studentId
        //         },
        //         dataType: 'JSON',
        //         success: function(response) {

        //             console.log(response);

        //             if (response.status == "success") {
        //                 $('#permissionModal').modal('hide');
        //                 successMsg(response.message);
        //                 location.reload();
        //             } else {
        //                 alert('Error occurred while granting permission.');
        //             }
        //         },
        //         error: function() {
        //             alert('Something went wrong. Please try again.');
        //         }
        //     });
        // });


        // $('#confirmDenyButton').click(function() {
        //     var studentId = $('#denyModal').data('student-id');
        //     $.ajax({
        //         url: '<?php echo site_url('user/user/deny_permission'); ?>',
        //         type: 'POST',
        //         data: {
        //             student_id: studentId
        //         },
        //         dataType: 'JSON',
        //         success: function(response) {
        //             if (response.status == "success") {
        //                 $('#denyModal').modal('hide');
        //                 successMsg(response.message);
        //                 location.reload();
        //             } else {
        //                 alert('Error occurred while denying permission.');
        //             }
        //         },
        //         error: function() {
        //             alert('Something went wrong. Please try again.');
        //         }
        //     });
        // });
        });

        function loadDaycareStatus() {
            $('#refresh-status').prop('disabled', true);
            $.ajax({
                url: '<?php echo site_url("user/user/refresh_daycare_status"); ?>',
                type: 'GET',
                success: function(response) {
                    $('#daycare-status-content').html(response);
                    $('#refresh-status').prop('disabled', false);
                },
                error: function() {
                    $('#daycare-status-content').html('<div class="text-danger">Unable to load status.</div>');
                    $('#refresh-status').prop('disabled', false);
                }
            });
        }


        let currentDate = new Date();

        function formatDateDisplay(date) {
            return date.toLocaleDateString('en-GB', {
                weekday: 'long',
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        function formatDateParam(date) {
            return date.toISOString().split('T')[0];
        }

        function isToday(date) {
        const today = new Date();
        return (
            date.getFullYear() === today.getFullYear() &&
            date.getMonth() === today.getMonth() &&
            date.getDate() === today.getDate()
            );
        }


        function loadActivities(date) {
            let displayDate = formatDateDisplay(date);
            let paramDate = formatDateParam(date);

            $('#activity-date-display').text(displayDate);

            $('#refresh-day, #prev-day, #next-day').prop('disabled', true);
         
            $('#refresh-day i').addClass('fa-spin');

            $.ajax({
                url: "<?= site_url('user/user/get_daycare_activities_by_date') ?>",
                type: "POST",
                data: { date: paramDate },
                success: function (response) {
                    $('#daycare-activities-container').html(response);
                },
                error: function () {
                    $('#daycare-activities-container').html('<div class="no-activity">Could not load activities.</div>');
                },
                complete: function () {

                    $('#refresh-day, #prev-day').prop('disabled', false);
                  
                    if (!isToday(date)) {
                        $('#next-day').prop('disabled', false);
                    }

                    $('#refresh-day i').removeClass('fa-spin');

                }
            });
        }
     
        $('#activity-date-picker').val(new Date().toISOString().split('T')[0]);
        loadActivities(currentDate);

        $('#activity-date-picker').on('change', function () {
            const selected = new Date(this.value);
            currentDate = selected;
            loadActivities(currentDate);
        });

        $('#refresh-day').click(function () {
            loadActivities(currentDate);
        });

        function printActivities() {
            var printContents = document.getElementById('daycare-activities-container').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = `
                <html>
                <head>
                    <title>Daycare Activities</title>
                    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
                    <style>
                        body { padding: 20px; }
                    </style>
                </head>
                <body>
                    ${printContents}
                </body>
                </html>
            `;

            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        function downloadPdf() {
            var element = document.getElementById('daycare-activities-container');
            var opt = {
                margin:       0.5,
                filename:     'daycare_activities_<?php echo date('Ymd'); ?>.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2 },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            html2pdf().set(opt).from(element).save();
        }
                
</script>

