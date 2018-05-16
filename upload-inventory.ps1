add-type @"
    using System.Net;
    using System.Security.Cryptography.X509Certificates;
    public class TrustAllCertsPolicy : ICertificatePolicy {
        public bool CheckValidationResult(
            ServicePoint srvPoint, X509Certificate certificate,
            WebRequest request, int certificateProblem) {
            return true;
        }
    }
"@
[System.Net.ServicePointManager]::CertificatePolicy = New-Object TrustAllCertsPolicy

$url = "https://file-content-url.com";
$path = "C:\your-file-path-to-write-to";
$ftp = "ftp://username:password@website.com/folder1/folder2/inventory.json";

$securePassword = ConvertTo-SecureString "password" -AsPlainText -Force;
$credentials = New-Object System.Management.Automation.PSCredential("username",$securePassword);

$data = Invoke-WebRequest -Uri $url -Credential $credentials;

$data.content | Out-File $path -Encoding "UTF8" -Force;

$webClient = New-Object System.Net.WebClient;

$uri = New-Object System.Uri($ftp);
$webClient.UploadFile($uri, $path);