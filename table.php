<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>

    <div class="container mt-5 p-4 shadow rounded-3">
        <!-- Button trigger modal -->
        <button type="button" id="add" class="btn btn-outline-dark mb-4 float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">
            + Add Student
        </button>
        <table class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Profile</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require 'connection.php';
                $select = "SELECT * FROM tbl_student";
                $ex = $conn->query($select);
                while ($row = mysqli_fetch_assoc($ex)) {
                    echo '
                            <tr>
                                <td>' . $row['id'] . '</td>
                                <td>' . $row['name'] . '</td>
                                <td>' . $row['gender'] . '</td>
                                <td>
                                    <img src="' . $row['profile'] . '" width="40" height="40" class="rounded-circle" alt="">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-danger" id="delete">Delete</button>
                                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#exampleModal" id="edit">Edit</button>
                                </td>
                            </tr>
                        ';
                }
                ?>
            </tbody>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Student</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form" action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id" id="id">
                                <div class="mb-2">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter your Username" required>
                                </div>
                                <div class="mb-2">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select name="gender" id="gender" class="form-select" required>
                                        <option value="" selected disabled>---Gender---</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="file" class="form-label">Profile</label>
                                    <input type="file" name="file" id="file" class="form-control" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="save" data-bs-dismiss="modal">Add</button>
                            <button type="button" class="btn btn-success" id="update" data-bs-dismiss="modal">Update</button>
                        </div>
                    </div>
                </div>
            </div>

        </table>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {

        $('#add').click(function() {
            $('#update').hide();
            $('#save').show();
            $('#exampleModalLabel').text('Add Student');
        })

        $('#save').click(function() {
            const username = $('#username').val();
            const gender = $('#gender').val();
            const file = $('#file')[0].files[0];
            const imgURL = URL.createObjectURL(file);

            let formdata = new FormData();
            formdata.append('username', username);
            formdata.append('gender', gender);
            formdata.append('file', file);
            $.ajax({
                url: 'insert.php',
                method: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#form').trigger('reset');
                    $('tbody').append(`
                        <tr>
                            <td>${response}</td>
                            <td>${username}</td>
                            <td>${gender}</td>
                            <td>
                                <img src="${imgURL}" width="40" height="40" class="rounded-circle" alt="">
                            </td>
                            <td>
                                <button type="button" class="btn btn-outline-danger">Delete</button>
                                <button type="button" class="btn btn-outline-warning">Edit</button>
                            </td>
                        </tr>
                    `);
                }
            })
        })

        $(document).on('click', '#edit', function() {
            $('#update').show();
            $('#save').hide();
            $('#exampleModalLabel').text('Update Student');
            const row = $(this).closest('tr');
            const id = row.find('td:eq(0)').text().trim();
            const username = row.find('td:eq(1)').text().trim();
            const gender = row.find('td:eq(2)').text().trim();
            $('#id').val(id);
            $('#username').val(username);
            $('#gender').val(gender);
        });
        $('#update').click(function() {
            const id = $('#id').val();
            const username = $('#username').val();
            const gender = $('#gender').val();
            const file = $('#file')[0].files[0];

            let formdata = new FormData();
            formdata.append('id', id);
            formdata.append('username', username);
            formdata.append('gender', gender);

            let imgURL = null;

            if (file) {
                imgURL = URL.createObjectURL(file);
                formdata.append('file', file);
            }

            $.ajax({
                url: 'edit.php',
                method: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                success: function() {
                    $('tbody tr').each(function() {
                        if ($(this).find('td:eq(0)').text().trim() == id) {
                            $(this).find('td:eq(1)').text(username);
                            $(this).find('td:eq(2)').text(gender);
                            if (imgURL) {
                                $(this).find('td:eq(3) img').attr('src', imgURL);
                            }
                        }
                    });
                    $('#form').trigger('reset');
                }
            })
        });

        $(document).on('click','#delete',function(){
            if(!confirm("Are you sure?")) return ;
            const row = $(this).closest('tr');
            const id = row.find('td:first').text().trim();
            const formdata = new FormData();
            formdata.append('id', id);
            $.ajax({
                url:'delete.php',
                method: 'POST',
                data: {id},
                success:function() {
                    row.remove();
                }
            })
            
        })
    })
</script>