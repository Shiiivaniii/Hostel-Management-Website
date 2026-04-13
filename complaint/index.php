<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
        

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Hostel Complaint Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Submit and track hostel complaints easily. Electricity, plumbing, carpentry and more." />
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <!-- ================= HEADER ================= -->
<header class="complaint-header">
  <div class="header-left">
    <a href="/SL_project/hostel.php" class="btn-back">⬅ Back</a>
  </div>

  <div class="header-center">
 <div class="marquee">
   <div class="marquee-content">
   Submit maintenance issues and track resolution time in real-time.
   Each complaint receives a unique tracking ID.
   </div>
</div>
  </div>

  <div class="header-right">
    <div class="header-stat-pill">⏱ Avg. resolution: 24–48 hrs</div>
  </div>
</header>

  <!-- ================= MAIN ================= -->
  <main>
    <div class="layout-grid">

      <section class="card">
        <div class="card-inner">

          <div class="card-header">
            <div>
              <div class="card-title">
                Submit a Complaint
                <span class="chip">
                  <span class="chip-dot"></span>
                  Auto-assigned to coordinator
                </span>
              </div>
              <p class="card-subtitle">
                Fill all required fields marked with <span class="required-badge">*</span>.
              </p>
            </div>

            <a href="status.php" class="btn-action-outline">
              🔍 Track Existing Complaint
            </a>
          </div>

          <!-- ================= FORM ================= -->
          <form action="add_complaint.php" method="POST" autocomplete="off">

            <div class="form-row">
              <div class="form-group">
                <label for="studentName">
                  Student Name <span class="required-badge">*</span>
                </label>
                <input id="studentName" type="text" name="studentName" placeholder="e.g. Riya Sharma" required autofocus />
              </div>

              <div class="form-group">
                <label for="roomNumber">
                  Room Number <span class="required-badge">*</span>
                </label>
                <input id="roomNumber" name="roomNumber" type="text" placeholder="e.g. B-204" required />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="hostel">
                  Hostel<span class="required-badge">*</span>
                </label>
                <input id="hostel" name="hostel" type="text" placeholder="e.g. Girls Hostel 2" required />
              </div>
              <div class="form-group">
                <label for="block">
                  Block <span class="required-badge"></span>
                </label>
                <input type="text" id="block" name="block" placeholder="e.g. Block B" required/>
              </div>

              <div class="form-group">
                <label for="email">Email (optional)</label>
                <input id="email" name="email" type="email" placeholder="name@college.edu" />
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="category">
                  Complaint Category <span class="required-badge">*</span>
                </label>
                <select id="category" name="category" required>
                  <option value="">Select category</option>
                  <option value="electricity">⚡ Electricity</option>
                  <option value="washroom">🚿 Washroom / Plumbing</option>
                  <option value="carpenter">🪑 Carpenter / Furniture</option>
                  <option value="other">📌 Other</option>
                </select>
              </div>

              <div class="form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority">
                  <option value="normal">Normal</option>
                  <option value="high">High (Urgent)</option>
                </select>
                <p class="helper-text">
                  Use high priority only for safety or health-related issues.
                </p>
              </div>
            </div>

            <div class="form-group">
              <label for="details">
                Describe the Issue <span class="required-badge">*</span>
              </label>
              <textarea 
                id="details" 
                rows="4"
                placeholder="Explain the issue clearly. Mention location and any important details." 
                name="details"
                required>
              </textarea>
            </div>
<br>
            <button type="submit" class="btn-action">
              ➤ Submit Complaint
            </button>
          </form>

          <p class="helper-text" id="lastComplaintInfo"></p>

        </div>
      </section>

    </div>
  </main>

  <!-- ================= BIG SUCCESS MODAL ================= -->
  <div class="id-modal-backdrop" id="idModalBackdrop"></div>

  <div class="id-modal" id="idModal">
    <div class="id-modal-content">

      <h2 class="success-title">🎉 Complaint Submitted Successfully!</h2>

      <p class="success-subtext">
        Please save your complaint ID to track the status later.
      </p>

      <div class="big-id" id="idModalText">
        #1
      </div>

      <div class="modal-actions">
        <button id="copyIdBtn" class="btn-secondary">Copy ID</button>
        <button id="idModalOkBtn" class="btn-primary">Okay</button>
      </div>

    </div>
  </div>

  <!-- ================= TOAST ================= -->
  <div class="toast" id="toast"></div>

</body>
</html>