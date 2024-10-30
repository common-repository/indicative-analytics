<?php

/**
 * Originally code was part of the Laravel Framework licensed under the open source MIT license.
 * @license http://opensource.org/licenses/MIT
 */

namespace Dragon;

class DragonException extends \Exception{};
class DragonEncryptorRuntimeException extends DragonException{};
class DragonEncryptionException extends DragonException{};
class DragonDecryptionException extends DragonException{};

class Encrypter
{
    protected $key;
    protected $cipher;

    public function __construct($cipher = 'AES-256-CBC')
    {
    	static::ensureKeyExists();
    	$key = Config::$encryptionKey;

    	if (static::supported($key, $cipher) && !is_null($key)) {
            $this->key = $key;
            $this->cipher = $cipher;
        } else {
        	throw new DragonEncryptorRuntimeException(
        		'The only supported ciphers are AES-128-CBC and AES-256-CBC with the correct key lengths.'
        	);
        }
    }

    public static function generateKey($cipher = 'AES-256-CBC')
    {
        return random_bytes($cipher == 'AES-128-CBC' ? 16 : 32);
    }

    public function encrypt($value, $serialize = true)
    {
    	if (is_null($value)) {
    		return null;
    	}
    	
        $iv = random_bytes(openssl_cipher_iv_length($this->cipher));

        // First we will encrypt the value using OpenSSL. After this is encrypted we
        // will proceed to calculating a MAC for the encrypted value so that this
        // value can be verified later as not having been changed by the users.
        $value = \openssl_encrypt(
            $serialize ? serialize($value) : $value,
            $this->cipher, $this->key, 0, $iv
        );

        if ($value === false) {
        	throw new DragonEncryptionException('Could not encrypt the data.');
        }

        // Once we get the encrypted value we'll go ahead and base64_encode the input
        // vector and create the MAC for the encrypted value so we can then verify
        // its authenticity. Then, we'll JSON the data into the "payload" array.
        $mac = $this->hash($iv = base64_encode($iv), $value);

        $json = json_encode(compact('iv', 'value', 'mac'));

        if (json_last_error() !== JSON_ERROR_NONE) {
        	throw new DragonEncryptionException('Could not encrypt the data.');
        }

        return base64_encode($json);
    }

    public function decrypt($payload, $unserialize = true)
    {
        $payload = $this->getJsonPayload($payload);

        $iv = base64_decode($payload['iv']);

        // Here we will decrypt the value. If we are able to successfully decrypt it
        // we will then unserialize it and return it out to the caller. If we are
        // unable to decrypt this value we will throw out an exception message.
        $decrypted = \openssl_decrypt(
            $payload['value'], $this->cipher, $this->key, 0, $iv
        );

        if ($decrypted === false) {
        	throw new DragonDecryptionException('Could not decrypt the data.');
        }

        return $unserialize ? unserialize($decrypted) : $decrypted;
    }
    
    protected static function supported($key, $cipher)
    {
    	$length = mb_strlen($key, '8bit');
    	
    	return ($cipher === 'AES-128-CBC' && $length === 16) ||
    	($cipher === 'AES-256-CBC' && $length === 32);
    }

    protected function hash($iv, $value)
    {
        return hash_hmac('sha256', $iv.$value, $this->key);
    }

    protected function getJsonPayload($payload)
    {
        $payload = json_decode(base64_decode($payload), true);

        // If the payload is not valid JSON or does not have the proper keys set we will
        // assume it is invalid and bail out of the routine since we will not be able
        // to decrypt the given value. We'll also check the MAC for this encryption.
        if (! $this->validPayload($payload)) {
        	throw new DragonDecryptionException('The payload is invalid.');
        }

        if (! $this->validMac($payload)) {
        	throw new DragonDecryptionException('The MAC is invalid.');
        }

        return $payload;
    }

    protected function validPayload($payload)
    {
        return is_array($payload) && isset(
            $payload['iv'], $payload['value'], $payload['mac']
        );
    }

    protected function validMac(array $payload)
    {
        $calculated = $this->calculateMac($payload, $bytes = random_bytes(16));

        return hash_equals(
            hash_hmac('sha256', $payload['mac'], $bytes, true), $calculated
        );
    }

    protected function calculateMac($payload, $bytes)
    {
        return hash_hmac(
            'sha256', $this->hash($payload['iv'], $payload['value']), $bytes, true
        );
    }
    
    private static function ensureKeyExists()
    {
    	if (!defined('DRAGON_ENCRYPTION_KEY')) {
    		
    		$key = static::generateKey();
    		FileSystem::defineInWpConfig('DRAGON_ENCRYPTION_KEY', base64_encode($key));
    		
    	}
    	
    	if (!defined('DRAGON_ENCRYPTION_KEY')) {
    		throw new DragonEncryptorRuntimeException('Could not define encryption key.');
    	}
    	
    	if (FileSystem::stringExistsInWpConfig('DRAGON_ENCRYPTION_KEY')) {
    		Config::$encryptionKey = base64_decode(DRAGON_ENCRYPTION_KEY);
    	}
    }
}
