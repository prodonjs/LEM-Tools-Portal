<?php
/**
 * Copyright 2010 - 2013, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2013, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

echo "Hello, {$user[$model]['username']}\n\n";

echo "Your user information has been confirmed by our administrators. ";
echo "Please click the link below within 24 hours to confirm your registration.\n\n";

echo Router::url(array(
    'admin' => false,
    'controller' => 'lem_users',
    'action' => 'verify',
    'email',
    $user[$model]['email_token']
), true) . "\n\n";

echo "Thank you for your patience and for being our customer.";