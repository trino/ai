<?php
    $root = left(public_path(), strlen(public_path()) - 6);
    if(!isset($files)){$files = $_GET["files"];}
    if(!is_array($files)){$files = explode(",", $files);}
    $path = "resources/assets/scripts/";
    if(isset($_GET["path"])){
        $path = $_GET["path"];
        if(right($_GET["path"],1) != '/' && right($_GET["path"],1) != "\\"){$path .= '/';}
    }
    $workingfile = $root . $path . str_replace(".js", "", implode("-", $files)) . ".js";
    $workingtimestamp = 0;
    $forcenew = isset($_GET["forcenew"]) || isset($forcenew);
    $myfilename = myself($view_name);
    $mytimestamp = filemtime($myfilename);

    if(file_exists($workingfile) && !$forcenew){
        $workingtimestamp = filemtime($workingfile);
        if($mytimestamp >= $workingtimestamp){
            $workingtimestamp = 0;
        } else {
            foreach($files as $file){
                if(strpos($file, ".") === false){
                    $file .= ".js";
                }
                $file = $root . $path . $file;
                if(file_exists($file)){
                    $timestamp = filemtime($file);
                    if($timestamp > $workingtimestamp){
                        $workingtimestamp = 0;
                    }
                }
            }
            if($workingtimestamp){//file is up-to-date, just spit it out
                echo file_get_contents($workingfile);
            }
        }
    }

    if($workingtimestamp == 0){//file is not up-to-date or does not exist, update it
        $entirefile = "/* Generated at " . time () . " */";
        foreach($files as $file){
            $orig = $file;
            if(strpos($file, ".") === false){
                $file .= ".js";
            }
            $file = $root . $path . $file;
            if(file_exists($file)){
                $entirefile .= " /*" . $orig . "*/ " . minify_JS(file_get_contents($file));
            } else {
                $entirefile .= " /*" . $orig . " NOT FOUND!*/ ";
            }
        }
        file_put_contents($workingfile, $entirefile);
        echo $entirefile;
    }

    function minify_JS($code){

        $code = str_replace(array(";", "//"), array(" ; ", " // "), $code);//error handling
        $code = preg_replace( "/(?<!\:)\/\/(.*)\\n/", "", $code );//single line comments
        $code = preg_replace('!/\*.*?\*/!s', '', $code);//multi line comments
        $code = preg_replace('/\n\s*\n/', "\n", $code);//multi line comments

        $code = str_replace(array("\n","\r", "\t"), '',$code);// make it into one long line
        $code = filterduplicates($code, "   ", "  ");// replace all triple spaces with two spaces (two spaces are needed)
        $code = str_replace(array('new Array()', 'new Array', "{ ", "} "), array('[]', '[]', "{", "}"),$code);

        foreach(array(",", "(", ")", "+", "=", "<", ">", "||", ":", ";", ";", "{", "}", "&&") as $char){
            $code = str_replace(array($char . " ", " " . $char, " " . $char . " "), $char, $code);
        }
        return $code;// replace some unneeded spaces, modify as needed
    }
?>