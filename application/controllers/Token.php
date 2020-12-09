<?php defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;

Class Token extends CI_Controller{
  public function index(){
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

    $privateKey = "
    -----BEGIN PRIVATE KEY-----
    MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDKyYPdIaXNfeGra5B1NFUFvI4Mv/CDqgxKHuK24DmsDGUvVNHHRMb7UMoOPpcjEi5UvCOqGL15j7EamVTDA+8/EfifsW4KvN3pzbBIvAs6h9hje4VPhqk61w7EhnbkKZ6Zi29w0l+8/IIo
    6S1i+ToJYWD5TcBniTwIvLrggsgjYlZjinV0zobayVNpk6MLCS/HWHPNtSI5PniNSjsuaoy16JvUxwKZuLv+Qp/JJYGlnIn8RJQkoAm28bA4vqyBvFNDNncmx1NzNXDBLf53PvU53ewLpfesuw/OylVcPwQxYU0NyMUcnSWwQUEGcifp8mphffoGej0XO4K7n9RSJhlbAgMBAAECggEAdw9lmll9DW9RJeIbiJTXLm0MQbQXtFYMrSABIDn54mfMqMN1/zcEVN2UJFTfS7oK9bkYf2/THyIca2+G8pDG0RLuFGSKJUfKStF3JN0zAoRrqc0F8jEv/tnxU7VW7JL7CU9yeJ0rlLv0d6yf9zI4vvUriHP7+U37r22Pku12MD8qlhp8gvvvJAmWB6JTh2PD3unah+wcLXDoTWiw62C3hCn7X9fnyGeDdATMf1Jo0AA4snThJnMdfDSxlFJtVbG0t7486EOimfS+b87M2uxAry0dxLPHTtaQ9/DEyTQ7/0I9jvYjPOhtAHBZc8mhtocThhDzA9iO0c+29YpHwy6FoQKBgQDszVwHYFwkHA/sEUzy4G2maBAx5btX0yY8Pe+t42zGf5ABgPVrlbaD2zeZuwpnVticsK0I5nSFEP5YpSBmeI05DPI31htdcOr9+IGltGKgyEcC3sOfKUHNZXTjxggnHpsYmAqAVsS7XEQQWekMkynm0y+M4sphxNSn1/MJjoj7mQKBgQDbOjN4ECjC1+UZ5N5Ape9icl2GTVNazUO46Hnfbqr9Yz6y01MTd6v4AOyMfObEpvqw6CFR7HsXHYGq8nqWnjiS4JFQKFYyMjKnclIE5LRcPriHkyVo0q/bQfLn2ngHpMGRotBPh9qBSfysAlqhldLCaEKu1zSEIvKsLQY93gL1EwKBgFudO8ySyDMkNDjFnLqef22Q9ysG0UsyIqnN4Iuq4CuPsJwUU17TJjCvQDyWs3i1jcpZRCicWFoe7/hFslpSq3h1/MQDbsTg6dlmKmp8dmfz9B01KAPMx2t6pBV3STIxUhnawL1UVHqUQLT+w/4cdWqbK9ta5qeaqhdhoeKBpZeJAoGAfXG/U9uDR4L8kKWa28lXwrCIfbovkUzVjLBSVJzh8R5iGTe9WO1olQAKW5V5A1w9JQ+fSV6VTLPQp/4aUad13e3smL6MHCsHOzO6ZRxtbD9jngiAJowwnrkNAsjLWCxZqzlME8Y8LTEmCVNLgxzVPfMk/SEd5uKMlCMraClq7csCgYBQ0lzyt28ydHL5o6yQWlsQgGDTvAtGiPubmnAI1hWuW6jsVkdQLUIESNkJddDJrKYsdnscLUV96Vvd8XF0PL7X2iJZGNqmAHQtvNiA6acODctWNKH74NF2KsmqWo94eAT8BYnvK1WlHxR9OKCB5QVqnOM42mX/29Y2DW/2bmeh6A==
    -----END PRIVATE KEY-----";

    // NOTE: Before you proceed with the TOKEN, verify your users session or access.

    $payload = array(
      "sub" => "123", // unique user id string
      "name" => "Yohanes Dwi Listio", // full name of user


      "exp" => time() + 60 * 10 // 10 minute expiration
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