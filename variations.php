<?php
$page_title = 'All Product';
require_once('includes/load.php');
// Check user permission to view this page
page_require_level(2);
if (isset($_GET['delete_id'])) {
    $response = [
        'status' => false,
        'message' => 'Invalid operation',
    ];

    global $db;

    try {
        $id = (int) $_GET['delete_id'];
        $sql = "DELETE FROM variation WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);

        $response['status'] = true;
        $response['message'] = 'Variation deleted successfully.';
    } catch (Exception $e) {
        $response['message'] = 'An error occurred: ' . $e->getMessage();
    }

}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Initialize response
    $response = [
        'status' => false,
        'message' => 'Invalid operation',
    ];

    // Connect to the database
    global $db;

    try {
        if (isset($_POST['variation'], $_POST['sub'])) {
            $variation = $_POST['variation'];
            $sub = json_encode(array_map('trim', explode(',', $_POST['sub']))); // Convert options to JSON format

            if (isset($_POST['id']) && !empty($_POST['id'])) {
                // Edit operation
                $id = (int) $_POST['id'];
                $sql = "UPDATE variation SET variation = ?, sub = ? WHERE id = ?";
                $stmt = $db->prepare($sql);
                $stmt->execute([$variation, $sub, $id]);

                $response['status'] = true;
                $response['message'] = 'Variation updated successfully.';
            } else {
                // Add operation
                $sql = "INSERT INTO variation (variation, sub) VALUES (?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->execute([$variation, $sub]);

                $response['status'] = true;
                $response['message'] = 'Variation added successfully.';
            }
        } else {
            $response['message'] = 'Required fields are missing.';
        }
    } catch (Exception $e) {
        $response['message'] = 'An error occurred: ' . $e->getMessage();
    }

    echo "<script>alert('" . htmlspecialchars($response['message'], ENT_QUOTES) . "');</script>";
}
$variation = fetch_variations();
?>

<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <!-- <div class="pull-right">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addVariationModal">Add
                        Variation</button>
                </div> -->
            </div>
            <div class="panel-body">
                <table class="table table-bordered" id="table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Variation Name</th>
                            <th>Options</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($variation as $index => $var): ?>
                            <tr>
                                <td class="text-center"><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($var['variation']); ?></td>
                                <td>
                                    <?php
                                    $options = json_decode($var['sub'], true); // Decode JSON to array
                                    if (is_array($options)) { // Ensure it's an array after decoding
                                        echo htmlspecialchars(implode(", ", $options));
                                    } else {
                                        echo htmlspecialchars($var['sub']); // Fallback in case decoding fails
                                    }
                                    ?>
                                </td>


                                <td class="text-center">
                                    <button class="btn btn-info btn-xs edit-variation-btn"
                                        data-id="<?php echo $var['id']; ?>"
                                        data-variation="<?php echo htmlspecialchars($var['variation']); ?>"
                                        data-sub="<?php echo htmlspecialchars($var['sub']); ?>" title="Edit"
                                        data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </button>
                                    <a href="variations.php?delete_id=<?php echo $var['id']; ?>"
                                        class="btn btn-xs btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this variation?');"
                                        title="Remove" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Variation Modal -->
<div class="modal fade" id="addVariationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="addVariationForm" method="post" action="variations.php">
                <div class="modal-header">
                    <h5 class="modal-title">Add Variation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="variationName">Variation Name</label>
                        <input type="text" class="form-control" id="variationName" name="variation" required>
                    </div>
                    <div class="form-group">
                        <label for="variationOptions">Options (comma-separated)</label>
                        <input type="text" class="form-control" id="variationOptions" name="sub" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Variation Modal -->
<div class="modal fade" id="editVariationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="editVariationForm" method="post" action="variations.php">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Variation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editVariationId" name="id">
                    <div class="form-group">
                        <label for="editVariationName">Variation Name</label>
                        <input type="text" class="form-control" readonly id="editVariationName" name="variation"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="editVariationOptions">Options (comma-separated)</label>
                        <input type="text" class="form-control" id="editVariationOptions" name="sub" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#table").DataTable();



    });

    $(document).on("click", ".edit-variation-btn", function () {
        const id = $(this).data("id");
        const variation = $(this).data("variation");
        const sub = $(this).data("sub");


        $("#editVariationId").val(id);
        $("#editVariationName").val(variation);
        $("#editVariationOptions").val(sub);
        $("#editVariationModal").modal("show");
    });

</script>
<?php include_once('layouts/footer.php'); ?>