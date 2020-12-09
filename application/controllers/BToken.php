<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;

class Token extends CI_Controller {

  public function index(){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

    $privateKey = <<<EOD
    -----BEGIN PRIVATE KEY-----
    MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDKyYPdIaXNfeGr
    a5B1NFUFvI4Mv/CDqgxKHuK24DmsDGUvVNHHRMb7UMoOPpcjEi5UvCOqGL15j7Ea
    mVTDA+8/EfifsW4KvN3pzbBIvAs6h9hje4VPhqk61w7EhnbkKZ6Zi29w0l+8/IIo
    6S1i+ToJYWD5TcBniTwIvLrggsgjYlZjinV0zobayVNpk6MLCS/HWHPNtSI5PniN
    Sjsuaoy16JvUxwKZuLv+Qp/JJYGlnIn8RJQkoAm28bA4vqyBvFNDNncmx1NzNXDB
    Lf53PvU53ewLpfesuw/OylVcPwQxYU0NyMUcnSWwQUEGcifp8mphffoGej0XO4K7
    n9RSJhlbAgMBAAECggEAdw9lmll9DW9RJeIbiJTXLm0MQbQXtFYMrSABIDn54mfM
    qMN1/zcEVN2UJFTfS7oK9bkYf2/THyIca2+G8pDG0RLuFGSKJUfKStF3JN0zAoRr
    qc0F8jEv/tnxU7VW7JL7CU9yeJ0rlLv0d6yf9zI4vvUriHP7+U37r22Pku12MD8q
    lhp8gvvvJAmWB6JTh2PD3unah+wcLXDoTWiw62C3hCn7X9fnyGeDdATMf1Jo0AA4
    snThJnMdfDSxlFJtVbG0t7486EOimfS+b87M2uxAry0dxLPHTtaQ9/DEyTQ7/0I9
    jvYjPOhtAHBZc8mhtocThhDzA9iO0c+29YpHwy6FoQKBgQDszVwHYFwkHA/sEUzy
    4G2maBAx5btX0yY8Pe+t42zGf5ABgPVrlbaD2zeZuwpnVticsK0I5nSFEP5YpSBm
    eI05DPI31htdcOr9+IGltGKgyEcC3sOfKUHNZXTjxggnHpsYmAqAVsS7XEQQWekM
    kynm0y+M4sphxNSn1/MJjoj7mQKBgQDbOjN4ECjC1+UZ5N5Ape9icl2GTVNazUO4
    6Hnfbqr9Yz6y01MTd6v4AOyMfObEpvqw6CFR7HsXHYGq8nqWnjiS4JFQKFYyMjKn
    clIE5LRcPriHkyVo0q/bQfLn2ngHpMGRotBPh9qBSfysAlqhldLCaEKu1zSEIvKs
    LQY93gL1EwKBgFudO8ySyDMkNDjFnLqef22Q9ysG0UsyIqnN4Iuq4CuPsJwUU17T
    JjCvQDyWs3i1jcpZRCicWFoe7/hFslpSq3h1/MQDbsTg6dlmKmp8dmfz9B01KAPM
    x2t6pBV3STIxUhnawL1UVHqUQLT+w/4cdWqbK9ta5qeaqhdhoeKBpZeJAoGAfXG/
    U9uDR4L8kKWa28lXwrCIfbovkUzVjLBSVJzh8R5iGTe9WO1olQAKW5V5A1w9JQ+f
    SV6VTLPQp/4aUad13e3smL6MHCsHOzO6ZRxtbD9jngiAJowwnrkNAsjLWCxZqzlM
    E8Y8LTEmCVNLgxzVPfMk/SEd5uKMlCMraClq7csCgYBQ0lzyt28ydHL5o6yQWlsQ
    gGDTvAtGiPubmnAI1hWuW6jsVkdQLUIESNkJddDJrKYsdnscLUV96Vvd8XF0PL7X
    2iJZGNqmAHQtvNiA6acODctWNKH74NF2KsmqWo94eAT8BYnvK1WlHxR9OKCB5QVq
    nOM42mX/29Y2DW/2bmeh6A==
    -----END PRIVATE KEY-----
    EOD;

    $payload = array(
      "sub" => "123",
      "name" => "Yohanes Dwi Listio",
      "exp" => (time() + 60 * 10)
    );

    try {
      $token = JWT::encode($payload, $privateKey, 'RS256');
      http_response_code(200);
      header('Content-Type: application/json');
      echo json_encode(array("token" => $token));
    } catch (Exception $e) {
      http_response_code(500);
      header('Content-Type: application/json');
      echo $e->getMessage();
    }
  }
}
?>
