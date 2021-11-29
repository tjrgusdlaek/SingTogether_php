<?php
/**
 * 동영상 관련 PHP/모바일앱개발 어플리케이션을 개발하기전

*/

@exec('ffmpeg -version 2>&1', $output, $returnvalue);
if ($returnvalue === 0) {
    echo 'installed';
    exit;
}
@exec('avconv -version 2>&1', $output, $returnvalue);
if ($returnvalue === 0) {
    echo 'installed';
    exit;
}
echo 'NOT installed';

exit;
?>