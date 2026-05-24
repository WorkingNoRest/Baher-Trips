<?php
// السماح للمتصفح بقرائتها بدون CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// دالة لجلب البيانات من 3 صفحات ودمجهم مع بعض ليعطيك 12 رحلة
function getFlightsFromPage($page) {
    $api_token = '99a7877a26a87b3d358e1eff089501c5';
    $url = "https://api.travelpayouts.com/v2/prices/latest?currency=usd&period_type=year&page=" . $page . "&limit=4&token=" . $api_token;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // تزوير الـ User Agent لتبدو كأنها قادمة من متصفح كروم حقيقي لتخطي حماية InfinityFree
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

$all_flights = [];

// جلب 3 صفحات لضمان ظهور 12 رحلة
for ($p = 1; $p <= 3; $p++) {
    $res = getFlightsFromPage($p);
    if ($res) {
        $data = json_decode($res, true);
        if (isset($data['success']) && $data['success'] && isset($data['data'])) {
            $all_flights = array_merge($all_flights, $data['data']);
        }
    }
}

// طباعة النتيجة النهائية كـ JSON
echo json_encode([
    "success" => true,
    "data" => $all_flights
]);
?>