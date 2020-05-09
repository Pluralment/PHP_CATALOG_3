<?php
echo "<div style=\"font-size:1.25em;padding: 5px\">";
$catalogName = '';
if(isset($_POST['catalog-name']))
{
    if(is_dir($_POST['catalog-name']))
    {
        $catalogName = $_POST['catalog-name'];
        showInfo($catalogName);
        echo 'Directory content:'.'<br/>';
        showDirContent($catalogName);
        $graphFleSize = intdiv(getDirSize($catalogName, "GRAPHIC"), 1024);
        $totalDirSize = intdiv(getDirSize($catalogName), 1024);
        echo "Graphic files size: ".$graphFleSize." Kbyte"."<br/>";
        echo "Graphic files percentage : ".getPercent($totalDirSize, $graphFleSize)."%"."<br/><br/>";
        showTxtFilesContent($catalogName, 100);
    }
    else
    {
        echo "<span style=\"font-size:1.25em;color:#F11A36;\">";
        echo 'Wrong directory, please try again';
        echo "</span>";
    }
}
echo "</div>";


function getPercent($initNum, $checkNum)
{
    if($initNum)
        return ($percent = (100*$checkNum)/$initNum);
    else
        return 0;
}

// Показывает подробную информацию по файлу.
function showInfo($fileName)
{
    if(is_dir($fileName))
    {
        echo '<img src="folder-icon.png"/>'.'Catalog name: '.$fileName.'<br/>';
        echo '<ul>';
        echo '<li>'.'Size: '.intdiv(getDirSize($fileName), 1024).' Kbyte'.'</li>';
    }
    if(is_file($fileName))
    {
        echo '<img src="files-icon.png"/>'.'Name: '.$fileName.'<br/>';
        echo '<ul>';
        echo '<li>'.'Size: '.intdiv(filesize($fileName), 1024).' Kbyte'.'</li>';
    }

    if(filectime($fileName) !== FALSE)
        echo '<li>'.'Time of creation: '.date("F d Y H:i:s.", filectime($fileName)).'</li>';
    else
        echo '<li>'.'Time of creation: -'.'</li>';

    if(filemtime($fileName) !== FALSE)
        echo '<li>'.'Time of modification: '.date("F d Y H:i:s.", filemtime($fileName)).'</li>';
    else
        echo '<li>'.'Time of modification: -'.'</li>';

    if(fileatime($fileName) !== FALSE)
        echo '<li>'.'Time of last access: '.date("F d Y H:i:s.", fileatime($fileName)).'</li>';
    else
        echo '<li>'.'Time of last access: -'.'</li>';

    echo '</ul>';
}

// Показывает содержимое каталога.
function showDirContent($dir)
{
    $totalSize=0;
    if ($dirStream = @opendir($dir))
    {
        echo '<ul>';
        while (($fileName = readdir($dirStream)) !== FALSE)
        {
            if ($fileName != "." && $fileName != "..")
            {
                echo '<li>';
                showInfo("$dir\\$fileName");
                echo '</li>';
            }
        }
        echo '</ul>';
    }
    closedir($dirStream);
    return $totalSize;
}

function showTxtFilesContent($dirPath, $maxLen)
{
    if ($dirStream = @opendir($dirPath))
    {
        while (($fileName = readdir($dirStream)) !== FALSE)
        {
            if ($fileName != "." && $fileName != "..")
            {
                if(getFileExtension($fileName) == "txt")
                    echo "$fileName, content -> ".file_get_contents("$dirPath\\$fileName",
                            FALSE, NULL, 0, $maxLen)."<br/>";
            }
        }
    }
    closedir($dirStream);
}

function getFileExtension($filename)
{
    $pathInfo = pathinfo($filename, PATHINFO_EXTENSION);
    if(isset($pathInfo))
        return strtolower($pathInfo);
    else
        return ".";
}

// Подсчитывание размера директории.
function getDirSize($dir, $type = '')
{
    $totalSize=0;
    if ($dirStream = @opendir($dir))
    {
        $graphExt = array();
        if($type == "GRAPHIC")
            $graphExt = array('bmp', 'png', 'gif', 'jpg', 'jpeg', 'tiff', 'tga', 'xps', 'webp', 'svg');
        while (($fileName = readdir($dirStream)) !== false)
        {
            if ($fileName!= "." && $fileName!= "..")
            {
                $filePath = "$dir\\$fileName";
                if($type == "GRAPHIC")
                {
                    $fileExt = getFileExtension($filePath);
                    if (is_file($filePath) and in_array($fileExt, $graphExt))
                        $totalSize += filesize($filePath);
                }
                else
                {
                    if (is_file($filePath))
                        $totalSize += filesize($filePath);
                }
                if (is_dir($filePath))
                    $totalSize += getDirSize($filePath, $type);
            }
        }
    }
    closedir($dirStream);
    return $totalSize;
}