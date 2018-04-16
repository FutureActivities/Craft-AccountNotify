<?php

return array (
	'user_notify_heading' => 'When someone has modified their account:',
	'user_notify_subject' => 'Account Updated',
	'user_notify_body' => "Hi,\n\n" .
		"Your account on {{siteName}} has recently been updated.\n\n" .
		"{% for field, value in diff %}\n" .
		"Your {{ field }} has been changed from {{ value.was }} to {{ value.now }}.\n\n" .
	    "{% endfor %}\n\n" .
		"If you did not authorise this change then please contact us immediately.\n"
);