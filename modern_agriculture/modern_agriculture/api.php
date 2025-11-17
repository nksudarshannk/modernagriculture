<?php
include 'db.php'; 
error_reporting(E_ALL);
ini_set('display_errors', 1); 
date_default_timezone_set('Asia/Kolkata');

$difficulty = 4; 
define('ENCRYPTION_KEY', 'dfhwsdfghdfghsjiwi1318r'); 

// --- ENCRYPTION FUNCTIONS ---
function encryptData($plaintext) {
    $key = ENCRYPTION_KEY;
    $ivlen = openssl_cipher_iv_length($cipher="AES-256-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    return base64_encode($iv . $ciphertext_raw);
}

function decryptData($ciphertext_base64) {
    $key = ENCRYPTION_KEY;
    $c = base64_decode($ciphertext_base64);
    $ivlen = openssl_cipher_iv_length($cipher="AES-256-CBC");
    $iv = substr($c, 0, $ivlen);
    $ciphertext_raw = substr($c, $ivlen);
    return openssl_decrypt($ciphertext_raw, $cipher, $key, OPENSSL_RAW_DATA, $iv);
}

// --- HASH & POW ---
function calculateHash($index, $timestamp, $encryptedData, $previousHash, $nonce) {
    return hash('sha256', $index . $timestamp . $encryptedData . $previousHash . $nonce);
}

function proofOfWork($index, $timestamp, $encryptedData, $previousHash, $difficulty) {
    $nonce = 0;
    $prefix = str_repeat("0", $difficulty);
    do {
        $hash = calculateHash($index, $timestamp, $encryptedData, $previousHash, $nonce);
        $nonce++;
    } while (substr($hash, 0, $difficulty) !== $prefix);
    return [$nonce - 1, $hash];
}

// --- GET LATEST BLOCK ---
function getLatestBlock($conn) {
    $sql = "SELECT * FROM modern_new_data ORDER BY block_index DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

// --- CREATE GENESIS BLOCK ---
function createGenesisBlock($conn, $difficulty) {
    $index = 0;
    $timestamp = date("Y-m-d H:i:s");
    $ph = "Genesis Block";
    $encryptedData = encryptData($ph);
    $previousHash = str_repeat("0", 64);
    list($nonce, $hash) = proofOfWork($index, $timestamp, $encryptedData, $previousHash, $difficulty);
    $token = bin2hex(random_bytes(8)); // generate token

    $stmt = $conn->prepare("INSERT INTO modern_new_data (token, block_index, timestamp, ph, previous_hash, nonce, current_hash) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssis", $token, $index, $timestamp, $encryptedData, $previousHash, $nonce, $hash);
    $stmt->execute();
    $stmt->close();

   return [
    "token" => $token,
    "block_index" => $index,
    "timestamp" => $timestamp,
    "ph" => $ph,
    "previous_hash" => $previousHash,
    "nonce" => $nonce,
    "current_hash" => $hash
];

}

// --- ADD NEW BLOCK ---
function addBlock($conn, $ph, $difficulty) {
    $latestBlock = getLatestBlock($conn);
    if (!$latestBlock) {
        $latestBlock = createGenesisBlock($conn, $difficulty);
    }

    $index = $latestBlock['block_index'] + 1;
    $timestamp = date("Y-m-d H:i:s");
    $previousHash = $latestBlock['current_hash'];
    $encryptedData = encryptData($ph);
    list($nonce, $hash) = proofOfWork($index, $timestamp, $encryptedData, $previousHash, $difficulty);
    $token = bin2hex(random_bytes(8));

    $stmt = $conn->prepare("INSERT INTO modern_new_data (token, block_index, timestamp, ph, previous_hash, nonce, current_hash) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssis", $token, $index, $timestamp, $encryptedData, $previousHash, $nonce, $hash);

    if ($stmt->execute()) {
        $stmt->close();
        return [
            "Status" => "Block added successfully",
            "Block" => [
                "token" => $token,
                "index" => $index,
                "timestamp" => $timestamp,
                "ph" => $encryptedData,
                "previous_hash" => $previousHash,
                "nonce" => $nonce,
                "current_hash" => $hash
            ]
        ];
    } else {
        $stmt->close();
        return ["Status" => "Error inserting block", "Error" => $conn->error];
    }
}

// --- VALIDATE BLOCKCHAIN ---
function isChainValid($conn, $difficulty) {
    $sql = "SELECT * FROM modern_new_data ORDER BY block_index ASC";
    $result = $conn->query($sql);
    if (!$result || $result->num_rows == 0) return false;

    $prefix = str_repeat("0", $difficulty);
    $blocks = $result->fetch_all(MYSQLI_ASSOC);

    for ($i = 1; $i < count($blocks); $i++) {
        $current = $blocks[$i];
        $previous = $blocks[$i - 1];
        $recalculatedHash = calculateHash(
            $current['block_index'],
            $current['timestamp'],
            $current['ph'],
            $current['previous_hash'],
            $current['nonce']
        );

        if ($current['current_hash'] !== $recalculatedHash) return false;
        if ($current['previous_hash'] !== $previous['current_hash']) return false;
        if (substr($current['current_hash'], 0, $difficulty) !== $prefix) return false;
    }
    return true;
}

// --- MAIN PROCESS ---
$ph = isset($_GET['ph']) ? $_GET['ph'] : null;
if ($ph === null) {
    echo json_encode(["Status" => "Please provide 'ph' parameter."]);
    exit;
}

$response = addBlock($conn, $ph, $difficulty);
echo json_encode($response);

$valid = isChainValid($conn, $difficulty);
echo "\nChain valid? " . ($valid ? "Yes" : "No");

$conn->close();
?>
