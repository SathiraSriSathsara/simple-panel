<?php

// Available languages: English (default) and Sinhala (auto-translated)
$available_languages = [
    'en' => 'English',
    'si' => 'සිංහල',
];

$default_language = 'en';

// Get current language from URL or cookie
$current_language = get_current_language();

// Translation cache to avoid repeated API calls
$translation_cache = [];

function get_current_language()
{
    global $available_languages, $default_language;

    if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $available_languages)) {
        $lang = $_GET['lang'];
        setcookie('panel_lang', $lang, time() + (365 * 24 * 60 * 60), '/');
        return $lang;
    }

    if (isset($_COOKIE['panel_lang']) && array_key_exists($_COOKIE['panel_lang'], $available_languages)) {
        return $_COOKIE['panel_lang'];
    }

    return $default_language;
}

/**
 * Translate text using Google Translate API
 * @param string $text - Text to translate
 * @param string $targetLang - Target language code (e.g., 'si' for Sinhala)
 * @return string - Translated text
 */
function google_translate($text, $targetLang)
{
    global $translation_cache;
    
    // Check cache first
    $cacheKey = md5($text . '_' . $targetLang);
    if (isset($translation_cache[$cacheKey])) {
        return $translation_cache[$cacheKey];
    }

    // Use Google Translate API (free endpoint)
    $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=' . 
           urlencode($targetLang) . '&dt=t&q=' . urlencode($text);
    
    try {
        $response = @file_get_contents($url);
        if ($response !== false) {
            $result = json_decode($response, true);
            if (isset($result[0][0][0])) {
                $translated = $result[0][0][0];
                $translation_cache[$cacheKey] = $translated;
                return $translated;
            }
        }
    } catch (Exception $e) {
        // If translation fails, return original text
    }
    
    return $text;
}

/**
 * Main translation function
 * @param string $text - English text to translate
 * @return string - Translated text or original if language is English
 */
function t($text)
{
    global $current_language, $default_language;
    
    // If current language is English, return as-is
    if ($current_language === $default_language) {
        return $text;
    }
    
    // Translate to target language
    return google_translate($text, $current_language);
}

function url_with_lang(array $extra_params = [])
{
    global $current_language;
    $query = array_merge($_GET, $extra_params, ['lang' => $current_language]);
    return '?' . http_build_query($query);
}
