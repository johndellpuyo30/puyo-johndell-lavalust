<?php
class UserPasswordCommand
{
    public static $command = 'user:password';
    public static $description = 'Change an application user password securely';
    public static $arguments = ['username' => 'Account username (for example: lab6admin)'];

    public function handle($username = null, array $flags = [])
    {
        if (!$username || !preg_match('/^[A-Za-z0-9_.-]{1,100}$/D', $username)) {
            fwrite(STDERR, "Usage: php lava user:password <username>\n");
            exit(1);
        }

        fwrite(STDOUT, 'New password (12-72 characters): ');
        $password = fgets(STDIN);
        fwrite(STDOUT, 'Confirm new password: ');
        $confirmation = fgets(STDIN);

        if ($password === false || $confirmation === false) {
            fwrite(STDERR, "Could not read the password input.\n");
            exit(1);
        }

        $password = rtrim($password, "\r\n");
        $confirmation = rtrim($confirmation, "\r\n");

        if (strlen($password) < 12 || strlen($password) > 72) {
            fwrite(STDERR, "Choose a memorable passphrase between 12 and 72 characters.\n");
            exit(1);
        }
        if (!hash_equals($password, $confirmation)) {
            fwrite(STDERR, "The passwords did not match. No change was made.\n");
            exit(1);
        }

        // Pass only the hash to the child process, never the plaintext password.
        putenv('LAB6_PASSWORD_HASH=' . password_hash($password, PASSWORD_DEFAULT));
        if (function_exists('sodium_memzero')) {
            sodium_memzero($password);
            sodium_memzero($confirmation);
        }

        $route = 'lab6-password/' . rawurlencode($username);
        passthru(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(PUBLIC_DIR . 'index.php') . ' ' . escapeshellarg($route), $code);
        putenv('LAB6_PASSWORD_HASH');
        exit($code);
    }
}
