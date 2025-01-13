<?php

if (!function_exists('parsePath')) {
/**
 * Determine if the input is a directory path or a directory path with a filename.
 *
 * @param string $input
 * @return array
 */

    function parsePath($input)
    {
        
        $pathInfo = pathinfo($input);

        // Check if the input has a filename
        if (isset($pathInfo['extension'])) {
            // Input contains a filename
            return [
                'directory' => $pathInfo['dirname'],
                'filename' => $pathInfo['basename'],
            ];
        } else {
            // Input is only a directory path
            return [
                'directory' => $input,
                'filename' => null,
            ];
        }
    }
}