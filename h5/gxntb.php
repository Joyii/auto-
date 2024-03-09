<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>共享内部</title>
    <link rel="stylesheet" href="./WPS Document List_files/bootstrap.min.css">
    <script src="./WPS Document List_files/jquery.min.js"></script>
    <script src="./WPS Document List_files/popper.min.js"></script>
    <script src="./WPS Document List_files/bootstrap.min.js"></script>
</head>

<body>
<?php 
    include('header.php');  
?>
    <div>
        <h1 class="my-4">共享内部</h1>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link <?php echo (isset($_GET['tag']) && $_GET['tag'] == 'add') ? 'active' : ''; ?>" href="?tag=add">Add</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (isset($_GET['tag']) && $_GET['tag'] == 'list') ? 'active' : ''; ?>" href="?tag=list">List</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (isset($_GET['tag']) && $_GET['tag'] == 'search') ? 'active' : ''; ?>" href="?tag=search">Search</a>
            </li>
        </ul>

        <div class="tab-content">
            <?php if (isset($_GET['tag']) && $_GET['tag'] == 'add') : ?>
                <div id="add" class="tab-pane fade show active">
                    <h3 class="my-4">Add New Item</h3>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?tag=add"); ?>">
                        <div class="form-group">
                            <label for="content">Content:</label>
                            <textarea class="form-control" rows="3" id="content" name="content" style="width: 450px; height: 80px;"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes:</label>
                            <input type="text" class="form-control" id="notes" name="notes">
                        </div>
                        <div class="form-group">
                            <label for="person">Person:</label>
                            <input type="text" class="form-control" id="person" name="person">
                        </div>
                        <button type="submit" class="btn btn-primary" name="add">Add</button>
                    </form>
                </div>
            <?php elseif (isset($_GET['tag']) && $_GET['tag'] == 'list') : ?>
                <div id="list" class="tab-pane fade show active">
                    <h3 class="my-4">Item List</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Time</th>
                                <th>Content</th>
                                <th>Notes</th>
                                <th>Person</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // 读取CSV文件并显示列表
                            if (($handle = fopen("gxntb.csv", "r")) !== FALSE) {
                                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                    echo "<tr>";
                                    echo "<td>" . $data[0] . "</td>";
                                    echo "<td>" . $data[1] . "</td>";
                                    echo "<td>" . $data[2] . "</td>";
                                    echo "<td>" . $data[3] . "</td>";
                                    echo "<td>" . $data[4] . "</td>";
                                    echo "<td>
                                            <a href='?tag=edit&id=" . $data[0] . "' class='btn btn-sm btn-primary'>Edit</a>
                                            <a href='?tag=list&del=" . $data[0] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this item?\")'>Delete</a>
                                          </td>";
                                    echo "</tr>";
                                }
                                fclose($handle);
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif (isset($_GET['tag']) && $_GET['tag'] == 'search') : ?>
                <div id="search" class="tab-pane fade show active">
                    <h3 class="my-4">Search Item</h3>
                    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <input type="hidden" name="tag" value="search">
                        <div class="form-group">
                            <label for="keyword">Keyword:</label>
                            <input type="text" class="form-control" id="keyword" name="keyword">
                        </div>
                        <button type="submit" class="btn btn-primary" name="search">Search</button>
                    </form>
                    <?php
                    // 处理搜索功能
                    if (isset($_GET['search'])) {
                        $keyword = $_GET['keyword'];
                        echo "<h4 class='my-4'>Search Results for: " . $keyword . "</h4>";
                        echo "<table class='table table-striped'>";
                        echo "<thead><tr><th>ID</th><th>Time</th><th>Content</th><th>Notes</th><th>Person</th></tr></thead>";
                        echo "<tbody>";
                        if (($handle = fopen("gxntb.csv", "r")) !== FALSE) {
                            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                if (stripos($data[2], $keyword) !== false || stripos($data[3], $keyword) !== false) {
                                    echo "<tr>";
                                    echo "<td>" . $data[0] . "</td>";
                                    echo "<td>" . $data[1] . "</td>";
                                    echo "<td>" . $data[2] . "</td>";
                                    echo "<td>" . $data[3] . "</td>";
                                    echo "<td>" . $data[4] . "</td>";
                                    echo "</tr>";
                                }
                            }
                            fclose($handle);
                        }
                        echo "</tbody></table>";
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <?php
        // 处理添加、编辑和删除功能
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
            $content = $_POST['content'];
            $notes = $_POST['notes'];
            $person = $_POST['person'];
            $time = date("Y-m-d H:i:s");

            $data = array(getNextId(), $time, $content, $notes, $person);
            $fp = fopen('gxntb.csv', 'a');
            fputcsv($fp, $data);
            fclose($fp);
            header("Location: ?tag=list");
            exit();
        }

        if (isset($_GET['tag']) && $_GET['tag'] == 'edit' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $data = getDataById($id);
            if ($data) {
                echo "<div class='modal fade' id='editModal' tabindex='-1' role='dialog' aria-labelledby='editModalLabel' aria-hidden='true'>
                        <div class='modal-dialog' role='document'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <h5 class='modal-title' id='editModalLabel'>Edit Item</h5>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                        <span aria-hidden='true'>&times;</span>
                                    </button>
                                </div>
                                <div class='modal-body'>
                                    <form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"] . "?tag=list") . "'>
                                        <input type='hidden' name='id' value='" . $data[0] . "'>
                                        <div class='form-group'>
                                            <label for='content'>Content:</label>
                                            <textarea class='form-control' id='content' name='content'>" . $data[2] . "</textarea>
                                        </div>
                                        <div class='form-group'>
                                            <label for='notes'>Notes:</label>
                                            <input type='text' class='form-control' id='notes' name='notes' value='" . $data[3] . "'>
                                        </div>
                                        <div class='form-group'>
                                            <label for='person'>Person:</label>
                                            <input type='text' class='form-control' id='person' name='person' value='" . $data[4] . "'>
                                        </div>
                                        <button type='submit' class='btn btn-primary' name='update'>Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                      </div>";
                echo "<script>$('#editModal').modal('show');</script>";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
            $id = $_POST['id'];
            $content = $_POST['content'];
            $notes = $_POST['notes'];
            $person = $_POST['person'];

            updateData($id, $content, $notes, $person);
            header("Location: ?tag=list");
            exit();
        }

        if (isset($_GET['tag']) && $_GET['tag'] == 'list' && isset($_GET['del'])) {
            $id = $_GET['del'];
            deleteData($id);
            header("Location: ?tag=list");
            exit();
        }

        // 辅助函数
        function getNextId() {
            $maxId = 0;
            if (($handle = fopen("gxntb.csv", "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $maxId = max($maxId, $data[0]);
                }
                fclose($handle);
            }
            return $maxId + 1;
        }

        function getDataById($id) {
            if (($handle = fopen("gxntb.csv", "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($data[0] == $id) {
                        fclose($handle);
                        return $data;
                    }
                }
                fclose($handle);
            }
            return null;
        }

        function updateData($id, $content, $notes, $person) {
            $rows = array();
            if (($handle = fopen("gxntb.csv", "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($data[0] == $id) {
                        $data[2] = $content;
                        $data[3] = $notes;
                        $data[4] = $person;
                    }
                    $rows[] = $data;
                }
                fclose($handle);
            }
            $fp = fopen('gxntb.csv', 'w');
            foreach ($rows as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);
        }

        function deleteData($id) {
            $rows = array();
            if (($handle = fopen("gxntb.csv", "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if ($data[0] != $id) {
                        $rows[] = $data;
                    }
                }
                fclose($handle);
            }
            $fp = fopen('gxntb.csv', 'w');
            foreach ($rows as $row) {
                fputcsv($fp, $row);
            }
            fclose($fp);
        }
        ?>
    </div>
</body>
</html>