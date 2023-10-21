<?= $this->extend('template/admin_template'); ?>

<?= $this->section('content'); ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Ticket Management</h1>
            </div>
        </div>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <?php if (auth()->user()->inGroup('user')) : ?>
        <div class="row mb-2">
            <div class="col-sm-12">
                <button type="button" id="addrow" data-toggle="modal" data-target="#modelId" class="btn btn-primary" onclick="clearform()">
                    Add Ticket
                </button>
            </div>
        </div>
        <?php endif; ?>
        <table id="dataTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">OFFICE/SECTION/DIVISION</th>
                    <th scope="col">FULL NAME</th>
                    <th scope="col">TITLE</th>
                    <th scope="col">DESCRIPTION</th>
                    <th scope="col">SEVERITY</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">ACTIONS</th>
                </tr>
            </thead>
        </table>

        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">New Ticket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" novalidate>
                            <input type="hidden" name="id" id="id" />
                            <input type="hidden" name="user_id" id="user_id" value="<?= auth()->user()->id ?? "GUEST" ?>"/>
                            <?php if (auth()->user()->inGroup('user')) : ?>
                            <input type="hidden" name="status" id="status" value="Pending"/>
                            <?php endif; ?>
                            <?php if (auth()->user()->inGroup('admin')) : ?>
                            <div class="form-group">
                                <label for="status">Status * </label>
                                <select id="status" name="status" class="custom-select" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Resolved">Resolved</option>
                                </select>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Status is Required.</div>
                            </div>
                            <?php endif; ?>
                            <?php if (auth()->user()->inGroup('admin')) : ?>
                            <fieldset disabled>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Full Name" required />
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter a Full Name.</div>
                            </div>
                            <div class="form-group">
                                <label for="title">Title *</label>
                                <input type="text" class="form-control" name="title" id="title" placeholder="Title" required />
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter a Title.</div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description *</label>
                                <textarea type="textarea" class="form-control" name="description" id="description" rows="5" required></textarea>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Description is Required.</div>
                            </div>
                            <div class="form-group">
                                <label for="office_id">Office/Section/Division *</label>
                                <select id="office_id" name="office_id" class="custom-select" required>
                                    <option value="">Select Office/Section/Division</option>
                                    <?php
                                    foreach ($offices as $office) {
                                        echo "<option value='" . $office['id'] . "'>" . $office['office_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Office/Section/Division is Required.</div>
                            </div>
                            <div class="form-group">
                                <label for="severity">Severity *</label>
                                <select id="severity" name="severity" class="custom-select" required>
                                    <option value="">Select Severity</option>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>

                                </select>
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Severity is Required.</div>
                            </div>
                            <?php if (auth()->user()->inGroup('admin')) : ?>
                            </fieldset>
                            <?php endif; ?>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Close
                                </button>
                                <button type="submit" class="btn btn-primary" id="save">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="deleteprompt" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>
                            You are about to delete this record with ID
                            <span id="itemtodelete"></span>.
                        </p>
                        <input type="hidden" id="deleteid" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary" onclick="deleteProfile()">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>

<?= $this->section('pagescripts'); ?>
<script>
    $(function() {

        $("form").submit(function(event) {
            event.preventDefault();
            let formdata = $(this).serializeArray().reduce(function(obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            let jsondata = JSON.stringify(formdata);

            if (this.checkValidity()) {
                if (!formdata.id) {
                    //create
                    $.ajax({
                        method: "POST",
                        url: "<?= base_url() ?>tickets",
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            console.log(textStatus + ": " + jqXHR.status);
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Records Created Successfuly.",
                                autohide: true,
                                delay: 3000,
                            });
                            
                            table.ajax.reload();
                            $("#modelId").modal("hide");
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                            $(document).Toasts("create", {
                                class: "bg-danger",
                                title: "Error",
                                body: "Records Was Not Created.",
                                autohide: false,
                                delay: 3000,
                            });
                        },
                    });
                } else {
                    //update
                    $.ajax({
                        method: "PUT",
                        url: "<?= base_url() ?>tickets/" + formdata.id,
                        data: jsondata,
                        success: function(result, textStatus, jqXHR) {
                            console.log(textStatus + ": " + jqXHR.status);
                            $(document).Toasts("create", {
                                class: "bg-success",
                                title: "Success",
                                body: "Records Updated Successfuly.",
                                autohide: true,
                                delay: 3000,
                            });
                            clearform();
                            table.ajax.reload();
                            $("#modelId").modal("hide");

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                            $(document).Toasts("create", {
                                class: "bg-danger",
                                title: "Error",
                                body: "Records Was Not Updated.",
                                autohide: true,
                                delay: 3000,
                            });
                        },
                    });
                }
                $(this).addClass("was-validated");
            }


        });

        $(document).on("click", "#editRow", function() {
            let row = $(this).parents("tr")[0];
            let id = table.row(row).data().id;
            $.ajax({
                method: "GET",
                url: "<?= base_url() ?>tickets/" + id,
                success: function(result, textStatus, jqXHR) {
                    console.log(textStatus + ": " + jqXHR.status);
                    $("#modelId").modal("show");
                    $("#id").val(result.id);
                    $("#user_id").val(result.user_id);
                    $("#status").val(result.status);
                    $("#title").val(result.title);
                    $("#name").val(result.name);
                    $("#description").val(result.description);
                    $("#office_id").val(result.office_id);
                    $("#severity").val(result.severity);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
            });
        });

        $(document).on("click", "#deleteRow", function() {
            let row = $(this).parents("tr")[0];
            let id = table.row(row).data().id;
            $("#deleteid").val(id);
            $("#itemtodelete").text(id);
            $("#deleteprompt").modal("show");
        });
    });

    var table = $("#dataTable").DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= base_url() ?>tickets/list",
            type: "POST",
        },
        columns: [{
                data: "id"
            },
            {
                data: "office_name"
            },
            {
                data: "name"
            },
            {
                data: "title"
            },
            {
                data: "description"
            },
            {
                data: "severity"
            },
            {
                data: "status"
            },
            {
                "data": null,
                "render": function (data, type, row) {
                    if (row.status === "Pending") {
                        return '<td><button class="btn btn-primary" id="editRow">Edit</button> '+
                        ' <button class="btn btn-danger" data-toggle="modal" id="deleteRow">Delete</button>'+
                        '</td>';
                    } else if (row.status === "Processing") {
                        return '<span>Working on it</span>';
                    } else if (row.status === "Resolved") {
                        return '<span>Work done</span>';
                    } else {
                        return '';
                    }
                }
            }
            
        ],
        paging: true,
        lengthChange: true,
        searching: false,
        ordering: true,
        info: true,
        autoWidth: false,
        lengthMenu: [5, 10, 25, 50],
        
    });

    function deleteProfile() {
        $("#deleteprompt").modal("hide");
        let id = $("#deleteid").val();
        $.ajax({
            method: "DELETE",
            url: "<?= base_url() ?>tickets/" + id,
            success: function(result, textStatus, jqXHR) {
                console.log(textStatus + ": " + jqXHR.status);
                $(document).Toasts("create", {
                    class: "bg-success",
                    title: "Success",
                    body: "Records Deleted Successfuly.",
                    autohide: true,
                    delay: 3000,
                });
                table.ajax.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
            },
        });
    }

    function clearform() {
        $("#name").val("");
        $("#title").val("");
        $("#description").val("");
        $("#office_id").val("");
        $("#severity").val("");
    }

    $(document).ready(function() {
        'use strict';
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = $('.needs-validation');
        // Loop over them and prevent submission
        forms.each(function() {
            $(this).on('submit', function(event) {
                if (this.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                $(this).addClass('was-validated');
            });
        });
    });
</script>
<?= $this->endSection(); ?>