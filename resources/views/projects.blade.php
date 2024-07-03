<!DOCTYPE html>
<html lang="en">

<head>
    <title>BT</title>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ url('/') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</head>

<body>


    <div class="container">
        <h2 class="text-center mt-5 mb-3">Lembaga Kemanusiaan</h2>
        <div class="card">
            <div class="card-header">
                <button class="btn btn-outline-primary" onclick="createProject()">
                    Add Data
                </button>
            </div>
            <div class="card-body">
                <div id="alert-div">

                </div>

                <div class="mb-3">
                    <label for="filter_sumber_dana" class="form-label">Sumber Dana:</label>
                    <input type="text" id="filter_sumber_dana" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="filter_keterangan" class="form-label">Keterangan:</label>
                    <input type="text" id="filter_keterangan" class="form-control">
                </div>
                <button class="btn btn-primary mb-3" id="filterButton">Filter</button>
                <button class="btn btn-secondary mb-3" id="resetButton">Reset</button>

                <table class="table table-bordered" id="projects_table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Sumber_dana</th>
                            <th>Program</th>
                            <th>Keterangan</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="projects-table-body">

                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="form-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Project Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="error-div"></div>
                    <form>
                        <input type="hidden" name="update_id" id="update_id">
                        <div class="form-group">
                            <label for="sumber_dana">Sumber_dana</label>
                            <input type="text" class="form-control" id="sumber_dana" name="sumber_dana">
                        </div>
                        <div class="form-group">
                            <label for="program">Program</label>
                            <input type="text" class="form-control" id="program" name="program"></input>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan"></input>
                        </div>
                        <button type="submit" class="btn btn-outline-primary mt-3" id="save-project-btn">Save
                            Project</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal " tabindex="-1" role="dialog" id="view-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Project Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <b>Id:</b>
                    <p id="id-info"></p>
                    <b>Sumber_dana:</b>
                    <p id="sumber_dana-info"></p>
                    <b>Program:</b>
                    <p id="program-info"></p>
                    <b>Keterangan:</b>
                    <p id="keterangan-info"></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript">
        var table;
        $(function() {
            var baseUrl = $('meta[name=app-url]').attr("content");
            let url = baseUrl + '/projects';

            table = $('#projects_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    data: function(d) {
                        d.sumber_dana = $('#filter_sumber_dana').val();
                        d.keterangan = $('#filter_keterangan').val();
                    }
                },
                "order": [
                    [0, "desc"]
                ],
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'sumber_dana'
                    },
                    {
                        data: 'program'
                    },
                    {
                        data: 'keterangan'
                    },
                    {
                        data: 'action'
                    },
                ],

            });

            $('#filterButton').click(function() {
                table.ajax.reload();
            });

            $('#resetButton').click(function() {
                $('#filter_sumber_dana').val('');
                $('#filter_keterangan').val('');
                table.ajax.reload();
            });
        });

        function reloadTable() {
            $('#projects_table').DataTable().ajax.reload();
        }

        $("#save-project-btn").click(function(event) {
            event.preventDefault();
            if ($("#update_id").val() == null || $("#update_id").val() == "") {
                storeProject();
            } else {
                updateProject();
            }
        })

        function createProject() {
            $("#alert-div").html("");
            $("#error-div").html("");
            $("#update_id").val("");
            $("#sumber_dana").val("");
            $("#program").val("");
            $("#keterangan").val("");
            $("#form-modal").modal('show');
        }

        function storeProject() {
            $("#save-project-btn").prop('disabled', true);
            let url = $('meta[name=app-url]').attr("content") + "/projects";
            let data = {
                sumber_dana: $("#sumber_dana").val(),
                program: $("#program").val(),
                keterangan: $("#keterangan").val(),
            };
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "POST",
                data: data,
                success: function(response) {
                    $("#save-project-btn").prop('disabled', false);
                    let successHtml =
                        '<div class="alert alert-success" role="alert"><b>Project Created Successfully</b></div>';
                    $("#alert-div").html(successHtml);
                    $("#sumber_dana").val("");
                    $("#program").val("");
                    $("#keterangan").val("");
                    reloadTable();
                    $("#form-modal").modal('hide');
                },
                error: function(response) {
                    $("#save-project-btn").prop('disabled', false);
                    if (typeof response.responseJSON.errors !== 'undefined') {
                        let errors = response.responseJSON.errors;
                        let descriptionValidation = "";
                        if (typeof errors.description !== 'undefined') {
                            descriptionValidation = '<li>' + errors.description[0] + '</li>';
                        }
                        let nameValidation = "";
                        if (typeof errors.name !== 'undefined') {
                            nameValidation = '<li>' + errors.name[0] + '</li>';
                        }

                        let errorHtml = '<div class="alert alert-danger" role="alert">' +
                            '<b>Validation Error!</b>' +
                            '<ul>' + nameValidation + descriptionValidation + '</ul>' +
                            '</div>';
                        $("#error-div").html(errorHtml);
                    }
                }
            });
        }

        function editProject(id) {
            let url = $('meta[name=app-url]').attr("content") + "/projects/" + id;
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    let project = response.project;
                    $("#alert-div").html("");
                    $("#error-div").html("");
                    $("#update_id").val(project.id);
                    $("#sumber_dana").val(project.sumber_dana);
                    $("#program").val(project.program);
                    $("#keterangan").val(project.keterangan)
                    $("#form-modal").modal('show');
                },
                error: function(response) {
                    console.log(response.responseJSON)
                }
            });
        }

        function updateProject() {
            $("#save-project-btn").prop('disabled', true);
            let url = $('meta[name=app-url]').attr("content") + "/projects/" + $("#update_id").val();
            let data = {
                id: $("#update_id").val(),
                sumber_dana: $("#sumber_dana").val(),
                program: $("#program").val(),
                keterangan: $("#keterangan").val(),
            };
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "PUT",
                data: data,
                success: function(response) {
                    $("#save-project-btn").prop('disabled', false);
                    let successHtml =
                        '<div class="alert alert-success" role="alert"><b>Project Updated Successfully</b></div>';
                    $("#alert-div").html(successHtml);
                    $("#sumber_dana").val("");
                    $("#program").val("");
                    $("#keterangan").val("");
                    reloadTable();
                    $("#form-modal").modal('hide');
                },
                error: function(response) {
                    $("#save-project-btn").prop('disabled', false);
                    if (typeof response.responseJSON.errors !== 'undefined') {
                        let errors = response.responseJSON.errors;
                        let descriptionValidation = "";
                        if (typeof errors.description !== 'undefined') {
                            descriptionValidation = '<li>' + errors.description[0] + '</li>';
                        }
                        let nameValidation = "";
                        if (typeof errors.name !== 'undefined') {
                            nameValidation = '<li>' + errors.name[0] + '</li>';
                        }

                        let errorHtml = '<div class="alert alert-danger" role="alert">' +
                            '<b>Validation Error!</b>' +
                            '<ul>' + nameValidation + descriptionValidation + '</ul>' +
                            '</div>';
                        $("#error-div").html(errorHtml);
                    }
                }
            });
        }

        function showProject(id) {
            $("#id-info").html("");
            $("#sumber_dana-info").html("");
            $("#program-info").html("");
            $("#keterangan-info").html("");
            let url = $('meta[name=app-url]').attr("content") + "/projects/" + id + "";
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    let project = response.project;
                    $("#id-info").html(project.id);
                    $("#sumber_dana-info").html(project.sumber_dana);
                    $("#program-info").html(project.program);
                    $("#keterangan-info").html(project.keterangan);
                    $("#view-modal").modal('show');

                },
                error: function(response) {
                    console.log(response.responseJSON)
                }
            });
        }

        function destroyProject(id) {
            let url = $('meta[name=app-url]').attr("content") + "/projects/" + id;
            let data = {
                sumber_dana: $("#sumber_dana").val(),
                program: $("#program").val(),
                keterangan: $("#keterangan").val(),
            };
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "DELETE",
                data: data,
                success: function(response) {
                    let successHtml =
                        '<div class="alert alert-success" role="alert"><b>Project Deleted Successfully</b></div>';
                    $("#alert-div").html(successHtml);
                    reloadTable();
                },
                error: function(response) {
                    console.log(response.responseJSON)
                }
            });
        }
    </script>
</body>

</html>
