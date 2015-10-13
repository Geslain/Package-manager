<?php
error_reporting(-1);
ini_set('display_errors', 'On');
$package = "";

if(isset($_GET["package"]))
$package = $_GET["package"];


function dirsize($dir) {
    $size = 0;
    $unit = "o";
    foreach(scandir($dir) as $file) {
        if($file != '.' && $file != '..') {
            $size += filesize($dir."/".$file);
        }
    }

    if(($size/ 1024) >= 1 && $size / 1024*1024 < 1) {
        $unit = "K" . $unit;
        $size= number_format($size/1024, 2, ',', ' ');
    } else if(($size / (1024*1024)) > 1) {
        $unit = "M".$unit;
        $size = ($size/(1024*1024));
        $size = number_format($size, 2, ',', ' ');
    }

    return $size." ".$unit;

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Package Manager</title>

    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/css/jquery.jscrollpane.css" rel="stylesheet">
    <link href="main.css" rel="stylesheet">
    <script src="vendor/js/jquery-1.11.3.min.js"></script>
    <script src="vendor/js/jquery.mousewheel.js"></script>
    <script src="vendor/js/mwheelIntent.js"></script>
    <script src="vendor/js/jquery.jscrollpane.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <script>
        var id_package = "<?=$package?>";
    </script>

</head>
<body>
<div class="container-fluid full-height">
    <div class="row full-height">
        <div class="col-md-12 full-height main-frame-container">
            <div class="panel panel-primary main-frame">
                <div class="panel-heading">Package Manager</div>
                <div class="panel-body">
                    <div class="file-detail full-height col-md-4"></div>
                    <div class="file-manager scroll-pane full-height col-md-12">
                        <?php
                        $files_array = array();

                        $dir = new DirectoryIterator(dirname(__FILE__) . "/packages");
                        while($dir->valid()) {
                            if (!$dir->isDot() && $dir->isDir()) {
                                // sort key, ie. modified timestamp
                                $key = $dir->getCTime();
                                $data = array($dir->getFilename(),dirsize($dir->getPathname())) ;
                                $files_array[$key] = $data;

                            }
                            $dir->next();
                        }

                        ksort($files_array);
                        $files_array= array_reverse($files_array, true);

                        foreach ($files_array as $key => $fileinfo) {
                                ?>
                                <div class="dir-item" data-package="<?= $fileinfo[0]  ?>"><a href="?package=<?=$fileinfo[0]?>" onClick="return false;">
                                    <div class="file-name col-md-4"><i class="glyphicon glyphicon-folder-open"
                                                                       style="margin-right: 20px"> </i><?= $fileinfo[0]  ?>
                                    </div>
                                    <div
                                        class="file-date col-md-4"><?= date("d/m/Y H:i:s", $key) ?></div>
                                    <div class="file-size col-md-4"><?= $fileinfo[1] ?></div></a>
                                </div>
                                <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Package manquant</h4>
            </div>
            <div class="modal-body">
                <p><i class="glyphicon glyphicon-alert" style="color: red ; font-size: 24px; padding-right: 15px"></i>Le package que vous recherchez n'existe pas</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="main.js"></script>
</body>
</html>