<?php

/**
 *
 * http://localhost:9999/login?user=luke.sands@cabinetoffice.gov.uk
 *
 * http://localhost:9999/auth/?token=790386a3cfc6d69a17df5fa04b0c7ae52cf6e1b12c723bfd2337ad1e1de09b86&user=K0h5RzR3VUsyWnFHS1hPYTdsSFhwZ0VTbVgxSHhHc3FXZXk0SytXeUY0bz06OkbW/tlHvQO0tKV7H7SHBS4=&time=101010
 */
class AuthManager {

	/**
	 * Determine whether user has a valid token
	 */
	public static function auth() {

		$encryptionSecret = getenv('TOKEN_ENCRYPTION_SECRET');
		$hashSecret = getenv('TOKEN_HASH_SECRET');

		$time = get_query_var('time', 1);
		$user = get_query_var('user', 1);
		$token = get_query_var('token', 1);

		// Decrypt the user
		$decryptedUser = AuthManager::decrypt($user, $encryptionSecret);

		// Create a hash
		$hash = hash_hmac('sha256', $time.$decryptedUser, $hashSecret, false);

		// Determine whether token matches our hash
		if ($hash === $token) {

			// Determine whether token has expired

			return true;
		}

		return false;
	}

	/**
	 * Log user in (partial)
	 */
	public static function login() {

		$encryptionSecret = getenv('TOKEN_ENCRYPTION_SECRET');

		$user = 'luke.sands@cabinetoffice.gov.uk';

		// Encrypt the email
		$encryptedUser = AuthManager::encrypt($user, $encryptionSecret);
		var_dump($encryptedUser);

		// Build the token

		// Call Notify

		return true;
	}

	public static function encrypt($data, $key) {
	    // Remove the base64 encoding from our key
	    $encryption_key = base64_decode($key);
	    // Generate an initialization vector
	    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
	    // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
	    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
	    // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
	    return base64_encode($encrypted . '::' . $iv);
	}

	public static function decrypt($data, $key) {
	    // Remove the base64 encoding from our key
	    $encryption_key = base64_decode($key);
	    // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
	    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
	    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
	}

}