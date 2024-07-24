<!--
<?php
require_once 'function.php';
?>
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/adminDashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>

<?php include_once('includes/side_bar.php');?>

<div class="container mt-5">
  <div class="row">
    <div class="col-12">
      <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
          <a class="nav-link active" href="#" onclick="showForm('schoolForm')">Create School</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" onclick="showForm('classForm')">Create Class</a>
        </li>
      </ul>
    </div>
  </div>

  <!-- create school form -->
  <div id="schoolForm" class="mb-4">
    <h2>Create School</h2>
    <form onsubmit="createSchool(event)" enctype="multipart/form-data">
      <input type="text" name="schoolName" placeholder="School Name" required class="form-control mb-2">
      <input type="text" name="region" placeholder="Region" required class="form-control mb-2">
      <input type="text" name="town" placeholder="Town" required class="form-control mb-2">
      <input type="text" name="educator" placeholder="Educator" required class="form-control mb-2">
      <input type="file" name="logo" required class="form-control mb-2">
      <button type="submit" class="btn btn-success">Create School</button>
    </form>

</div>

<div id="classForm" class="mb-4">
            <h2>Create Class</h2>
            <form onsubmit="createClass(event)">
                <select name="schoolId" required class="form-control mb-2">
                    <option value="">Select School</option>
                    <?php foreach (getSchools() as $school): ?>
                        <option value="<?= $school['id'] ?>"><?= $school['school_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="className" placeholder="Class Name" required class="form-control mb-2">
                <button type="submit" class="btn btn-success">Create Class</button>
            </form>
        </div>


  </div>

  <!-- create class form -->
  <div id="classForm" class="mb-4" style="display: none;">
    <h2>Create Class</h2>
    <form onsubmit="createClass(event)">
      <select name="schoolId" required class="form-control mb-2">
        <option value="">Select School</option>
        <?php foreach (getSchools() as $school): ?>
                        <option value="<?= $school['id'] ?>"><?= $school['school_name'] ?></option>
                    <?php endforeach; ?>
      </select>
      <input type="text" name="className" placeholder="Class Name" required class="form-control mb-2">
      <button type="submit" class="btn btn-success">Create Class</button>
    </form>
  </div>
</div>



<div id="tableContainer"></div>
<nav>
  <ul class="pagination"></ul>
</nav>

<!-- EDIT MODAL -->
<div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editStudentForm" onsubmit="updateStudent(event)">
          <!-- Form fields will be dynamically populated -->
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
function showForm(formId) {
  const schoolForm = document.getElementById('schoolForm');
  const classForm = document.getElementById('classForm');

  if (formId === 'schoolForm') {
    schoolForm.style.display = 'block';
    classForm.style.display = 'none';
  } else if (formId === 'classForm') {
    schoolForm.style.display = 'none';
    classForm.style.display = 'block';
  }

  // Update the active tab
  const tabs = document.querySelectorAll('.nav-link');
  tabs.forEach(tab => {
    tab.classList.remove('active');
  });
  document.querySelector(`.nav-link[onclick="showForm('${formId}')"]`).classList.add('active');
}

  
        function updateClassSelect(schoolId) {
            $.get('ajax_handlers.php', { action: 'getClasses', schoolId: schoolId }, function(response) {
                $('select[name="classId"]').html(response);
            });
        }

        function updateFilterClassSelect(schoolId) {
            $.get('ajax_handlers.php', { action: 'getClasses', schoolId: schoolId }, function(response) {
                $('select[name="filterClassId"]').html(response);
            });
        }


  function editSchool(schoolId) {
        const newName = prompt("Enter new school name:");
        if (newName) {
            $.post('ajax_handlers.php', { action: 'updateSchool', schoolId: schoolId, name: newName }, function(response) {
                alert(response);
                showTable('schools');
            });
        }
    }

    function deleteSchool(schoolId) {
        if (confirm("Are you sure you want to delete this school?")) {
            $.post('ajax_handlers.php', { action: 'deleteSchool', schoolId: schoolId }, function(response) {
                alert(response);
                showTable('schools');
            });
        }
    }

    function editClass(classId) {
        const newName = prompt("Enter new class name:");
        if (newName) {
            $.post('ajax_handlers.php', { action: 'updateClass', classId: classId, name: newName }, function(response) {
                alert(response);
                showTable('classes');
            });
        }
    }

    function deleteClass(classId) {
        if (confirm("Are you sure you want to delete this class?")) {
            $.post('ajax_handlers.php', { action: 'deleteClass', classId: classId }, function(response) {
                alert(response);
                showTable('classes');
            });
        }
    }

        function filterStudents(event) {
            event.preventDefault();
            const schoolId = event.target.filterSchoolId.value;
            const classId = event.target.filterClassId.value;
            $.get('ajax_handlers.php', { action: 'getTable', type: 'students', schoolId: schoolId, classId: classId }, function(response) {
                $('#tableContainer').html(response);
            });
        }




function createSchool(event) {
    event.preventDefault();
    var formData = new FormData(event.target);
    formData.append('action', 'createSchool');
    $.ajax({
        url: 'ajax_handlers.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            alert(response);
            if (response.includes("successfully")) {
                // Reset the form
                $('#schoolForm form')[0].reset();
                // Clear the file input
                $('#schoolForm input[type="file"]').val('');
                showTable('schools');
            }
        }
    });
}



function createStudent(event) {

function createClass(event) {

    event.preventDefault();
    const schoolId = event.target.schoolId.value;
    const className = event.target.className.value;
    $.post('ajax_handlers.php', { action: 'createClass', schoolId: schoolId, name: className }, function(response) {
        alert(response);
        if (response.includes("successfully")) {
            // Reset the form
            $('#classForm form')[0].reset();
            showTable('classes');
        }
    });
}


function showTable(type, page = 1) {
    $.get('ajax_handlers.php', { action: 'getTable', type: type, page: page }, function(response) {
        $('#tableContainer').html(response);
    });
}
}
</script>
</body>
</html>

