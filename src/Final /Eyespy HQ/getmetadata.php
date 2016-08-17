<?php
// Assuming the above tags are at www.example.com
$tags = get_meta_tags('http://php.net/');

// Notice how the keys are all lowercase now, and
// how . was replaced by _ in the key.
echo $tags['keywords'];     // php documentation
?>