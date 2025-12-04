<?php


use App\Models\BusinessSetting;
use Illuminate\Support\Facades\App;

function remove_invalid_charcaters($str)
{
    return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $str);
}

if (!function_exists('translate')) {
    function translate($key, $replace = [])
    {
        $key = strpos($key, 'messages.') === 0 ? substr($key, 9) : $key;
        $local = App::getLocale();
//
//        App::setLocale($local);
        try {
            $lang_array = include(base_path('lang/' . $local . '/messages.php'));

            if (!array_key_exists($key, $lang_array)) {

                $processed_key = str_replace('_', ' ', remove_invalid_charcaters($key));
                $lang_array[$key] = $processed_key;
                $str = "<?php return " . var_export($lang_array, true) . ";";
                file_put_contents(base_path('lang/' . $local . '/messages.php'), $str);
                $result = $processed_key;
            } else {
                $result = trans('messages.' . $key, $replace);
            }
        } catch (\Exception $exception) {
            $result = trans('messages.' . $key, $replace);
        }
        return $result;
//        return trim($result, "messages.");
    }
}

function get_business_settings($name, $json_decode = true)
{
    $config = null;

    $setting = BusinessSetting::where('key', $name)->first();

    if ($setting) {
        $config = $json_decode ? json_decode($setting->value, true) : $setting->value;
    }

    return $config;
}

if (!function_exists('update_env')) {
    function update_env($data = []): void
    {

        $path = base_path('.env');

        if (file_exists($path)) {
            foreach ($data as $key => $value) {
                file_put_contents($path, str_replace(
                    $key . '=' . env($key), $key . '=' . $value, file_get_contents($path)
                ));
            }
        }

    }
}
