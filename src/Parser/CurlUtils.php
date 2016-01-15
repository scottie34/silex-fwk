<?php

namespace SilexFwk\Parser;


class CurlUtils
{

    public static function getResource($url, $options = array())
    {
        $ch = curl_init($url);
        $outputFile = tmpfile();
        curl_setopt($ch, CURLOPT_FILE, $outputFile);
        curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('Erreur Curl : ' . curl_error($ch));
        }

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) {
            throw new \Exception('Curl : last received HTTP code is ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\r\n"
            );
        }
        $outputFileName = stream_get_meta_data($outputFile)['uri'];
        $html = file_get_contents($outputFileName);
        fclose($outputFile);

        if (empty($html)) {
            throw new \Exception('Curl returned an empty html page.');
        }
        curl_close($ch);

        return $html;
    }



}