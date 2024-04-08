<?php
function genDefaultLibImg($photoPath, $args = []) {
    $defaultKwargs = [
        "extraClass" => "",
        "height" => "",
        "width" => ""
    ];
    $mergedArgs = array_merge($defaultKwargs, $args);

    if (!empty($photoPath) && file_exists($photoPath)) {
        return "<img class='" . $mergedArgs["extraClass"] . "' src='/" . $photoPath . "' width='". $mergedArgs["width"] ."' height='". $mergedArgs["height"] ."'>";
    } else {
        return "<img class='" . $mergedArgs["extraClass"] . "' src='/media/libraries/defaults/default_library_img.webp' alt='You should not see this' width='" . $mergedArgs["width"] . "' height='". $mergedArgs["height"] ."'>";
    }
}
