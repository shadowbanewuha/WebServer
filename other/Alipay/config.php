<?php
$alt_alipay_config = array (
		//签名方式,默认为RSA2(RSA2048)
		'sign_type' => "RSA2",

        //支付宝公钥
        'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwrjwjZ6mDfVPFy7M7I+M+8hPoFV284EYL7l3Af7f/+78iYA3dchye3RGtn0pVqY00sLb6edntDtU3j31vZDDQcMYkFzhkeB8U+WiM+rg13RVSosUR3Up/+aHdyCnzAo5VfAJuMtPIb2Ivxu+UXsv03xL7GDGc4dHsPe4PO4nPZ/Bn51xVCqFw11KsbIEL3GPWUj9m3S834oou5UxqzzXqru1RVfwarqDnJEgw/3mBCtf0d2ebSjGXGdgn8JY6qv1p90XMs9VJCofkAokD08ckWAOkYYrVflnbNuqfcuARgDnR4qlDbQNgGJ+9Z3H34wUo9cIQXT+r/AaxzgqX8nJaQIDAQAB",

        //商户私钥
        'merchant_private_key' => "MIIEuwIBADANBgkqhkiG9w0BAQEFAASCBKUwggShAgEAAoIBAQC0tgUkKbzPS4ZAJmR9YokiAoAKaG1Zqer6Dyar1mnJltDIetX9IcfZsiILrWe2ueN4vPE7glD+QHxj9yaWwDRvJOXWAxBoQuVDBkhwbx+0gv/an+yZi2ubD5wy6BKRV/78skbvDQox3pSg6VYu5EHrQRWp70p5QS4qmpE1DEVQUXg7YzTYUdtFB1rPE+5jn+e+9P1+FGDPBMIMF1xOHEKhL527A4Pt/7fZ4XR+WeDcAIQTc288+BM899LwBTATmGjh+lCKfxvcRtXNAsvY61iEu7ISnobJpLd0TIqd5KHtF7BkLpg92HSCBclnODO/WfLu9Z0UAiRmrHBiVw1vSbRVAgMBAAECggEASdQ/n3mp2OsbKyREU/Go4wswcpAddM9137nG69eKmsCSBgYhXOyrMKaVQD4VLFMfuxk+WTixjF6eM10rF9Xo1iu19syDrIUAE3UCrWTJCD+o9hH2YPjT+qLTBv1HcLgJxgWz80BZlOxBvsf13OyGfe22QxovTAC0MtW5U3ny64/ckn2XdN+f4U1OdbLU8szkTLzQyj4CI1dCiZ3FQBuhrTLuRLmn29YsxbI6AfzCNyXGPw7SN/Sl+jzlaIGt3Y0XU7nZkURksybjlV675U0lQ2g4JaoqXq8bGYk6vA7y+4ZBmU9EuRiVZv4mlneCS5fEHjbbiyrsK/wjCmmNGjr7AQKBgQDvfP/0ZMSzBHsIwwzOTP8R6xD0l9LOd3iDn6PvTSgmzunUgL5pMoeIZRQaALxTIYHvwRnItJ1AYH/qhyBt8gYD9Wl61TFJcb6uDSw1t9HRpcbmboggW+PksKeZqW/LEhDrYRESlbYE1cDFyoHFJbZgF/K/HqinEg8AHkUqOb+RFQKBgQDBK5f7Z+LRaKo65j1GB9RBFCjmE8ADqsOryQw5U6sSs5+Pk751Z9Zz26bL0Sm/N8skMKV/HTa2OgQWXcrQupRYi0xkqmA/IX+DtzYF/CU73i/NODwSHD1jXG2vST9uJZUCfpvG4SeCKKBTweeUIuOH69XsHkRRY2mnsjzIlVnmQQJ/MZSvaOlzz5wd3LKE9DD8nbkMyZalDhmt2ZIzXlea+G3d+yCsSxsq9BH5F2kPlCZbwEc6D1NVOv6/fM586EYy+J7WN5frnEjwMRiJDpX/2md3xj0b8ZcUl0P7btSlUGVtG039OtvD0zxtrIVAWJB2MW7D0s2GHRCgCmY564QJJQKBgA0XACiIJKWeGxL8sSBCvY5faFoW8ocNFk9Yw1xLICeXRnO7Rke8bprRQp9DIDoV9M0SAtT3TNLSYZ6GYoz8Z7sMTXPw267LfVGWo7GKIZfJ+aFFc0Si50IJbGFZbFPxdgSCQdobPcsjJ6IlXKT8LED0qT1j26tMOkej21yYSkxBAoGBAJ6YVtneZiji7ajNsP2vpfNKvJARXl238Ff7WBF/atQHYyQHmJ6a54sxdPUb1/4S3bl2zydZJjByo8U6MdeD6B3kjVXxWl8jmfX5F/xJElSjvygkMSj4CGfT+4E7nyoDFY8ZuxExMJx/vOEyhU71OXZe1QpEUf9OnPf50r6i9Jgg",

        //编码格式
        'charset' => "UTF-8",

        //支付宝网关
        'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

        //应用ID
        'app_id' => "2021001132627122",

        'seller_email' => '601728235@qq.com',

		//异步通知地址,只有扫码支付预下单可用
		'notify_url' => "www.altai.cn:10999/other/Alipay/notify.php",

		//最大查询重试次数
		'MaxQueryRetry' => "10",

		//查询间隔
		'QueryDuration' => "3"
);