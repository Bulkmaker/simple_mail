<?php
$project_name = "Brusmir.ru";
$admin_email  = "test@brusmir.ru";
$form_subject = trim($_POST["form_subject"]);

foreach ( $_POST as $key => $value ) {
    if ( $value != "" && $key != "form_subject" ) {
        $print_key = str_replace('_',' ',$key);
        if (substr($value,0,4) == 'http') {
            $links = explode(',',$value);
            $value = '';
            foreach ($links as $id => $link) {
                $value .= "<a href='".$link."' target='_blank'>".$link."</a><br>";
            }
        }
        $message .= "
			" . (($c = !$c) ? '<tr>' : '<tr style="background-color: #f8f8f8;">') . "
				<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$print_key</b></td>
				<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
			</tr>
			";
    }
}
$message = "<table style='width: 100%;'>$message</table>";
//echo $message;

$headers = "MIME-Version: 1.0" . PHP_EOL .
    "Content-Type: text/html; charset=utf-8" . PHP_EOL .
    'From: '.adopt($project_name).' <'.$admin_email.'>' . PHP_EOL .
    'Reply-To: '.$admin_email.'' . PHP_EOL;
if (mail($admin_email, adopt($form_subject), $message, $headers )) {
    echo '200';
} else {
    echo '500';
}


function adopt($text) {
    return '=?UTF-8?B?'.Base64_encode($text).'?=';
}