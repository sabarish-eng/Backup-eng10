<?php
$domain = "fantasysolution.in";
$dns_records = dns_get_record($domain, DNS_ALL);

if ($dns_records) {
    echo "<h3>DNS Records for $domain:</h3>";
    echo "<pre>";
    print_r($dns_records);
    echo "</pre>";
} else {
    echo "No DNS records found for $domain.";
}
?>
