<?php

function debug($tableau) {
	echo '<pre>'.print_r($tableau, true).'</pre>';
}

function redirectJS($url, $delay = 1) {
    echo '<script>
          setTimeout(function() {
                location.href = "'.$url.'"; }
          , '.($delay * 1000).');
          </script>';
}
