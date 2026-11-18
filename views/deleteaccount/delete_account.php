<?php $this->load->view('layout/privacy-policy/header'); ?>

<div class="container my-5">

<h1>Delete Your Account – CAG ERP Mobile App</h1>

    <p>This page is provided by <strong>Educron Pvt Ltd</strong>, the developer of the <strong>CAG ERP Mobile App</strong>.</p>

    <h2>Requesting Account Deletion</h2>
    <p>To request deletion of your account and associated data, please follow the steps below:</p>
    <ol>
        <li>Email your request to <a href="mailto:prshah83@gmail.com">prshah83@gmail.com</a></li>
        <li>Include your registered mobile number or email ID linked to your account</li>
        <li>Subject line: <strong>Account Deletion Request – CAG ERP</strong></li>
    </ol>

    <h2>What Happens When You Request Deletion?</h2>
    <p>Upon verification, we will delete the following data:</p>
    <ul>
        <li>User account details (email, username)</li>
        <li>Login credentials</li>
        <li>App usage data</li>
        <li>Fee transaction history (if not required for legal/tax reasons)</li>
    </ul>

    <h3>Data We May Retain</h3>
    <ul>
        <li>Payment records required for taxation or school audits (retained for 7 years)</li>
        <li>Anonymized usage logs (used to improve app experience, non-personalized)</li>
    </ul>

    <h2>Timeline</h2>
    <p>Account deletion requests are typically processed within <strong>7 business days</strong> after identity verification.</p>

    <p>If you need assistance, you may also contact CAG School directly through <a href="https://www.cagschool.org">www.cagschool.org</a>.</p>

    <a class="button" onclick="document.getElementById('deletionModal').style.display='block'">Request Deletion Now</a>

</div>


<!-- Modal -->
<div id="deletionModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h3>Request Account Deletion</h3>
        <p>Please enter your registered email or mobile number:</p>
        <form onsubmit="submitDeletion(event)">
            <input type="text" id="contactInput" placeholder="Email or Mobile Number" required>
            <button type="submit">Send Request</button>
        </form>
        <p id="responseMessage" style="margin-top: 10px;"></p>
    </div>
</div>

<script>


    function closeModal() {
            document.getElementById('responseMessage').textContent = '';
            document.getElementById('deletionModal').style.display = 'none';
    }

    function submitDeletion(e) {
        e.preventDefault();
        
        const contact = document.getElementById('contactInput').value;
        const response = document.getElementById('responseMessage');
        // You can use AJAX here to send to your backend. For now:
        response.textContent = "Your request has been submitted for review.";
        response.style.color = "green";
        document.getElementById('contactInput').value = '';

        
    }
</script>
<?php $this->load->view('layout/privacy-policy/footer'); ?>

