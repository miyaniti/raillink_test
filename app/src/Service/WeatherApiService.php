<?php

namespace App\Service;

class WeatherApiService
{
    const CNT = 5; // 天気取得

    const WEATHER_MAP = [
        '200' => '小雨と雷雨',
        '201' => '雨と雷雨',
        '202' => '大雨と雷雨',
        '210' => '光雷雨',
        '211' => '雷雨',
        '212' => '重い雷雨',
        '221' => 'ぼろぼろの雷雨',
        '230' => '小雨と雷雨',
        '231' => '霧雨と雷雨',
        '232' => '重い霧雨と雷雨',
        '300' => '光強度霧雨',
        '301' => '霧雨',
        '302' => '重い強度霧雨',
        '310' => '光強度霧雨の雨',
        '311' => '霧雨の雨',
        '312' => '重い強度霧雨の雨',
        '313' => 'にわかの雨と霧雨',
        '314' => '重いにわかの雨と霧雨',
        '321' => 'にわか霧雨',
        '500' => '小雨',
        '501' => '適度な雨',
        '502' => '重い強度の雨',
        '503' => '非常に激しい雨',
        '504' => '極端な雨',
        '511' => '雨氷',
        '520' => '光強度のにわかの雨',
        '521' => 'にわかの雨',
        '522' => '重い強度にわかの雨',
        '531' => '不規則なにわかの雨',
        '600' => '小雪',
        '601' => '雪',
        '602' => '大雪',
        '611' => 'みぞれ',
        '612' => 'にわかみぞれ',
        '615' => '光雨と雪',
        '616' => '雨や雪',
        '620' => '光のにわか雪',
        '621' => 'にわか雪',
        '622' => '重いにわか雪',
        '701' => 'ミスト',
        '711' => '煙',
        '721' => 'ヘイズ',
        '731' => '砂、ほこり旋回する',
        '741' => '霧',
        '751' => '砂',
        '761' => 'ほこり',
        '762' => '火山灰',
        '771' => 'スコール',
        '781' => '竜巻',
        '800' => '晴天',
        '801' => '薄い雲',
        '802' => '雲',
        '803' => '曇りがち',
        '804' => '厚い雲',
        // 他の天気状態もここに追加
    ];

    const PLACE_MAP = [
        "Tokyo" => '東京',
        'Osaka' => '大阪',
    ];

    const TEMP_MINUS = 273.15;

    public function getWeathers($city = 'Tokyo',$limit = self::CNT)
    {
        $apiUrl = "http://api.openweathermap.org/data/2.5/forecast?q=" . $city . "&appid=" . WEATHERS_API_KEY . "&cnt=". $limit;
        $response = file_get_contents($apiUrl);
        $rows = json_decode($response, true);
        $weathers = [];
        foreach($rows['list'] as $row){
            $weathers[] = [
                'place' => self::PLACE_MAP[$city] ?? 'その他の場所',
                'date' => date('m/d　H時', $row['dt']),
                'temp' => $row['main']['temp'] - self::TEMP_MINUS . '°C',
                'weather' => self::WEATHER_MAP[$row["weather"][0]["id"]] ?? 'その他の天気',
                'img' => 'https://openweathermap.org/img/wn/' . $row["weather"][0]["icon"] . '@2x.png',
            ];
        }
        return $weathers;
    }

}