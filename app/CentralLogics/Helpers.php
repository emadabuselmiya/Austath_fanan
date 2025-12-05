<?php

namespace App\CentralLogics;

use App\Models\BusinessSetting;
use App\Models\Course;
use App\Models\StudentCourseActivation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Helpers
{
    public static function sendNotificationByCourse($course_id): bool
    {
        $course = Course::find($course_id);
        $data = [
            'title' => $course->name,
            'description' => "تم تنزيل محاضرة جديدة",
            'image_url' => '',
            'url' => '',
        ];

        $studentCourseActivation = StudentCourseActivation::where('course_id', $course_id)->get()->pluck('student_id')->toArray();
        $student_ids = array_unique($studentCourseActivation);

        foreach ($student_ids as $item) {
            $student = User::find($item);
            NotificationLogic::send_push_notif_to_device($student->fcm_token, $data);
        }

        return true;
    }

    public static function generateRandom($length = 6): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }


    public static function getJsonToString($value): string
    {
        try {
            $string = collect($value)->map(function ($value, $key) {
                return "$key: $value";
            })->implode(', ');
        } catch (\Exception $e) {
            $string = json_encode($value, JSON_PRETTY_PRINT);
        }
        return $string;
    }

    protected static function normalize($v)
    {
        if ($v instanceof \DateTimeInterface) return $v->format('c');
        if (is_object($v)) return method_exists($v, '__toString') ? (string)$v : json_encode($v);
        return $v;
    }

    public static function filter_phone_number($phoneNumber): array|string
    {
        // Step 1: Remove spaces and dashes
        $phoneNumber = str_replace([' ', '-'], '', $phoneNumber);

        // Step 2: Replace "+972" with "0"
        $phoneNumber = str_replace("+972", "0", $phoneNumber);
        $phoneNumber = str_replace("+970", "0", $phoneNumber);

        return $phoneNumber;
    }

    public static function error_processor($validator): array
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }

    public static function error_format($key, $mesage, $errors = [])
    {
        $errors[] = ['code' => $key, 'message' => $mesage];

        return $errors;
    }

    public static function convert2english($string): array|string
    {
        $newNumbers = range(0, 9);
        // 1. Persian HTML decimal
        $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        // 2. Arabic HTML decimal
        $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
        // 3. Arabic Numeric
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        // 4. Persian Numeric
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        $string = str_replace($persianDecimal, $newNumbers, $string);
        $string = str_replace($arabicDecimal, $newNumbers, $string);
        $string = str_replace($arabic, $newNumbers, $string);
        return str_replace($persian, $newNumbers, $string);
    }

    public static function isEnglishNumber($number): false|int
    {
        // Regular expression to match English numbers
        $englishNumberPattern = '/^(\d+|\d{1,3}(,\d{3})*)(\.\d+)?$/';

        return preg_match($englishNumberPattern, $number);
    }

    public static function get_avg_rating($rating): float|int
    {
        $total_rating = 0;
        $total_rating += $rating[4];
        $total_rating += $rating[3] * 2;
        $total_rating += $rating[2] * 3;
        $total_rating += $rating[1] * 4;
        $total_rating += $rating[0] * 5;

        if ($total_rating != 0) {
            return $total_rating / array_sum($rating);
        } else {
            return 0;
        }
    }

    public static function check_phone_number($number)
    {
        // check if number is numeric and doesn't contain spaces
        if (is_numeric($number) && strpos($number, ' ') === false) {
            // convert the number to string to check its length
            $number = strval($number);
            if (strlen($number) == 10) {
                return true;
            }
        }
        return false;
    }

    public static function get_business_settings($name, $json_decode = true)
    {
        $config = null;

        $paymentmethod = BusinessSetting::where('key', $name)->first();

        if ($paymentmethod) {
            $config = $json_decode ? json_decode($paymentmethod->value, true) : $paymentmethod->value;
        }

        return $config;
    }

    public static function currency_symbol(): string
    {
        return '$';
//        return '₪';
    }

    public static function format_currency($value): string
    {
        return self::currency_symbol() . ' ' . round($value);
    }

    public static function convertUrlsToLinks($text): array|string|null
    {
        // Regular expression to find URLs
        $urlPattern = '/(https?:\/\/[^\s]+|www\.[^\s]+)/';

        // Replace URLs with anchor tags, adding http:// to www. links
        $textWithLinks = preg_replace_callback($urlPattern, function ($matches) {
            $url = $matches[0];
            if (!preg_match('/^https?:\/\//', $url)) {
                $url = 'http://' . $url;
            }
            return '<a href="' . $url . '" target="_blank">' . $matches[0] . '</a>';
        }, $text);

        return $textWithLinks;
    }

    public static function getDatesBetween($startDate, $endDate, $schedules = []): array
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $times = [];

        foreach ($schedules as $schedule) {
            $times[$schedule->day] = ['start_time' => $schedule->start_time, 'expire_time' => $schedule->expire_time];
        }
        $dates = [];

        for ($date = $start; $date->lte($end); $date->addDay()) {
            $day = $date->dayOfWeek;
            if (isset($times[$day])) {
                $start_date = $date->format('Y-m-d') . ' ' . $times[$day]['start_time'];
                $expire_date = $date->format('Y-m-d') . ' ' . $times[$day]['expire_time'];

                $dates[] = ['start_date' => $start_date, 'expire_date' => $expire_date];
            }
        }

        return $dates;
    }

    public static function day_part(): string
    {
        $part = "";
        $morning_start = date("h:i:s", strtotime("5:00:00"));
        $afternoon_start = date("h:i:s", strtotime("12:01:00"));
        $evening_start = date("h:i:s", strtotime("17:01:00"));
        $evening_end = date("h:i:s", strtotime("21:00:00"));

        if (time() >= $morning_start && time() < $afternoon_start) {
            $part = "morning";
        } elseif (time() >= $afternoon_start && time() < $evening_start) {
            $part = "afternoon";
        } elseif (time() >= $evening_start && time() <= $evening_end) {
            $part = "evening";
        } else {
            $part = "night";
        }

        return $part;
    }

    public static function env_update($key, $value): void
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $key . '=' . env($key),
                $key . '=' . $value,
                file_get_contents($path)
            ));
        }
    }

    public static function get_language_name($key = null)
    {
        $languages = array(
            "af" => "Afrikaans",
            "sq" => "Albanian - shqip",
            "am" => "Amharic - አማርኛ",
            "ar" => "Arabic - العربية",
            "an" => "Aragonese - aragonés",
            "hy" => "Armenian - հայերեն",
            "ast" => "Asturian - asturianu",
            "az" => "Azerbaijani - azərbaycan dili",
            "eu" => "Basque - euskara",
            "be" => "Belarusian - беларуская",
            "bn" => "Bengali - বাংলা",
            "bs" => "Bosnian - bosanski",
            "br" => "Breton - brezhoneg",
            "bg" => "Bulgarian - български",
            "ca" => "Catalan - català",
            "ckb" => "Central Kurdish - کوردی (دەستنوسی عەرەبی)",
            "zh" => "Chinese - 中文",
            "zh-HK" => "Chinese (Hong Kong) - 中文（香港）",
            "zh-CN" => "Chinese (Simplified) - 中文（简体）",
            "zh-TW" => "Chinese (Traditional) - 中文（繁體）",
            "co" => "Corsican",
            "hr" => "Croatian - hrvatski",
            "cs" => "Czech - čeština",
            "da" => "Danish - dansk",
            "nl" => "Dutch - Nederlands",
            "en" => "English",
            "en-AU" => "English (Australia)",
            "en-CA" => "English (Canada)",
            "en-IN" => "English (India)",
            "en-NZ" => "English (New Zealand)",
            "en-ZA" => "English (South Africa)",
            "en-GB" => "English (United Kingdom)",
            "en-US" => "English (United States)",
            "eo" => "Esperanto - esperanto",
            "et" => "Estonian - eesti",
            "fo" => "Faroese - føroyskt",
            "fil" => "Filipino",
            "fi" => "Finnish - suomi",
            "fr" => "French - français",
            "fr-CA" => "French (Canada) - français (Canada)",
            "fr-FR" => "French (France) - français (France)",
            "fr-CH" => "French (Switzerland) - français (Suisse)",
            "gl" => "Galician - galego",
            "ka" => "Georgian - ქართული",
            "de" => "German - Deutsch",
            "de-AT" => "German (Austria) - Deutsch (Österreich)",
            "de-DE" => "German (Germany) - Deutsch (Deutschland)",
            "de-LI" => "German (Liechtenstein) - Deutsch (Liechtenstein)",
            "de-CH" => "German (Switzerland) - Deutsch (Schweiz)",
            "el" => "Greek - Ελληνικά",
            "gn" => "Guarani",
            "gu" => "Gujarati - ગુજરાતી",
            "ha" => "Hausa",
            "haw" => "Hawaiian - ʻŌlelo Hawaiʻi",
            "he" => "Hebrew - עברית",
            "hi" => "Hindi - हिन्दी",
            "hu" => "Hungarian - magyar",
            "is" => "Icelandic - íslenska",
            "id" => "Indonesian - Indonesia",
            "ia" => "Interlingua",
            "ga" => "Irish - Gaeilge",
            "it" => "Italian - italiano",
            "it-IT" => "Italian (Italy) - italiano (Italia)",
            "it-CH" => "Italian (Switzerland) - italiano (Svizzera)",
            "ja" => "Japanese - 日本語",
            "kn" => "Kannada - ಕನ್ನಡ",
            "kk" => "Kazakh - қазақ тілі",
            "km" => "Khmer - ខ្មែរ",
            "ko" => "Korean - 한국어",
            "ku" => "Kurdish - Kurdî",
            "ky" => "Kyrgyz - кыргызча",
            "lo" => "Lao - ລາວ",
            "la" => "Latin",
            "lv" => "Latvian - latviešu",
            "ln" => "Lingala - lingála",
            "lt" => "Lithuanian - lietuvių",
            "mk" => "Macedonian - македонски",
            "ms" => "Malay - Bahasa Melayu",
            "ml" => "Malayalam - മലയാളം",
            "mt" => "Maltese - Malti",
            "mr" => "Marathi - मराठी",
            "mn" => "Mongolian - монгол",
            "ne" => "Nepali - नेपाली",
            "no" => "Norwegian - norsk",
            "nb" => "Norwegian Bokmål - norsk bokmål",
            "nn" => "Norwegian Nynorsk - nynorsk",
            "oc" => "Occitan",
            "or" => "Oriya - ଓଡ଼ିଆ",
            "om" => "Oromo - Oromoo",
            "ps" => "Pashto - پښتو",
            "fa" => "Persian - فارسی",
            "pl" => "Polish - polski",
            "pt" => "Portuguese - português",
            "pt-BR" => "Portuguese (Brazil) - português (Brasil)",
            "pt-PT" => "Portuguese (Portugal) - português (Portugal)",
            "pa" => "Punjabi - ਪੰਜਾਬੀ",
            "qu" => "Quechua",
            "ro" => "Romanian - română",
            "mo" => "Romanian (Moldova) - română (Moldova)",
            "rm" => "Romansh - rumantsch",
            "ru" => "Russian - русский",
            "gd" => "Scottish Gaelic",
            "sr" => "Serbian - српски",
            "sh" => "Serbo-Croatian - Srpskohrvatski",
            "sn" => "Shona - chiShona",
            "sd" => "Sindhi",
            "si" => "Sinhala - සිංහල",
            "sk" => "Slovak - slovenčina",
            "sl" => "Slovenian - slovenščina",
            "so" => "Somali - Soomaali",
            "st" => "Southern Sotho",
            "es" => "Spanish - español",
            "es-AR" => "Spanish (Argentina) - español (Argentina)",
            "es-419" => "Spanish (Latin America) - español (Latinoamérica)",
            "es-MX" => "Spanish (Mexico) - español (México)",
            "es-ES" => "Spanish (Spain) - español (España)",
            "es-US" => "Spanish (United States) - español (Estados Unidos)",
            "su" => "Sundanese",
            "sw" => "Swahili - Kiswahili",
            "sv" => "Swedish - svenska",
            "tg" => "Tajik - тоҷикӣ",
            "ta" => "Tamil - தமிழ்",
            "tt" => "Tatar",
            "te" => "Telugu - తెలుగు",
            "th" => "Thai - ไทย",
            "ti" => "Tigrinya - ትግርኛ",
            "to" => "Tongan - lea fakatonga",
            "tr" => "Turkish - Türkçe",
            "tk" => "Turkmen",
            "tw" => "Twi",
            "uk" => "Ukrainian - українська",
            "ur" => "Urdu - اردو",
            "ug" => "Uyghur",
            "uz" => "Uzbek - o‘zbek",
            "vi" => "Vietnamese - Tiếng Việt",
            "wa" => "Walloon - wa",
            "cy" => "Welsh - Cymraeg",
            "fy" => "Western Frisian",
            "xh" => "Xhosa",
            "yi" => "Yiddish",
            "yo" => "Yoruba - Èdè Yorùbá",
            "zu" => "Zulu - isiZulu",
        );
        return array_key_exists($key, $languages) ? $languages[$key] : $languages;
    }

    public static function getIntro($phone): array
    {
        $countries = [
            '+93' => ['name' => 'Afghanistan', 'flag' => '🇦🇫'],
            '+91' => ['name' => 'India', 'flag' => '🇮🇳'],
            '+355' => ['name' => 'Albania', 'flag' => '🇦🇱'],
            '+213' => ['name' => 'Algeria', 'flag' => '🇩🇿'],
            '+376' => ['name' => 'Andorra', 'flag' => '🇦🇩'],
            '+244' => ['name' => 'Angola', 'flag' => '🇦🇴'],
            '+54' => ['name' => 'Argentina', 'flag' => '🇦🇷'],
            '+374' => ['name' => 'Armenia', 'flag' => '🇦🇲'],
            '+61' => ['name' => 'Australia', 'flag' => '🇦🇺'],
            '+43' => ['name' => 'Austria', 'flag' => '🇦🇹'],
            '+994' => ['name' => 'Azerbaijan', 'flag' => '🇦🇿'],
            '+1242' => ['name' => 'Bahamas', 'flag' => '🇧🇸'],
            '+973' => ['name' => 'Bahrain', 'flag' => '🇧🇭'],
            '+880' => ['name' => 'Bangladesh', 'flag' => '🇧🇩'],
            '+1246' => ['name' => 'Barbados', 'flag' => '🇧🇧'],
            '+375' => ['name' => 'Belarus', 'flag' => '🇧🇾'],
            '+32' => ['name' => 'Belgium', 'flag' => '🇧🇪'],
            '+501' => ['name' => 'Belize', 'flag' => '🇧🇿'],
            '+229' => ['name' => 'Benin', 'flag' => '🇧🇯'],
            '+975' => ['name' => 'Bhutan', 'flag' => '🇧🇹'],
            '+591' => ['name' => 'Bolivia', 'flag' => '🇧🇴'],
            '+387' => ['name' => 'Bosnia and Herzegovina', 'flag' => '🇧🇦'],
            '+267' => ['name' => 'Botswana', 'flag' => '🇧🇼'],
            '+55' => ['name' => 'Brazil', 'flag' => '🇧🇷'],
            '+673' => ['name' => 'Brunei', 'flag' => '🇧🇳'],
            '+359' => ['name' => 'Bulgaria', 'flag' => '🇧🇬'],
            '+226' => ['name' => 'Burkina Faso', 'flag' => '🇧🇫'],
            '+257' => ['name' => 'Burundi', 'flag' => '🇧🇮'],
            '+855' => ['name' => 'Cambodia', 'flag' => '🇰🇭'],
            '+237' => ['name' => 'Cameroon', 'flag' => '🇨🇲'],
            '+238' => ['name' => 'Cape Verde', 'flag' => '🇨🇻'],
            '+236' => ['name' => 'Central African Republic', 'flag' => '🇨🇫'],
            '+235' => ['name' => 'Chad', 'flag' => '🇹🇩'],
            '+56' => ['name' => 'Chile', 'flag' => '🇨🇱'],
            '+86' => ['name' => 'China', 'flag' => '🇨🇳'],
            '+57' => ['name' => 'Colombia', 'flag' => '🇨🇴'],
            '+269' => ['name' => 'Comoros', 'flag' => '🇰🇲'],
            '+506' => ['name' => 'Costa Rica', 'flag' => '🇨🇷'],
            '+385' => ['name' => 'Croatia', 'flag' => '🇭🇷'],
            '+53' => ['name' => 'Cuba', 'flag' => '🇨🇺'],
            '+357' => ['name' => 'Cyprus', 'flag' => '🇨🇾'],
            '+420' => ['name' => 'Czech Republic', 'flag' => '🇨🇿'],

            '+1' => ['name' => 'United States', 'flag' => '🇺🇸'],
            '+44' => ['name' => 'United Kingdom', 'flag' => '🇬🇧'],
            '+970' => ['name' => 'Palestine', 'flag' => '🇵🇸'],
            '+974' => ['name' => 'Qatar', 'flag' => '🇶🇦'],
            '+971' => ['name' => 'United Arab Emirates', 'flag' => '🇦🇪'],
            '+966' => ['name' => 'Saudi Arabia', 'flag' => '🇸🇦'],
            '+20' => ['name' => 'Egypt', 'flag' => '🇪🇬'],
            '+962' => ['name' => 'Jordan', 'flag' => '🇯🇴'],
            '+961' => ['name' => 'Lebanon', 'flag' => '🇱🇧'],
            '+963' => ['name' => 'Syria', 'flag' => '🇸🇾'],
            '+964' => ['name' => 'Iraq', 'flag' => '🇮🇶'],
            '+967' => ['name' => 'Yemen', 'flag' => '🇾🇪'],
        ];
        foreach (array_keys($countries) as $code) {
            if (str_starts_with($phone, $code)) {
                return $countries[$code];
            }
        }
        return ['name' => 'Unknown', 'flag' => '🌐'];
    }

    public static function get_timezone_name($key = null)
    {
        $timezones = timezone_identifiers_list();
        $timezone_names = array();

        foreach ($timezones as $tz) {
            $timezone_names[$tz] = str_replace('_', ' ', $tz);
        }

        return array_key_exists($key, $timezone_names) ? $timezone_names[$key] : $timezone_names;
    }

    public static function remove_invalid_charcaters($str): array|string
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $str);
    }

    public static function normalizeArabicText($text): array|string
    {
        $diacritics = ['َ', 'ً', 'ُ', 'ٌ', 'ِ', 'ٍ', 'ْ', 'ّ']; // تشكيلات
        $text = str_replace($diacritics, '', $text);

        // تحويل الحروف التي قد تسبب مشاكل في البحث
        $text = str_replace(['أ', 'إ', 'آ'], 'ا', $text);
        $text = str_replace(['ة'], 'ه', $text); // في بعض الأحيان يساعد
        return $text;
    }

}
